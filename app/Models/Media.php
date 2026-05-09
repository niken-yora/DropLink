<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Panggil fitur UUID
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasUuids; // Aktifkan auto-generate UUID

    // Mass Assignment Protection (Hanya kolom ini yang boleh diisi)
    protected $fillable = [
        'user_id', 
        'file_name', 
        'original_name', 
        'mime_type', 
        'file_size', 
        'description', 
        'visibility'
    ];

    // Relasi ke User (Satu file punya satu pemilik)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
