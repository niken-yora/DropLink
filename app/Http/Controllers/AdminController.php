<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $users = User::latest()->get();

        $media = Media::with('user')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('users', 'media'));
    }

    /**
     * Hapus user beserta semua media miliknya
     */
    public function destroyUser(User $user)
    {
        // Cegah admin hapus akun sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Hapus semua media milik user
        foreach ($user->media as $media) {

            // Hapus file fisik
            if (
                $media->file_name &&
                Storage::disk('public')->exists($media->file_name)
            ) {
                Storage::disk('public')->delete($media->file_name);
            }

            // Hapus database media
            $media->delete();
        }

        // Hapus user
        $user->delete();

        return back()->with(
            'success',
            'User dan semua medianya berhasil dihapus.'
        );
    }

    /**
     * Hapus media/file
     */
    public function destroyMedia(Media $media)
    {
        // Hapus file fisik dari storage
        if (
            $media->file_name &&
            Storage::disk('public')->exists($media->file_name)
        ) {
            Storage::disk('public')->delete($media->file_name);
        }

        // Hapus data media
        $media->delete();

        return back()->with(
            'success',
            'Media berhasil dihapus.'
        );
    }
}