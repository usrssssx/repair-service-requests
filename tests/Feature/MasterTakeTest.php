<?php

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterTakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_take_success(): void
    {
        $master = User::create([
            'name' => 'Master One',
            'email' => 'master.one@example.com',
            'password' => 'password',
            'role' => 'master',
        ]);

        $request = RepairRequest::create([
            'client_name' => 'Client',
            'phone' => '+7 900 000-00-00',
            'address' => 'Test address',
            'problem_text' => 'Test problem',
            'status' => RequestStatus::Assigned,
            'assigned_to' => $master->id,
        ]);

        $token = 'test-token';
        $response = $this->withSession([
            'user_id' => $master->id,
            '_token' => $token,
        ])->patch("/master/requests/{$request->id}/take", [
            '_token' => $token,
        ]);

        $this->assertTrue(in_array($response->status(), [200, 302], true));

        $request->refresh();
        $this->assertSame(RequestStatus::InProgress, $request->status);
    }

    public function test_master_take_conflict(): void
    {
        $master = User::create([
            'name' => 'Master Two',
            'email' => 'master.two@example.com',
            'password' => 'password',
            'role' => 'master',
        ]);

        $request = RepairRequest::create([
            'client_name' => 'Client',
            'phone' => '+7 900 000-00-01',
            'address' => 'Test address',
            'problem_text' => 'Test problem',
            'status' => RequestStatus::Assigned,
            'assigned_to' => $master->id,
        ]);

        $token = 'test-token';
        $this->withSession([
            'user_id' => $master->id,
            '_token' => $token,
        ])->patch("/master/requests/{$request->id}/take", [
            '_token' => $token,
        ]);

        $response = $this->withSession([
            'user_id' => $master->id,
            '_token' => $token,
        ])->patch("/master/requests/{$request->id}/take", [
            '_token' => $token,
        ]);

        $response->assertStatus(409);
    }
}
