<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 min-h-screen">

<!-- HEADER -->
<header class="bg-white shadow-sm border-b border-orange-100">

    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

        <!-- LOGO -->
        <h2 class="font-extrabold text-2xl">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-yellow-500">
                DropLink Admin
            </span>
        </h2>

        <!-- USER -->
        <div class="flex items-center gap-4">

            <div class="hidden sm:flex items-center gap-3 bg-orange-50 px-4 py-2 rounded-xl border border-orange-100">

                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-yellow-500 flex items-center justify-center text-white font-bold shadow">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="leading-tight">
                    <p class="font-bold text-gray-800 text-sm">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        Administrator
                    </p>
                </div>

            </div>

            <a
                href="{{ route('dashboard') }}"
                class="px-5 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold shadow-md transition-all duration-300"
            >
                Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold shadow-md transition-all duration-300"
                >
                    Logout
                </button>
            </form>

        </div>

    </div>

</header>

<!-- CONTENT -->
<main class="py-12">

    <div class="max-w-7xl mx-auto px-6">

        <!-- ALERT -->
        @if(session('success'))

            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm">
                <p class="font-medium text-yellow-800">
                    {{ session('success') }}
                </p>
            </div>

        @endif

        @if(session('error'))

            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <p class="font-medium text-red-700">
                    {{ session('error') }}
                </p>
            </div>

        @endif

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- USERS -->
            <div class="bg-white rounded-2xl shadow-lg border border-orange-100 p-6">

                <p class="text-gray-500 text-sm font-medium">
                    Total Users
                </p>

                <h2 class="text-4xl font-extrabold text-orange-600 mt-2">
                    {{ $users->count() }}
                </h2>

            </div>

            <!-- MEDIA -->
            <div class="bg-white rounded-2xl shadow-lg border border-orange-100 p-6">

                <p class="text-gray-500 text-sm font-medium">
                    Total Media
                </p>

                <h2 class="text-4xl font-extrabold text-yellow-500 mt-2">
                    {{ $media->count() }}
                </h2>

            </div>

            <!-- ADMINS -->
            <div class="bg-white rounded-2xl shadow-lg border border-orange-100 p-6">

                <p class="text-gray-500 text-sm font-medium">
                    Total Admin
                </p>

                <h2 class="text-4xl font-extrabold text-red-500 mt-2">
                    {{ $users->where('role', 'admin')->count() }}
                </h2>

            </div>

        </div>

        <!-- USERS TABLE -->
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-orange-100 mb-10">

            <div class="p-8">

                <div class="flex items-center mb-6">

                    <div class="p-2 bg-orange-100 rounded-lg mr-4">

                        <svg class="w-6 h-6 text-orange-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7">
                            </path>

                        </svg>

                    </div>

                    <h3 class="text-xl font-bold text-gray-800">
                        User Management
                    </h3>

                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">

                    <table class="min-w-full bg-white">

                        <thead>

                        <tr class="bg-orange-50 border-b border-orange-100">

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Name
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Email
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Role
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                        @foreach($users as $user)

                            <tr class="hover:bg-orange-50/50 transition">

                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $user->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($user->role === 'admin')

                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            ADMIN
                                        </span>

                                    @else

                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            USER
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    @if(auth()->id() !== $user->id)

                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            id="delete-user-{{ $user->id }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                onclick="deleteUser('{{ $user->id }}')"
                                                class="text-red-500 hover:text-red-700 font-semibold"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    @else

                                        <span class="text-gray-400 text-sm">
                                            Your Account
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- MEDIA TABLE -->
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-orange-100">

            <div class="p-8">

                <div class="flex items-center mb-6">

                    <div class="p-2 bg-yellow-100 rounded-lg mr-4">

                        <svg class="w-6 h-6 text-yellow-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>

                        </svg>

                    </div>

                    <h3 class="text-xl font-bold text-gray-800">
                        Uploaded Media
                    </h3>

                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">

                    <table class="min-w-full bg-white">

                        <thead>

                        <tr class="bg-orange-50 border-b border-orange-100">

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                File
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Owner
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Type
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Size
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                        @foreach($media as $item)

                            <tr class="hover:bg-orange-50/50 transition">

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $item->original_name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->user->name ?? 'Unknown' }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ $item->mime_type }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ round($item->file_size / 1024 / 1024, 2) }} MB
                                </td>

                                <td class="px-6 py-4">

                                    <form
                                        action="{{ route('admin.media.destroy', $item) }}"
                                        method="POST"
                                        id="delete-media-{{ $item->id }}"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            onclick="deleteMedia('{{ $item->id }}')"
                                            class="text-red-500 hover:text-red-700 font-semibold"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- DELETE USER -->
<script>
    function deleteUser(id) {

        Swal.fire({
            title: 'Delete user?',
            text: 'Semua media user juga akan ikut terhapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f59e0b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1rem'
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById('delete-user-' + id).submit();
            }

        });

    }

    function deleteMedia(id) {

        Swal.fire({
            title: 'Delete media?',
            text: 'File akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f59e0b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1rem'
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById('delete-media-' + id).submit();
            }

        });

    }
</script>

</body>
</html>