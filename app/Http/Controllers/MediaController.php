<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * 1. FUNGSI INDEX - Si Pustakawan
     * Nyiapin data file untuk ditampilin di tabel dashboard
     */
    public function index()
    {
        // Ambil semua file milik user yang lagi login, urutkan dari yang terbaru
        $all_media = Media::where('user_id', Auth::id())->latest()->get();
        
        return view('dashboard', compact('all_media'));
    }

    /**
     * 2. FUNGSI STORE - Si Satpam Pintu Masuk
     * Menerima, memvalidasi, dan menyimpan file yang di-upload
     */
    public function store(Request $request)
    {
        // Validasi: Maksimal 100MB (102400 KB), tambah izin format PDF
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,pdf|max:102400',
            'description' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private',
        ]);

        $file = $request->file('media');
        
        // Simpan data asli file
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        
        // Enkripsi nama file jadi acak dan simpan di folder 'storage/app/private_media'
        $fileName = $file->hashName();
        $file->storeAs('private_media', $fileName, 'local');

        // Catat detail file ke database
        Media::create([
            'user_id' => Auth::id(),
            'original_name' => $originalName,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'description' => $request->description,
            'visibility' => $request->visibility,
        ]);

        // Balik ke dashboard bawa pesan sukses
        return redirect()->route('dashboard')->with('success', 'Mantap! File berhasil di-upload.');
    }

    /**
     * 3. FUNGSI SHOW - Si Penjaga Brankas
     * Ngatur siapa yang boleh liat file via link browser
     */
    public function show(Media $media)
    {
        // Otorisasi: Tolak kalau file private tapi yang buka bukan yang punya
        if ($media->visibility === 'private' && $media->user_id !== Auth::id()) {
            abort(403, 'Maaf, file ini bersifat rahasia.');
        }

        // Cek Gudang: Tolak kalau file fisiknya nggak ada di server
        if (!Storage::disk('local')->exists('private_media/' . $media->file_name)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        // Delivery: Gunakan fungsi response bawaan Storage biar path-nya 100% akurat di Windows/Laragon
        return Storage::disk('local')->response('private_media/' . $media->file_name, $media->original_name, [
            'Content-Type' => $media->mime_type
        ]);
    }

    /**
     * 4. FUNGSI DESTROY - Si Petugas Kebersihan
     * Menghapus file fisik dari server sekaligus datanya dari database
     */
    public function destroy(Media $media)
    {
        // Verifikasi: Pastikan yang mau ngehapus adalah pemilik aslinya
        if ($media->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak! Anda tidak berhak menghapus file ini.');
        }

        // Hapus file fisiknya biar hardisk server nggak bengkak
        if (Storage::disk('local')->exists('private_media/' . $media->file_name)) {
            Storage::disk('local')->delete('private_media/' . $media->file_name);
        }

        // Hapus catatannya dari database
        $media->delete();

        // Balik ke dashboard bawa pesan sukses
        return redirect()->route('dashboard')->with('success', 'File berhasil dihapus permanen.');
    }
}