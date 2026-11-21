<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        try {
            $tasks = $this->taskService->listTasks(
                status: $status !== '' ? $status : null,
                search: $search,
                perPage: $perPage > 0 ? $perPage : 10
            );
        } catch (InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return view('tasks.index', [
                'tasks' => collect(),
                'filters' => [
                    'status' => $status,
                    'search' => $search,
                ],
                'errorMessage' => $e->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json($tasks);
        }

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
            'errorMessage' => null,
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse|JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task berhasil dibuat.',
                'data' => $task,
            ], 201);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan.');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse|JsonResponse
    {
        $updated = $this->taskService->updateTask($task, $request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task berhasil diperbarui.',
                'data' => $updated,
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil diperbarui.');
    }

    public function complete(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $updated = $this->taskService->markAsCompleted($task);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task ditandai selesai.',
                'data' => $updated,
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task ditandai selesai.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        $this->taskService->deleteTask($task);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil dihapus.');
    }
}