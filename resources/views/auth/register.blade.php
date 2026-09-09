@extends('layouts.app')

@php
    $hideSidebar = true;
@endphp

@section('title', 'Agendaku - Register')

@section('content')
<!-- PAGE: SIGNUP -->
<section id="page-signup" class="auth-wrapper min-h-[80vh] grid place-items-center w-full">
  <div class="auth-card bg-bg-card border-2 border-text-dark rounded-3xl p-9 lg:p-10 w-full max-w-[450px] shadow-pop-xl relative">
    <!-- Auth Header with Brand -->
    <div class="auth-header-bar flex items-center gap-7 mb-6 pb-4 border-b-2 border-dashed border-border-custom">
      <div class="brand flex items-center gap-3 border-none pb-0">
        <div class="brand-logo w-10 h-10 bg-primary-orange rounded-xl grid place-items-center text-white font-extrabold text-lg -rotate-3">A</div>
        <div class="brand-text">
          <h1 class="text-lg font-extrabold leading-tight text-text-dark">Agendaku</h1>
          <span class="text-[0.65rem] font-bold text-primary-orange uppercase tracking-wider block">Class&Life Sync</span>
        </div>
      </div>
    </div>

    <h2 class="text-3xl font-extrabold tracking-tight mb-1 text-text-dark">Buat Akun Baru</h2>
    <p class="text-text-muted text-sm mb-7 font-medium">Mulai atur jadwal akademis dan kegiatan personalmu</p>

    <form onsubmit="handleSignup(event)">
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama Lengkap</label>
        <input type="text" id="signup-fullname" placeholder="Contoh: Budi Santoso" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Username</label>
        <input type="text" id="signup-username" placeholder="Buat username unik" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      {{-- <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Role / Peran</label>
        <select id="signup-role" class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
          <option value="siswa">Siswa</option>
          <option value="guru">Guru</option>
          <option value="osis">Pengurus OSIS</option>
        </select>
      </div> --}}
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Password</label>
        <input type="password" id="signup-password" placeholder="••••••••" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <button type="submit" class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">Daftar Akun 🎉</button>
    </form>

    <div class="auth-switch text-center mt-6 text-sm text-text-muted font-semibold">
      Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary-orange font-extrabold hover:underline">Masuk di sini</a>
    </div>
  </div>
</section>
@endsection
