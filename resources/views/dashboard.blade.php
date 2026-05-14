<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DropLink Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- HEADER -->
    <header class="bg-white shadow-sm border-b border-orange-100">

        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

            <!-- LOGO -->
            <h2 class="font-extrabold text-2xl">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-yellow-500">
                    DropLink Dashboard
                </span>
            </h2>

            <!-- RIGHT MENU -->
            <div class="flex items-center gap-4">

                <!-- USER -->
                <div class="hidden sm:flex items-center gap-3 bg-orange-50 px-4 py-2 rounded-xl border border-orange-100">

                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-yellow-500 flex items-center justify-center text-white font-bold shadow">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="leading-tight">

                        <p class="font-bold text-gray-800 text-sm">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ Auth::user()->email }}
                        </p>

                    </div>

                </div>

                <!-- ADMIN PANEL -->
                @if(Auth::user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r  bg-yellow-500 hover:bg-yellow-600 text-white font-semibold shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                         Admin Panel
                    </a>

                @endif

                <!-- LOGOUT -->
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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- SUCCESS -->
            @if (session('success'))

                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm flex items-center">

                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>

                    <p class="font-medium text-yellow-800">
                        {{ session('success') }}
                    </p>

                </div>

            @endif

            <!-- UPLOAD CARD -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl mb-8 border border-orange-100">

                <div class="p-8">

                    <!-- TITLE -->
                    <div class="flex items-center mb-6">

                        <div class="p-2 bg-orange-100 rounded-lg mr-4">

                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>

                        </div>

                        <h3 class="text-xl font-bold text-gray-800">
                            Upload Media Baru
                        </h3>

                    </div>

                    <!-- FORM -->
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- LEFT -->
                            <div>

                                <!-- FILE -->
                                <div class="mb-5">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Pilih File (Max 100MB)
                                    </label>

                                    <input
                                        type="file"
                                        name="media"
                                        required
                                        class="block w-full text-sm text-gray-600
                                        file:mr-4 file:py-2.5 file:px-4 file:rounded-lg
                                        file:border-0 file:text-sm file:font-semibold
                                        file:bg-orange-50 file:text-orange-700
                                        hover:file:bg-orange-100 transition-colors
                                        cursor-pointer border border-gray-200 rounded-lg"
                                    >

                                    @error('media')

                                        <span class="text-red-500 text-xs mt-1 block">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                                <!-- VISIBILITY -->
                                <div class="mb-5">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Visibilitas
                                    </label>

                                    <select
                                        name="visibility"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                                    >
                                        <option value="private">
                                            🔒 Private (Hanya Aku)
                                        </option>

                                        <option value="public">
                                            🌐 Public (Bisa pakai Link)
                                        </option>
                                    </select>

                                    @error('visibility')

                                        <span class="text-red-500 text-xs mt-1 block">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div>

                                <div class="mb-5">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Deskripsi (Opsional)
                                    </label>

                                    <textarea
                                        name="description"
                                        rows="4"
                                        placeholder="Tulis catatan kecil tentang file ini..."
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                                    ></textarea>

                                    @error('description')

                                        <span class="text-red-500 text-xs mt-1 block">
                                            {{ $message }}
                                        </span>

                                    @enderror

                                </div>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="mt-4 border-t border-gray-100 pt-6">

                            <button
                                type="submit"
                                class="w-full md:w-auto bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-2.5 px-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                            >
                                🚀 Upload Sekarang
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- TABLE -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-orange-100">

                <div class="p-8">

                    <!-- TITLE -->
                    <div class="flex items-center mb-6">

                        <div class="p-2 bg-yellow-100 rounded-lg mr-4">

                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>

                        </div>

                        <h3 class="text-xl font-bold text-gray-800">
                            Koleksi Media Kamu
                        </h3>

                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto rounded-xl border border-gray-100">

                        <table class="min-w-full bg-white">

                            <thead>

                                <tr class="bg-orange-50 border-b border-orange-100">

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Nama File
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Tipe
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Ukuran
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Waktu Upload
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse($all_media as $item)

                                    <tr class="hover:bg-orange-50/50 transition-colors duration-150">

                                        <!-- FILE -->
                                        <td class="px-6 py-4 font-medium text-gray-800">
                                            {{ $item->original_name }}
                                        </td>

                                        <!-- TYPE -->
                                        <td class="px-6 py-4">

                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-bold uppercase tracking-wide">
                                                {{ $item->mime_type }}
                                            </span>

                                        </td>

                                        <!-- SIZE -->
                                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                            {{ round($item->file_size / 1024 / 1024, 2) }} MB
                                        </td>

                                        <!-- DATE -->
                                        <td class="px-6 py-4 text-sm text-gray-600">

                                            <span class="font-medium text-gray-800">
                                                {{ $item->created_at->diffForHumans() }}
                                            </span>

                                            <span class="text-xs block text-gray-400 mt-0.5">
                                                {{ $item->created_at->format('d M Y, H:i') }}
                                            </span>

                                        </td>

                                        <!-- STATUS -->
                                        <td class="px-6 py-4">

                                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                            {{ $item->visibility == 'public'
                                                ? 'bg-yellow-100 text-yellow-800 border border-yellow-200'
                                                : 'bg-gray-100 text-gray-600 border border-gray-200' }}">

                                                {{ $item->visibility == 'public'
                                                    ? '🌐 Public'
                                                    : '🔒 Private' }}

                                            </span>

                                        </td>

                                        <!-- ACTION -->
                                        <td class="px-6 py-4 flex items-center space-x-4">

                                            <!-- VIEW -->
                                            <a
                                                href="{{ route('media.show', $item->id) }}"
                                                target="_blank"
                                                class="text-orange-600 hover:text-orange-800 font-semibold text-sm transition-colors"
                                            >
                                                Lihat
                                            </a>

                                            <!-- COPY -->
                                            @if($item->visibility == 'public')

                                                <button
                                                    type="button"
                                                    onclick="copyLink('{{ route('media.show', $item->id) }}')"
                                                    class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm transition-colors focus:outline-none"
                                                >
                                                    Salin Link
                                                </button>

                                            @endif

                                            <!-- DELETE -->
                                            <form
                                                action="{{ route('media.destroy', $item->id) }}"
                                                method="POST"
                                                id="delete-form-{{ $item->id }}"
                                                class="inline m-0 p-0"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="button"
                                                    onclick="hapusFile('{{ $item->id }}')"
                                                    class="text-red-500 hover:text-red-700 font-semibold text-sm transition-colors focus:outline-none"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="px-6 py-12 text-center">

                                            <div class="flex flex-col items-center justify-center text-gray-400">

                                                <svg class="w-16 h-16 mb-4 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>

                                                <p class="text-lg font-medium text-gray-500">
                                                    Belum ada file nih!
                                                </p>

                                                <p class="text-sm">
                                                    Yuk upload file pertamamu di atas.
                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- COPY LINK -->
    <script>
        function copyLink(url) {

            const el = document.createElement('textarea');

            el.value = url;

            document.body.appendChild(el);

            el.select();

            document.execCommand('copy');

            document.body.removeChild(el);

            Swal.fire({
                title: 'Berhasil!',
                text: 'Link akses berhasil disalin ke clipboard 📋',
                icon: 'success',
                confirmButtonColor: '#f59e0b',
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>

    <!-- DELETE -->
    <script>
        function hapusFile(id) {

            Swal.fire({
                title: 'Yakin mau dihapus?',
                text: "File ini bakal hilang selamanya lho!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#f59e0b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#fff',
                borderRadius: '1rem'
            }).then((result) => {

                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }

            });

        }
    </script>

</body>
</html>