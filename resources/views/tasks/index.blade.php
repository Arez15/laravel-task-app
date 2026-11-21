<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management - Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 8% 12%, rgba(56, 189, 248, 0.12), transparent 34%),
                radial-gradient(circle at 92% 88%, rgba(37, 99, 235, 0.12), transparent 38%),
                #f1f5f9;
            color: #0f172a;
        }

        .top-navbar {
            background: linear-gradient(135deg, var(--brand-dark) 0%, #1e293b 65%, #1d4ed8 100%);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }

        .brand-badge {
            width: 38px;
            height: 38px;
            border-radius: 0.75rem;
            background: rgba(56, 189, 248, 0.18);
            color: #38bdf8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .stat-card {
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .main-card {
            border: 1px solid rgba(15, 23, 42, 0.07);
            border-radius: 1.1rem;
            background: #ffffff;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .form-control,
        .form-select {
            border-radius: 0.65rem;
            border-color: #cbd5e1;
            padding: 0.58rem 0.8rem;
        }

        .form-control-sm,
        .form-select-sm {
            padding: 0.42rem 0.68rem;
            border-radius: 0.55rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.14);
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 0.65rem;
            font-weight: 600;
            padding: 0.62rem 1rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.25);
        }

        .status-pill {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .table > :not(caption) > * > * {
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }
    </style>
</head>
<body>

{{-- Top Navigation --}}
<nav class="navbar top-navbar navbar-dark py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('tasks.index') }}" class="navbar-brand d-flex align-items-center gap-2 m-0 text-decoration-none">
            <span class="brand-badge">
                <i class="bi bi-check2-square"></i>
            </span>
            <div>
                <div class="fw-bold lh-1">Task Management</div>
                <div class="text-white-50" style="font-size: 0.75rem;">Laravel Service Layer &bull; MySQL</div>
            </div>
        </a>

        <div class="d-flex align-items-center gap-3">
            @auth
                <span class="text-white-50 small d-none d-sm-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? auth()->user()->email }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container pb-5">
    {{-- Alert Flash & Validasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (!empty($errorMessage))
        <div class="alert alert-danger border-0 shadow-sm rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errorMessage }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3">
            <div class="fw-semibold small mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Periksa kembali input Anda:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan Statistik Task (berdasarkan data halaman aktif) --}}
    @php
        $itemsCollection = is_object($tasks) && method_exists($tasks, 'getCollection')
            ? $tasks->getCollection()
            : collect($tasks);
        $totalCount = is_object($tasks) && method_exists($tasks, 'total')
            ? $tasks->total()
            : $itemsCollection->count();
        $pendingCount = $itemsCollection->where('status', 'pending')->count();
        $progressCount = $itemsCollection->where('status', 'in_progress')->count();
        $completedCount = $itemsCollection->where('status', 'completed')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 d-flex align-items-center gap-3">
                <span class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-list-task"></i>
                </span>
                <div>
                    <div class="text-muted small">Total Task</div>
                    <div class="h5 fw-bold mb-0">{{ $totalCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 d-flex align-items-center gap-3">
                <span class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-hourglass-split"></i>
                </span>
                <div>
                    <div class="text-muted small">Pending</div>
                    <div class="h5 fw-bold mb-0">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 d-flex align-items-center gap-3">
                <span class="stat-icon bg-warning bg-opacity-15 text-warning-emphasis">
                    <i class="bi bi-arrow-repeat"></i>
                </span>
                <div>
                    <div class="text-muted small">In Progress</div>
                    <div class="h5 fw-bold mb-0">{{ $progressCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 d-flex align-items-center gap-3">
                <span class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-patch-check"></i>
                </span>
                <div>
                    <div class="text-muted small">Completed</div>
                    <div class="h5 fw-bold mb-0">{{ $completedCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Form Tambah Task --}}
        <div class="col-lg-4">
            <div class="main-card">
                <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill text-primary"></i>
                    <span class="fw-semibold">Tambah Task Baru</span>
                </div>
                <div class="p-4">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label small fw-semibold">Judul Task <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="form-control"
                                   maxlength="150"
                                   required
                                   placeholder="Contoh: Audit konfigurasi MikroTik">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label small fw-semibold">Deskripsi</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Catatan atau rincian pekerjaan (opsional)">{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-sm-6">
                                <label for="due_date" class="form-label small fw-semibold">Tenggat (Due Date)</label>
                                <input type="date"
                                       id="due_date"
                                       name="due_date"
                                       value="{{ old('due_date') }}"
                                       class="form-control">
                            </div>

                            <div class="col-sm-6">
                                <label for="status" class="form-label small fw-semibold">Status Awal</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status') === 'in_progress')>In Progress</option>
                                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-primary-gradient w-100 text-white">
                            <i class="bi bi-plus-lg me-1"></i>Simpan Task
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Filter, Pencarian & Tabel Task --}}
        <div class="col-lg-8">
            <div class="main-card">
                <div class="p-3 px-4 border-bottom bg-light bg-opacity-50">
                    <form action="{{ route('tasks.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-sm-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.55rem 0 0 0.55rem; border-color: #cbd5e1;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text"
                                       name="search"
                                       value="{{ $filters['search'] ?? '' }}"
                                       class="form-control form-control-sm border-start-0"
                                       placeholder="Cari judul atau deskripsi...">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                                <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>In Progress</option>
                                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                            </select>
                        </div>

                        <div class="col-sm-3 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-dark w-100 rounded-2 fw-medium">
                                Terapkan
                            </button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary rounded-2" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Task</th>
                            <th>Status</th>
                            <th>Tenggat</th>
                            <th>Selesai</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($tasks as $task)
                            @php
                                $badgeClass = match ($task->status) {
                                    'completed' => 'bg-success bg-opacity-15 text-success',
                                    'in_progress' => 'bg-warning bg-opacity-20 text-warning-emphasis',
                                    default => 'bg-secondary bg-opacity-15 text-secondary',
                                };
                                $statusLabel = str_replace('_', ' ', $task->status);
                                $isOverdue = $task->due_date
                                    && $task->status !== 'completed'
                                    && $task->due_date->isPast()
                                    && !$task->due_date->isToday();
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $task->title }}</div>
                                    @if ($task->description)
                                        <div class="small text-muted mt-0.5">{{ $task->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge status-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="small text-nowrap">
                                    @if ($task->due_date)
                                        <span class="{{ $isOverdue ? 'text-danger fw-semibold' : 'text-secondary' }}">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('d M Y') }}
                                        </span>
                                        @if ($isOverdue)
                                            <span class="badge bg-danger bg-opacity-15 text-danger ms-1">Lewat</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small text-muted text-nowrap">
                                    {{ $task->completed_at ? $task->completed_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="pe-4 text-end text-nowrap">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-2 btn-edit-task"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTaskModal"
                                                data-update-url="{{ route('tasks.update', $task) }}"
                                                data-title="{{ $task->title }}"
                                                data-description="{{ $task->description ?? '' }}"
                                                data-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                                data-status="{{ $task->status }}"
                                                title="Edit Task">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        @if ($task->status !== 'completed')
                                            <form action="{{ route('tasks.complete', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-2" title="Tandai Selesai">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('tasks.destroy', $task) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Hapus task ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus Task">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                    Belum ada data task yang sesuai dengan filter saat ini.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($tasks, 'hasPages') && $tasks->hasPages())
                    <div class="p-3 px-4 border-top bg-light bg-opacity-50">
                        {{ $tasks->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Task --}}
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-2 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="editTaskModalLabel">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body px-4 py-2">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label small fw-semibold">Judul Task <span class="text-danger">*</span></label>
                        <input type="text" id="edit_title" name="title" class="form-control" maxlength="150" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label small fw-semibold">Deskripsi</label>
                        <textarea id="edit_description" name="description" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <label for="edit_due_date" class="form-label small fw-semibold">Tenggat (Due Date)</label>
                            <input type="date" id="edit_due_date" name="due_date" class="form-control">
                        </div>

                        <div class="col-sm-6">
                            <label for="edit_status" class="form-label small fw-semibold">Status</label>
                            <select id="edit_status" name="status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-2 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-primary-gradient text-white px-4">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModalEl = document.getElementById('editTaskModal');
    const editForm = document.getElementById('editTaskForm');
    const titleInput = document.getElementById('edit_title');
    const descriptionInput = document.getElementById('edit_description');
    const dueDateInput = document.getElementById('edit_due_date');
    const statusInput = document.getElementById('edit_status');

    editModalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        editForm.action = button.getAttribute('data-update-url') || '';
        titleInput.value = button.getAttribute('data-title') || '';
        descriptionInput.value = button.getAttribute('data-description') || '';
        dueDateInput.value = button.getAttribute('data-due-date') || '';
        statusInput.value = button.getAttribute('data-status') || 'pending';
    });
});
</script>
</body>
</html>