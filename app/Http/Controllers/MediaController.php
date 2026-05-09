<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Wajib ditambahin buat ngecek file fisik

class MediaController extends Controller
{
    // 1. Menampilkan Dashboard dan Daftar File (Read)
    public function index()
    {
        // Ambil semua file milik user yang sedang login, urutkan dari yang terbaru
        $all_media = Media::where('user_id', Auth::id())->latest()->get();
        
        return view('dashboard', compact('all_media'));
    }

    // 2. Menyimpan File Baru (Create) - Ini kodinganmu yang sudah aman
    public function store(Request $request)
    {
        // Aturan: format tetap ada pdf, tanpa mov/webm, ukuran max 100MB
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,pdf|max:102400',
            'description' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private',
        ]);


        $file = $request->file('media');
        $path = $file->store('private_media');

        Media::create([
            'user_id' => Auth::id(),
            'file_name' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'visibility' => $request->visibility,
        ]);

        return back()->with('success', 'File berhasil di-upload ');
    }

    // 3. Menampilkan File Fisik (Show/Stream) - "The Security Guard"
    public function show(Media $media)
    {
        // Otorisasi: Tolak jika file ini private dan yang akses BUKAN pemiliknya
        if ($media->visibility !== 'public' && $media->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat file ini.');
        }

        $path = $media->file_name;

        // Cek apakah file fisiknya beneran ada di server
        if (!Storage::exists($path)) {
            abort(404, 'File ada di server ini.');
        }

        // Tampilkan file ke browser (Streaming file dari folder rahasia)
        return response()->file(storage_path('app/' . $path));
    }
    // 4. Menghapus File (Delete)
    public function destroy(Media $media)
    {
        // SECURITY: Pastikan cuma pemilik file yang bisa hapus
        if ($media->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak bisa menghapus file orang lain.');
        }

        // Hapus file fisiknya dari folder server (Biar storage gak penuh)
        if (Storage::exists($media->file_name)) {
            Storage::delete($media->file_name);
        }

        // Hapus datanya dari database
        $media->delete();

        return back()->with('success', 'File berhasil dihapus permanen!');
    } 

}
