<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title ?? 'Agendaku | Class&Life Sync')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    colors: {
                        'bg-main': '#FBF9F5',
                        'bg-card': '#FFFFFF',
                        'bg-sidebar': '#FFFFFF',
                        'primary-orange': '#FF5A36',
                        'primary-hover': '#E04725',
                        'primary-light': '#FFF0ED',
                        'secondary-teal': '#2EC4B6',
                        'secondary-light': '#E8F9F8',
                        'accent-yellow': '#FFB800',
                        'accent-yellow-light': '#FFF9E6',
                        'text-dark': '#1E2522',
                        'text-muted': '#6C7571',
                        'border-custom': '#E8E3D9',
                        // 'cat-sekolah': '#3A86EF',
                        'cat-sekolah': '#FF5E3A',
                        // 'cat-sekolah-bg': '#EFF6FF',
                        'cat-sekolah-bg': '#FFDFD8',
                        'cat-pribadi': '#10B981',
                        'cat-pribadi-bg': '#ECFDF5',
                        'cat-acara': '#8B5CF6',
                        'cat-acara-bg': '#F5F3FF',
                        'prio-tinggi': '#EF4444',
                        'prio-sedang': '#F59E0B',
                        'prio-rendah': '#10B981',
                    },
                    boxShadow: {
                        'pop': '4px 4px 0px #1E2522',
                        'pop-lg': '6px 6px 0px #1E2522',
                        'pop-xl': '8px 8px 0px #1E2522',
                        'pop-sm': '2px 2px 0px #1E2522',
                        'pop-3px': '3px 3px 0px #1E2522',
                        'pop-5px': '5px 5px 0px #1E2522',
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @vite(['resources/js/app.js']) --}}
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
