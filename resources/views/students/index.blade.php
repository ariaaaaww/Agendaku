@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Dashboard Siswa')

@section('content')
    <section id="page-dashboard">
        @include('components.dashboard-views')
    </section>
@endsection
