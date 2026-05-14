# Script Video Keamanan Laravel pada Project DropLink

Tanggal pembuatan: 14/05/2026  
Target selesai maksimal 1 minggu: 21/05/2026  
Durasi video disarankan: 8-12 menit

## Judul Video

Analisis Keamanan Laravel pada Project DropLink: Validasi Input, SQL Injection, Password, Session, Upload File, dan Permission

## Tujuan Video

Video ini menjelaskan bagaimana project DropLink memanfaatkan fitur keamanan Laravel untuk melindungi aplikasi dari beberapa risiko umum:

1. Input validation dan XSS
2. SQL injection
3. Keamanan password
4. Session management
5. File upload
6. Permission server dan kontrol akses

## Opening

Halo, pada video ini saya akan menjelaskan keamanan pada project Laravel bernama DropLink. Project ini adalah aplikasi upload dan sharing file, jadi ada beberapa bagian yang penting untuk diamankan, mulai dari input user, query database, password, session login, upload file, sampai permission atau hak akses user dan admin.

Saya akan membahas 6 poin utama, lalu menunjukkan bagian coding mana yang melindungi aplikasi dan fitur Laravel apa yang digunakan.

## 1. Input Validation dan XSS

**Narasi:**

Pertama adalah input validation. Input validation digunakan untuk memastikan data yang masuk ke aplikasi sesuai aturan yang kita tentukan. Di DropLink, validasi paling jelas ada pada proses upload file.

Bagian kodenya ada di `app/Http/Controllers/MediaController.php`, method `store`.

```php
$request->validate([
    'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,pdf|max:102400',
    'description' => 'nullable|string|max:255',
    'visibility' => 'required|in:public,private',
]);
```

Kode ini melindungi aplikasi dengan cara:

- `media` wajib diisi, harus berupa file, hanya boleh format tertentu, dan maksimal 100 MB.
- `description` boleh kosong, tetapi kalau diisi harus string dan maksimal 255 karakter.
- `visibility` hanya boleh bernilai `public` atau `private`, sehingga user tidak bisa mengirim nilai sembarangan.

Validasi lain juga ada di proses register, login, reset password, update password, dan update profile:

- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Controllers/Auth/PasswordController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Requests/ProfileUpdateRequest.php`

Untuk perlindungan XSS, Laravel juga membantu lewat Blade escaping. Di view, data user ditampilkan menggunakan `{{ }}`, contohnya di `resources/views/dashboard.blade.php` dan `resources/views/admin/dashboard.blade.php`.

Contoh:

```blade
{{ $item->original_name }}
{{ Auth::user()->name }}
{{ $user->email }}
```

Syntax `{{ }}` akan melakukan HTML escaping otomatis. Jadi jika ada input seperti `<script>alert(1)</script>`, Blade tidak langsung menjalankannya sebagai JavaScript, tetapi menampilkannya sebagai teks yang aman.

**Fitur Laravel yang digunakan:**

- Request validation: `$request->validate()`
- Form Request validation: `ProfileUpdateRequest` dan `LoginRequest`
- Blade escaping otomatis dengan `{{ }}`
- Error handling validasi otomatis dari Laravel

**Catatan penting untuk video:**

Validasi input membantu membatasi data berbahaya, tetapi perlindungan utama dari XSS saat output ditampilkan adalah Blade escaping. Hindari memakai `{!! !!}` untuk data dari user karena itu menampilkan HTML mentah.

## 2. SQL Injection

**Narasi:**

Poin kedua adalah SQL injection. SQL injection terjadi ketika input user dimasukkan langsung ke query SQL mentah. Di project DropLink, query database mayoritas menggunakan Eloquent ORM dan Query Builder Laravel, sehingga input tidak digabung manual ke string SQL.

Contoh pertama ada di `app/Http/Controllers/MediaController.php`, method `index`.

```php
$all_media = Media::where('user_id', Auth::id())->latest()->get();
```

Kode ini mengambil media berdasarkan user yang sedang login. Query dibuat oleh Eloquent, bukan string SQL manual.

Contoh lain ada di `app/Http/Controllers/AdminController.php`.

```php
$users = User::latest()->get();

$media = Media::with('user')
    ->latest()
    ->get();
```

Untuk insert data upload, project ini memakai `Media::create()`.

```php
Media::create([
    'user_id' => Auth::id(),
    'original_name' => $originalName,
    'file_name' => $fileName,
    'mime_type' => $mimeType,
    'file_size' => $fileSize,
    'description' => $request->description,
    'visibility' => $request->visibility,
]);
```

Laravel akan membuat query dengan parameter binding, sehingga data user tidak ditempel langsung ke SQL.

Project ini juga memakai route model binding, contohnya:

```php
public function show(Media $media)
public function destroy(Media $media)
public function destroyUser(User $user)
```

Route model binding membantu mengambil model berdasarkan parameter route dengan mekanisme Laravel, bukan dengan query manual dari input URL.

Selain itu, model `Media` dan `User` memakai `$fillable`.

```php
protected $fillable = [
    'user_id',
    'file_name',
    'original_name',
    'mime_type',
    'file_size',
    'description',
    'visibility'
];
```

`$fillable` bukan perlindungan SQL injection secara langsung, tetapi melindungi dari mass assignment, yaitu user mengisi kolom yang seharusnya tidak boleh diubah.

**Fitur Laravel yang digunakan:**

- Eloquent ORM
- Query Builder dengan parameter binding
- Route model binding
- Mass assignment protection lewat `$fillable`

**Catatan penting untuk video:**

Selama query dibuat dengan Eloquent atau Query Builder dan tidak menyusun SQL mentah dari input user, risiko SQL injection jauh lebih kecil.

## 3. Keamanan Password

**Narasi:**

Poin ketiga adalah keamanan password. Password tidak boleh disimpan dalam bentuk teks asli. Di DropLink, password di-hash menggunakan fitur Hash Laravel.

Saat register, kode ada di `app/Http/Controllers/Auth/RegisteredUserController.php`.

```php
'password' => Hash::make($request->password),
```

Saat update password, kode ada di `app/Http/Controllers/Auth/PasswordController.php`.

```php
'password' => Hash::make($validated['password']),
```

Saat reset password, kode ada di `app/Http/Controllers/Auth/NewPasswordController.php`.

```php
'password' => Hash::make($request->password),
```

Model `User` juga memiliki cast:

```php
'password' => 'hashed',
```

Artinya Laravel ikut memastikan nilai password diperlakukan sebagai hash ketika disimpan.

Selain hashing, validasi password juga memakai rule bawaan Laravel:

```php
'password' => ['required', 'confirmed', Rules\Password::defaults()],
```

Untuk update password, Laravel memastikan password lama benar:

```php
'current_password' => ['required', 'current_password'],
```

Di login, autentikasi dilakukan lewat `Auth::attempt()` pada `app/Http/Requests/Auth/LoginRequest.php`.

```php
if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
    RateLimiter::hit($this->throttleKey());
}
```

Kode ini membuat Laravel yang mencocokkan password input dengan hash di database. Project ini juga memakai `RateLimiter`, sehingga percobaan login gagal dibatasi dan brute force menjadi lebih sulit.

Model `User` juga menyembunyikan password saat model diubah menjadi array atau JSON.

```php
protected $hidden = [
    'password',
    'remember_token',
];
```

**Fitur Laravel yang digunakan:**

- `Hash::make()`
- Password hashing cast: `'password' => 'hashed'`
- `Rules\Password::defaults()`
- Rule `current_password`
- `Auth::attempt()`
- `RateLimiter`
- Hidden attributes pada model

## 4. Session Management

**Narasi:**

Poin keempat adalah session management. Session penting karena digunakan untuk menandai user yang sudah login. Jika session tidak dikelola dengan benar, aplikasi bisa terkena session fixation atau penyalahgunaan session lama.

Di DropLink, setelah login berhasil, session dibuat ulang dengan kode di `app/Http/Controllers/Auth/AuthenticatedSessionController.php`.

```php
$request->session()->regenerate();
```

Ini penting karena Laravel mengganti session ID setelah login. Dengan begitu, session lama tidak bisa dipakai untuk mengambil alih akun.

Saat logout, session dihapus dan token CSRF dibuat ulang.

```php
$request->session()->invalidate();
$request->session()->regenerateToken();
```

Kode serupa juga ada saat user menghapus akun di `app/Http/Controllers/ProfileController.php`.

Laravel juga melindungi form dengan CSRF token. Di view, form POST, DELETE, dan update memakai:

```blade
@csrf
```

Contohnya ada di:

- `resources/views/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`

Konfigurasi session ada di `config/session.php`. Beberapa konfigurasi penting:

```php
'lifetime' => (int) env('SESSION_LIFETIME', 120),
'http_only' => env('SESSION_HTTP_ONLY', true),
'same_site' => env('SESSION_SAME_SITE', 'lax'),
```

`http_only` membantu mencegah JavaScript membaca cookie session. `same_site` membantu mengurangi risiko CSRF. `lifetime` menentukan batas waktu session.

**Fitur Laravel yang digunakan:**

- Session regeneration setelah login
- Session invalidation saat logout
- Regenerate CSRF token
- CSRF protection dengan `@csrf`
- Middleware `auth` dan `verified`
- Konfigurasi cookie session: `http_only`, `same_site`, dan `lifetime`

## 5. File Upload

**Narasi:**

Poin kelima adalah file upload. Karena DropLink adalah aplikasi upload media, bagian ini sangat penting.

Di `app/Http/Controllers/MediaController.php`, method `store`, file divalidasi dulu:

```php
'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,pdf|max:102400',
```

Rule ini memastikan input benar-benar file, formatnya dibatasi, dan ukuran maksimal 100 MB.

Setelah itu nama file tidak memakai nama asli dari user. Project ini memakai:

```php
$fileName = $file->hashName();
$file->storeAs('private_media', $fileName, 'local');
```

`hashName()` membuat nama file acak, sehingga nama file asli tidak langsung dipakai sebagai nama file server. Ini mengurangi risiko path manipulation dan konflik nama file.

File disimpan pada disk `local`. Konfigurasinya ada di `config/filesystems.php`.

```php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => true,
],
```

Artinya file upload tidak langsung diletakkan di folder `public`. File disimpan di area private storage, lalu aksesnya dikontrol lewat controller.

Saat file dibuka, method `show` mengecek permission dulu:

```php
if ($media->visibility === 'private' && $media->user_id !== Auth::id()) {
    abort(403, 'Maaf, file ini bersifat rahasia.');
}
```

Lalu mengecek file fisik ada atau tidak:

```php
if (!Storage::disk('local')->exists('private_media/' . $media->file_name)) {
    abort(404, 'File tidak ditemukan di server.');
}
```

Baru setelah itu file dikirim menggunakan Laravel Storage response:

```php
return Storage::disk('local')->response('private_media/' . $media->file_name, $media->original_name, [
    'Content-Type' => $media->mime_type
]);
```

Saat user menghapus file, project ini juga memeriksa bahwa penghapus adalah pemilik file:

```php
if ($media->user_id !== Auth::id()) {
    abort(403, 'Akses ditolak! Anda tidak berhak menghapus file ini.');
}
```

**Fitur Laravel yang digunakan:**

- File validation rule: `file`, `mimes`, `max`
- Uploaded file helper: `hashName()`
- Laravel Storage facade
- Private local disk
- Controlled file response lewat controller
- Authorization manual dengan `Auth::id()`
- HTTP error helper: `abort(403)` dan `abort(404)`

**Catatan penguatan:**

Pada `AdminController`, penghapusan file admin memakai `Storage::disk('public')`, sedangkan upload user disimpan dengan disk `local` pada folder `private_media`. Untuk konsistensi dan agar file fisik benar-benar ikut terhapus, bagian admin sebaiknya diarahkan ke:

```php
Storage::disk('local')->delete('private_media/' . $media->file_name);
```

Ini bukan masalah SQL atau XSS, tetapi penting untuk kebersihan file server.

## 6. Permission Server dan Kontrol Akses

**Narasi:**

Poin keenam adalah permission server dan kontrol akses. Di Laravel, permission tidak hanya berarti permission folder di server, tetapi juga hak akses user di level aplikasi.

Di `routes/web.php`, route dashboard, upload, delete, profile, dan admin dibungkus middleware:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // dashboard, upload, delete, profile, admin
});
```

Artinya hanya user yang sudah login dan terverifikasi yang bisa mengakses fitur utama.

Untuk admin, ada middleware tambahan:

```php
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
```

Middleware `admin` didaftarkan di `bootstrap/app.php`.

```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
]);
```

Isi middleware ada di `app/Http/Middleware/AdminMiddleware.php`.

```php
if (!$request->user() || !$request->user()->isAdmin()) {
    abort(403);
}
```

Pengecekan role dilakukan di model `User`.

```php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

Selain admin middleware, project juga melakukan ownership check untuk file. User hanya boleh melihat file private miliknya sendiri dan hanya boleh menghapus file miliknya sendiri.

Di sisi server file, `config/filesystems.php` menggunakan disk `local` dengan root `storage/app/private`. Ini membantu karena file upload tidak langsung terbuka dari web root. Idealnya, pada deployment, hanya folder `public` yang menjadi document root web server, sedangkan `storage` dan source code tidak bisa diakses langsung dari browser.

**Fitur Laravel yang digunakan:**

- Route middleware `auth`
- Route middleware `verified`
- Custom middleware `admin`
- Role check pada model `User`
- Authorization manual dengan ownership check
- Private storage disk
- `abort(403)` untuk akses yang ditolak

**Checklist permission server saat deployment:**

- Document root web server diarahkan ke folder `public`, bukan root project.
- File upload disimpan di `storage/app/private`, bukan langsung di `public`.
- Folder `storage` dan `bootstrap/cache` writable oleh user web server.
- File `.env` tidak boleh bisa diakses dari browser.
- `APP_DEBUG=false` di production.
- Session cookie secure di production HTTPS: `SESSION_SECURE_COOKIE=true`.

## Ringkasan Bagian Kode yang Ditunjukkan di Video

| Poin | File | Bagian yang Ditunjukkan |
| --- | --- | --- |
| Input validation | `app/Http/Controllers/MediaController.php` | `$request->validate()` pada method `store` |
| XSS | `resources/views/dashboard.blade.php`, `resources/views/admin/dashboard.blade.php` | Output memakai `{{ }}` |
| SQL injection | `app/Http/Controllers/MediaController.php`, `app/Http/Controllers/AdminController.php` | Eloquent `where`, `latest`, `with`, `create` |
| Mass assignment | `app/Models/Media.php`, `app/Models/User.php` | Properti `$fillable` |
| Password | `RegisteredUserController`, `PasswordController`, `NewPasswordController`, `User.php` | `Hash::make()`, password rules, hidden password |
| Login protection | `app/Http/Requests/Auth/LoginRequest.php` | `Auth::attempt()` dan `RateLimiter` |
| Session | `AuthenticatedSessionController.php`, `ProfileController.php`, `config/session.php` | `regenerate`, `invalidate`, `regenerateToken`, `http_only`, `same_site` |
| CSRF | File Blade form | `@csrf` |
| File upload | `MediaController.php`, `config/filesystems.php` | `file`, `mimes`, `max`, `hashName`, `Storage::disk('local')` |
| Permission | `routes/web.php`, `AdminMiddleware.php`, `User.php` | `auth`, `verified`, `admin`, `isAdmin`, `abort(403)` |

## Closing

Jadi kesimpulannya, project DropLink sudah menggunakan banyak fitur keamanan bawaan Laravel. Untuk validasi input, Laravel memakai validation rules. Untuk XSS, Blade melakukan escaping otomatis. Untuk SQL injection, project menggunakan Eloquent ORM dan Query Builder. Untuk password, Laravel memakai hashing, password rules, dan rate limiting. Untuk session, Laravel melakukan regenerate session saat login dan invalidate saat logout. Untuk upload file, project membatasi tipe file, ukuran file, memakai nama file acak, dan menyimpan file di private storage. Terakhir, untuk permission, project memakai middleware `auth`, `verified`, custom middleware `admin`, dan pengecekan kepemilikan file.

Dengan kombinasi fitur bawaan Laravel dan pengecekan manual di controller, DropLink menjadi lebih aman dari serangan umum yang sering terjadi pada aplikasi web.

## Saran Tambahan untuk Penguatan Project

1. Samakan disk penghapusan file admin dengan lokasi upload, yaitu `local/private_media`.
2. Aktifkan `APP_DEBUG=false` saat production.
3. Gunakan HTTPS dan set `SESSION_SECURE_COOKIE=true`.
4. Pertimbangkan validasi MIME lebih ketat jika file upload akan diperluas.
5. Tambahkan Laravel Policy untuk authorization file agar pengecekan permission lebih rapi.
