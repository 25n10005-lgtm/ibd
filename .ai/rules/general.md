---
paths:
  - '**/artisan,**/*.php'
  - '**/*.json'
---

# General

## Artisan dari host pakai DB 127.0.0.1:3307
MySQL Docker hanya bisa diakses dari host via 127.0.0.1:3307. .env memakai DB_HOST=mysql untuk di dalam container Sail. Setiap menjalankan php artisan dari host (migrate, tinker, db:*) wajib override dulu: $env:DB_HOST="127.0.0.1"; $env:DB_PORT="3307". Jangan ubah .env karena container butuh nilai aslinya.

## Akses Figma via figma-developer-mcp + FIGMA_API_KEY
Figma MCP = npx figma-developer-mcp --stdio di .mcp.json, butuh env FIGMA_API_KEY (Personal Access Token). Tanpa sesi MCP pun bisa fetch langsung via CLI: figma-developer-mcp fetch --figma-api-key KEY --file-key KEY --node-id "6:2". Jangan pernah commit token ke repo.
