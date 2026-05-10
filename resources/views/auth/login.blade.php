<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - DropLink</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen overflow-hidden antialiased">

    <!-- BACKGROUND -->
    <div class="fixed inset-0 -z-10">

        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-gradient-to-br from-orange-300 to-yellow-200 opacity-50 blur-2xl"></div>

        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-gradient-to-tr from-orange-400 to-yellow-300 opacity-40 blur-3xl"></div>

    </div>

    <!-- WRAPPER -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-6 py-10">

        <!-- CARD -->
        <div class="w-full sm:max-w-md bg-white shadow-2xl sm:rounded-3xl border border-orange-50 px-10 py-12 relative z-10">

            <!-- HEADER -->
            <div class="text-center mb-8">

                <h2 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-yellow-500 mb-2">
                    Selamat Datang!
                </h2>

                <p class="text-gray-500 font-medium">
                    Masuk untuk mengelola file kamu.
                </p>

            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}">

                @csrf

                <!-- EMAIL -->
                <div class="mb-5">

                    <label for="email" class="block font-semibold text-sm text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="contoh@email.com"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-2.5 px-4 bg-gray-50 text-gray-800"
                    />

                    @error('email')

                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <!-- PASSWORD -->
                <div class="mb-5">

                    <label for="password" class="block font-semibold text-sm text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-2.5 px-4 bg-gray-50 text-gray-800"
                    />

                    @error('password')

                        <span class="text-red-500 text-xs mt-1 block">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <!-- REMEMBER -->
                <div class="flex items-center justify-between mt-4 mb-8">

                    <label for="remember_me" class="inline-flex items-center cursor-pointer">

                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500"
                        >

                        <span class="ms-2 text-sm text-gray-600 font-medium">
                            Ingat Saya
                        </span>

                    </label>

                    @if (Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm font-semibold text-orange-600 hover:text-orange-800 transition-colors"
                        >
                            Lupa password?
                        </a>

                    @endif

                </div>

                <!-- BUTTON -->
                <div>

                    <button
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                        Masuk ke Dashboard
                    </button>

                </div>

                <!-- REGISTER -->
                <div class="mt-6 text-center text-sm text-gray-500 font-medium">

                    Belum punya akun?

                    <a
                        href="{{ route('register') }}"
                        class="text-orange-600 font-bold hover:underline"
                    >
                        Daftar di sini
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>