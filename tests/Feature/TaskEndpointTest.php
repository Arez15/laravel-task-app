public function test_can_create_and_update_task_with_due_date_via_endpoints(): void
    {
        $createResponse = $this->postJson('/tasks', [
            'title' => 'Konfigurasi Firewall Lab',
            'due_date' => '2026-12-15',
        ]);

        $createResponse
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Konfigurasi Firewall Lab');

        $this->assertStringStartsWith('2026-12-15', (string) $createResponse->json('data.due_date'));

        $taskId = $createResponse->json('data.id');

        $updateResponse = $this->putJson("/tasks/{$taskId}", [
            'title' => 'Konfigurasi Firewall Lab (Update)',
            'due_date' => '2026-12-25',
            'status' => 'in_progress',
        ]);

        $updateResponse
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'in_progress');

        $this->assertStringStartsWith('2026-12-25', (string) $updateResponse->json('data.due_date'));
    }

    public function test_invalid_due_date_returns_422(): void
    {
        $this->postJson('/tasks', [
            'title' => 'Task Tanggal Salah',
            'due_date' => 'bukan-tanggal',
        ])->assertStatus(422);
    }