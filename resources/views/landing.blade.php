@extends('layouts.landing')

@section('content')
@include('landing.partials.navbar')
@include('landing.partials.hero')
@include('landing.partials.layanan')
@include('landing.partials.event')
@include('landing.partials.cta')
@include('landing.partials.testimoni')
@include('landing.partials.faq')
@include('landing.partials.berita')

@include('landing.partials.footer')
@endsection
