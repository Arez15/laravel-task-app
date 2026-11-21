<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaskService
{
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'completed'];

    public function listTasks(?string $status = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Task::query()->latest();

        if ($status !== null && $status !== '') {
            $this->assertValidStatus($status);
            $query->where('status', $status);
        }

        if ($search !== null) {
            $keyword = trim($search);
            if ($keyword !== '') {
                $escaped = addcslashes($keyword, '%_\\');
                $like = "%{$escaped}%";

                $query->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('description', 'like', $like);
                });
            }
        }

        return $query->paginate($perPage);
    }

    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $status = $data['status'] ?? 'pending';
            $this->assertValidStatus($status);

            $dueDate = !empty($data['due_date']) ? $data['due_date'] : null;

            return Task::create([
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'status' => $status,
                'due_date' => $dueDate,
                'completed_at' => $status === 'completed' ? now() : null,
            ]);
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $nextStatus = $data['status'] ?? $task->status;
            $this->assertValidStatus($nextStatus);

            $completedAt = match ($nextStatus) {
                'completed' => $task->completed_at ?? now(),
                default => null,
            };

            $dueDate = array_key_exists('due_date', $data)
                ? (!empty($data['due_date']) ? $data['due_date'] : null)
                : $task->due_date;

            $task->update([
                'title' => array_key_exists('title', $data) ? trim($data['title']) : $task->title,
                'description' => array_key_exists('description', $data) ? $data['description'] : $task->description,
                'status' => $nextStatus,
                'due_date' => $dueDate,
                'completed_at' => $completedAt,
            ]);

            return $task->fresh();
        });
    }

    public function markAsCompleted(Task $task): Task
    {
        return $this->updateTask($task, ['status' => 'completed']);
    }

    public function deleteTask(Task $task): bool
    {
        return DB::transaction(fn () => (bool) $task->delete());
    }

    private function assertValidStatus(string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new InvalidArgumentException("Status task '{$status}' tidak valid.");
        }
    }
}