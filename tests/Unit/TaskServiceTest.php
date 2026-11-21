public function test_create_and_update_task_handles_due_date(): void
    {
        $task = $this->service->createTask([
            'title' => 'Audit Jaringan Cabang',
            'due_date' => '2026-12-10',
        ]);

        $this->assertNotNull($task->due_date);
        $this->assertSame('2026-12-10', $task->due_date->format('Y-m-d'));

        $updated = $this->service->updateTask($task, [
            'due_date' => '2026-12-20',
        ]);

        $this->assertNotNull($updated->due_date);
        $this->assertSame('2026-12-20', $updated->due_date->format('Y-m-d'));

        $cleared = $this->service->updateTask($updated, [
            'due_date' => null,
        ]);

        $this->assertNull($cleared->due_date);
    }