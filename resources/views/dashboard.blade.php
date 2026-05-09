<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('DropLink Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Upload Media Baru</h3>
                    
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-black">Pilih File (Max 100MB)</label>
                            <input type="file" name="media" required
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('media') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Visibilitas</label>
                            <select name="visibility" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="private">Private (Hanya Aku)</option>
                                <option value="public">Public (Bisa pakai Link)</option>
                            </select>
                            @error('visibility') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Koleksi Media Kamu</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama File</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tipe</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ukuran</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Waktu Upload</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($all_media as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $item->original_name }}</td>
                                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-600">{{ $item->mime_type }}</td>
                                        <td class="px-6 py-4 text-sm">{{ round($item->file_size / 1024 / 1024, 2) }} MB</td>
                                        
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $item->created_at->diffForHumans() }} 
                                            <span class="text-xs block text-gray-400">({{ $item->created_at->format('d M Y H:i') }})</span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $item->visibility == 'public' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                                {{ ucfirst($item->visibility) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 flex items-center space-x-3">
                                            <a href="{{ route('media.show', $item->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                                Lihat File
                                            </a>
                                            <form action="{{ route('media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus file ini secara permanen?');" class="inline">
                                                @csrf
                                                @method('DELETE') 
                                                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada file yang di-upload ni
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>