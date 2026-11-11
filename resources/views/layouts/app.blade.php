<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Agendaku | Class&Life Sync')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body
    class="bg-bg-main text-text-dark min-h-screen flex bg-[radial-gradient(circle_at_10%_20%,rgba(255,90,54,0.03)_0%,transparent_20%),radial-gradient(circle_at_90%_80%,rgba(46,196,182,0.03)_0%,transparent_20%)] bg-fixed font-sans">

    <!-- Mobile Overlay Backdrop -->
    <div id="sidebar-overlay"
        class="hidden fixed inset-0 bg-[#1E2522]/50 backdrop-blur-sm z-[990] transition-opacity duration-300"
        onclick="toggleMobileMenu()"></div>

    <!-- FIXED VERTICAL SIDEBAR / HEADER -->
    @unless (isset($hideSidebar) && $hideSidebar)
        @include('components.sidebar')
    @endunless

    <!-- MAIN CONTENT CONTAINER -->
    <main
        class="container {{ isset($hideSidebar) && $hideSidebar ? 'auth-mode !ml-0 !w-full !max-w-full !p-8 justify-center items-center' : 'ml-0 lg:ml-[280px] w-full lg:w-[calc(100%-280px)] max-w-none lg:max-w-[1150px] p-5 pt-[95px] lg:p-12 min-h-screen flex-1 flex flex-col [&.auth-mode]:!ml-0 [&.auth-mode]:!w-full [&.auth-mode]:!max-w-full [&.auth-mode]:!p-8 [&.auth-mode]:justify-center [&.auth-mode]:items-center' }}">
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
