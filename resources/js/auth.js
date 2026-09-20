// Login Google via Clerk (Core API):
// tombol Google -> authenticateWithRedirect -> /sso-callback -> handleRedirectCallback / setActive
// -> /dashboard (provisioning & penyatuan akun via email di backend).

const googleBtn = document.getElementById('google-btn');
const ssoPage = document.getElementById('sso-callback');
const clerkRoot = googleBtn ?? ssoPage;

if (clerkRoot) {
    const rawRedirect = clerkRoot.dataset.redirect ?? '/dashboard';
    const abs = (to) => new URL(to, window.location.origin).href;
    const redirect = /^https?:\/\//i.test(rawRedirect) ? rawRedirect : abs(rawRedirect);
    const ssoCallback = abs('/sso-callback');
    const SSO_TIMEOUT_MS = 25000;

    const statusBox = document.getElementById('clerk-status');
    const say = (msg, tone) => {
        if (!statusBox) {
            return;
        }
        statusBox.className = tone === 'error'
            ? 'w-full rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600'
            : 'w-full rounded-xl bg-amber-50 px-4 py-3 text-[13px] font-medium text-amber-700';
        statusBox.textContent = msg;
        statusBox.classList.remove('hidden');
    };

    const withTimeout = (promise, ms, message) => Promise.race([
        promise,
        new Promise((_, reject) => setTimeout(() => reject(new Error(message)), ms)),
    ]);

    // Publishable key berbentuk pk_test_<base64url> / pk_live_<base64url>;
    // payload-nya (setelah decode) adalah Frontend API host + '$'.
    const frontendApi = (key) => {
        const payload = (key ?? '').replace(/^pk_(test|live)_/, '');
        if (!payload) {
            return null;
        }
        const b64 = payload.replace(/-/g, '+').replace(/_/g, '/');
        const host = atob(b64 + '='.repeat((4 - (b64.length % 4)) % 4)).replace(/\$$/, '');

        return /^[a-z0-9.-]+\.[a-z]{2,}$/i.test(host) ? host : null;
    };

    const loadScript = (src, publishableKey) => new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        script.crossOrigin = 'anonymous';
        script.setAttribute('data-clerk-publishable-key', publishableKey);
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('gagal memuat ' + src));
        document.head.appendChild(script);
    });

    const loadClerk = async () => {
        if (window.Clerk?.load) {
            await window.Clerk.load({ afterSignInUrl: redirect, afterSignUpUrl: redirect });
            return window.Clerk;
        }

        const publishableKey = clerkRoot.dataset.publishableKey;
        if (!publishableKey) {
            throw new Error('Kunci Clerk belum dikonfigurasi.');
        }

        const host = frontendApi(publishableKey);
        // Hangatkan koneksi TLS lebih awal agar script Clerk lebih cepat diunduh.
        for (const origin of ['https://' + host, 'https://cdn.jsdelivr.net'].filter(Boolean)) {
            try {
                const link = document.createElement('link');
                link.rel = 'preconnect';
                link.href = origin;
                link.crossOrigin = 'anonymous';
                document.head.appendChild(link);
            } catch { /* abaikan */ }
        }
        const candidates = [
            host && 'https://' + host + '/npm/@clerk/clerk-js@6/dist/clerk.browser.js',
            'https://cdn.jsdelivr.net/npm/@clerk/clerk-js@6/dist/clerk.browser.js',
            'https://unpkg.com/@clerk/clerk-js@6/dist/clerk.browser.js',
        ].filter(Boolean);

        let lastErr = null;
        for (const src of candidates) {
            try {
                await loadScript(src, publishableKey);
                lastErr = null;
                break;
            } catch (err) {
                lastErr = err;
            }
        }

        if (lastErr || !window.Clerk?.load) {
            throw new Error('tidak dapat menghubungi server Clerk. Periksa koneksi internet, matikan pemblokir iklan/Brave Shields, atau pastikan instance Clerk masih aktif.');
        }

        await window.Clerk.load({ afterSignInUrl: redirect, afterSignUpUrl: redirect });
        return window.Clerk;
    };

    // Pastikan wadah captcha bawaan Clerk selalu ada (dibutuhkan
    // saat sign-up via transfer / bot protection aktif).
    const ensureCaptcha = () => {
        if (!document.getElementById('clerk-captcha')) {
            const el = document.createElement('div');
            el.id = 'clerk-captcha';
            (ssoPage ?? googleBtn)?.after(el);
        }
    };

    const clerkErrorText = (err, fallback) => err?.errors?.[0]?.longMessage
        ?? err?.errors?.[0]?.message
        ?? err?.message
        ?? fallback;

    // Finalisasi di halaman kembalian SSO
    const runSsoCallback = async (clerk) => {
        const go = (to) => {
            window.location.href = to;
        };

        say('Memverifikasi login Google…', 'warn');

        if (clerk.user) {
            say('Verifikasi berhasil, membuka dashboard…', 'warn');
            go(redirect);
            return;
        }

        // 1. Coba metode resmi Clerk SDK handleRedirectCallback
        if (typeof clerk.handleRedirectCallback === 'function') {
            try {
                await clerk.handleRedirectCallback({
                    signInForceRedirectUrl: redirect,
                    signUpForceRedirectUrl: redirect,
                    signInFallbackRedirectUrl: redirect,
                    signUpFallbackRedirectUrl: redirect,
                    firstFactorUrl: '/login',
                    secondFactorUrl: '/login',
                });

                if (clerk.user || clerk.session) {
                    go(redirect);
                    return;
                }
            } catch (err) {
                console.warn('[auth] handleRedirectCallback:', err);
            }
        }

        // 2. Fallback manual tanpa loop
        const signIn = clerk.client?.signIn;
        const signUp = clerk.client?.signUp;

        if (signIn?.status === 'complete') {
            if (signIn.createdSessionId) {
                await clerk.setActive({ session: signIn.createdSessionId });
            }
            go(redirect);
            return;
        }

        if (signUp?.status === 'complete') {
            if (signUp.createdSessionId) {
                await clerk.setActive({ session: signUp.createdSessionId });
            }
            go(redirect);
            return;
        }

        if (signIn?.isTransferable || signIn?.status === 'needs_identifier') {
            try {
                const res = await signUp.create({ transfer: true });
                if (res?.status === 'complete' && res?.createdSessionId) {
                    await clerk.setActive({ session: res.createdSessionId });
                    go(redirect);
                    return;
                }
            } catch (e) {
                console.error('[auth] transfer to signUp error:', e);
            }
        }

        if (signUp?.isTransferable) {
            try {
                const res = await signIn.create({ transfer: true });
                if (res?.status === 'complete' && res?.createdSessionId) {
                    await clerk.setActive({ session: res.createdSessionId });
                    go(redirect);
                    return;
                }
            } catch (e) {
                console.error('[auth] transfer to signIn error:', e);
            }
        }

        const existingSessionId = signIn?.existingSession?.sessionId ?? signUp?.existingSession?.sessionId;
        if (existingSessionId) {
            await clerk.setActive({ session: existingSessionId });
            go(redirect);
            return;
        }

        if (signUp?.status === 'missing_requirements') {
            const missing = (signUp.missingFields || []).join(', ');
            say('Registrasi Google membutuhkan kelengkapan data (' + (missing || 'data tambahan') + ') di pengaturan Clerk Dashboard.', 'error');
            return;
        }

        say('Menyelesaikan verifikasi Google... Jika tidak diarahkan otomatis, silakan kembali ke halaman masuk.', 'warn');
    };

    const boot = async () => {
        try {
            ensureCaptcha();
            if (ssoPage) {
                say('Menghubungkan ke Clerk…', 'warn');
            }
            const clerk = await withTimeout(
                loadClerk(),
                SSO_TIMEOUT_MS,
                'memuat Clerk terlalu lama. Pastikan koneksi lancar atau matikan pemblokir iklan.',
            );

            const params = new URLSearchParams(window.location.search);
            if (params.get('signed_out') === '1' && clerk.user) {
                await clerk.signOut();
                params.delete('signed_out');
                window.history.replaceState({}, '', window.location.pathname);
            }

            if (ssoPage) {
                say('Menghubungkan ke Clerk…', 'warn');
                await withTimeout(
                    runSsoCallback(clerk),
                    SSO_TIMEOUT_MS,
                    'finalisasi login Google terlalu lama. Silakan muat ulang halaman.',
                );
                return;
            }

            if (clerk.user) {
                // Sudah masuk: alihkan diam-diam tanpa menampilkan
                // flash error bawaan server sesaat.
                document.querySelectorAll('[data-server-flash]').forEach((el) => el.classList.add('hidden'));
                window.location.href = redirect;
                return;
            }

            if (googleBtn) {
                googleBtn.addEventListener('click', async () => {
                    googleBtn.disabled = true;
                    const label = googleBtn.querySelector('[data-label]');
                    const original = label ? label.textContent : null;
                    if (label) {
                        label.textContent = 'Menghubungkan ke Google…';
                    }

                    try {
                        const client = clerk.client;
                        const signIn = client?.signIn;
                        const signUp = client?.signUp;
                        const opts = {
                            strategy: 'oauth_google',
                            redirectUrl: ssoCallback,
                            redirectUrlComplete: redirect,
                            // Metadata default: backend baca unsafe_metadata.requested_role,
                            // kosong/'user' -> dibuat sebagai Warga (aktif langsung).
                            unsafeMetadata: { requested_role: 'user' },
                        };

                        // User baru harus lewat sign-UP dulu agar akun Clerk langsung
                        // terbuat tanpa dilempar ke https://...accounts.dev/default-redirect.
                        // Kalau email sudah terdaftar, Clerk balas form_identifier_exists
                        // -> lanjutkan via sign-IN. Backend tetap provisioning default
                        // role Warga untuk akun baru.
                        try {
                            if (signUp && typeof signUp.authenticateWithRedirect === 'function') {
                                await signUp.authenticateWithRedirect(opts);
                            } else if (signIn && typeof signIn.authenticateWithRedirect === 'function') {
                                await signIn.authenticateWithRedirect(opts);
                            } else if (typeof clerk.authenticateWithRedirect === 'function') {
                                await clerk.authenticateWithRedirect(opts);
                            }
                        } catch (signUpErr) {
                            const code = signUpErr?.errors?.[0]?.code ?? '';
                            const msg = signUpErr?.errors?.[0]?.message ?? signUpErr?.message ?? '';
                            const exists = [
                                'form_identifier_exists',
                                'form_identifier_already_signed_in',
                            ].includes(code) || /already|exists|identifier.*exist/i.test(msg);
                            if (exists && signIn && typeof signIn.authenticateWithRedirect === 'function') {
                                const { unsafeMetadata: _drop, ...signInOpts } = opts;
                                await signIn.authenticateWithRedirect(signInOpts);
                            } else {
                                throw signUpErr;
                            }
                        }
                    } catch (err) {
                        console.error('[auth]', err);
                        const raw = clerkErrorText(err, err);
                        const hint = /not.*enabled|provider|oauth|not_found/i.test(String(raw))
                            ? raw + ' (cek Clerk Dashboard: SSO > Google diaktifkan & Allowed redirect berisi ' + ssoCallback + ')'
                            : raw;
                        say('Login dengan Google gagal: ' + hint, 'error');
                        googleBtn.disabled = false;
                        if (label && original) {
                            label.textContent = original;
                        }
                    }
                });
                googleBtn.classList.remove('hidden');
                googleBtn.classList.add('flex');
            }
        } catch (err) {
            console.error('[auth]', err);
            say('Login dengan Google belum tersedia: ' + (err?.message ?? err), 'warn');
        }
    };

    boot();
}
