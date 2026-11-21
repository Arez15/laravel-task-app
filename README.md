# Task Management Application (Laravel 12)

Aplikasi manajemen tugas berbasis **Laravel 12** dan **MySQL** dengan arsitektur **MVC + Service Layer (`TaskService`)**, **Form Request Validation**, pengujian otomatis **PHPUnit**, serta antarmuka **Blade + Bootstrap 5** (halaman login dan workspace manajemen task) sekaligus dukungan *response* JSON.

## Fitur Utama

- **CRUD Task Lengkap**: Membuat, melihat, memperbarui, menandai selesai (`completed_at` otomatis terkelola), dan menghapus tugas.
- **Pencarian & Filter**: Pencarian kata kunci pada `title` dan `description` serta filter berdasarkan `status` (`pending`, `in_progress`, `completed`) dengan *pagination*.
- **Tanggal Tenggat (`due_date`)**: Penetapan tanggal tenggat tugas beserta penanda tugas yang melewati tenggat (*overdue*) pada tampilan.
- **Autentikasi Web**: Halaman login (`/login`) dan logout (`/logout`) menggunakan sesi autentikasi Laravel.
- **Dukungan Hybrid (Blade & JSON)**: Tampilan antarmuka Blade/Bootstrap 5 untuk browser dan *response* JSON saat *request* meminta JSON (`Accept: application/json`).

## Arsitektur & Struktur Utama

- `app/Services/TaskService.php`: Logika bisnis utama, validasi status, sinkronisasi `completed_at`, pencarian aman, dan transaksi database (`DB::transaction`).
- `app/Http/Requests/StoreTaskRequest.php` & `UpdateTaskRequest.php`: Aturan validasi input *request*.
- `app/Http/Controllers/TaskController.php` & `AuthController.php`: Pengatur alur *request/response* ke service dan view/JSON.
- `app/Models/Task.php`: Eloquent model dengan casting `due_date` (`date`) dan `completed_at` (`datetime`).
- `database/migrations/`: Skema tabel `tasks` (`id`, `title`, `description`, `status`, `due_date`, `completed_at`, `created_at`, `updated_at`) dan `users`.
- `resources/views/auth/login.blade.php` & `resources/views/tasks/index.blade.php`: Antarmuka login dan manajemen task berbasis Bootstrap 5.
- `tests/Unit/` & `tests/Feature/`: Unit test untuk service dan feature test untuk endpoint.