<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DropLink - Simpan & Bagikan File</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 text-gray-800 font-sans selection:bg-orange-500 selection:text-white overflow-hidden">

    <!-- BACKGROUND BLUR -->
    <div class="fixed inset-0 -z-10">

        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-orange-300 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-pulse"></div>

        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-pulse"></div>

        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-orange-400 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-pulse"></div>

    </div>

    <!-- WRAPPER -->
    <div class="relative min-h-screen flex flex-col items-center justify-center px-6">

        <!-- CONTENT -->
        <div class="relative z-10 w-full max-w-4xl text-center">

            <!-- LOGO -->
            <h1 class="text-6xl md:text-8xl font-extrabold tracking-tight mb-4">

                <span class="bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-yellow-500">
                    DropLink.
                </span>

            </h1>

            <!-- DESCRIPTION -->
            <p class="mt-6 text-xl md:text-2xl text-gray-600 font-medium max-w-2xl mx-auto mb-10 leading-relaxed">
                Tempat paling aman dan cepat buat nyimpen sekaligus ngebagiin file lu ke seluruh dunia.
            </p>

            <!-- BUTTON -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">

                @if (Route::has('login'))

                    @auth

                        <a
                            href="{{ url('/dashboard') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                        >
                            🚀 Ke Dashboard
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-orange-200 text-orange-600 hover:bg-orange-50 rounded-full font-bold text-lg shadow-sm hover:shadow-md transition-all duration-300"
                        >
                            Masuk
                        </a>

                        @if (Route::has('register'))

                            <a
                                href="{{ route('register') }}"
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                            >
                                Daftar Sekarang ✨
                            </a>

                        @endif

                    @endauth

                @endif

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="absolute bottom-6 text-gray-400 text-sm font-medium text-center">
            &copy; {{ date('Y') }} DropLink. All rights reserved.
        </footer>

    </div>

</body>
</html>