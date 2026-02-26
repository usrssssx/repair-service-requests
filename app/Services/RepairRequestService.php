<?php

namespace App\Services;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\RequestEvent;
use App\Models\User;
use RuntimeException;

class RepairRequestService
{
    public function create(array $data, ?User $actor): RepairRequest
    {
        $repairRequest = RepairRequest::create([
            ...$data,
            'status' => RequestStatus::New,
            'assigned_to' => null,
        ]);

        $this->logEvent($repairRequest->id, $actor, 'create', null, RequestStatus::New->value, null);

        return $repairRequest;
    }

    public function assign(RepairRequest $repairRequest, User $actor, User $master): void
    {
        if ($repairRequest->status !== RequestStatus::New) {
            throw new RuntimeException('Нельзя назначить мастера для этой заявки.');
        }

        $fromStatus = $this->statusValue($repairRequest->status);

        $repairRequest->update([
            'status' => RequestStatus::Assigned,
            'assigned_to' => $master->id,
        ]);

        $this->logEvent($repairRequest->id, $actor, 'assign', $fromStatus, RequestStatus::Assigned->value, [
            'assigned_to' => $master->id,
        ]);
    }

    public function cancel(RepairRequest $repairRequest, User $actor): void
    {
        if (in_array($repairRequest->status, [RequestStatus::Done, RequestStatus::Canceled], true)) {
            throw new RuntimeException('Нельзя отменить эту заявку.');
        }

        $fromStatus = $this->statusValue($repairRequest->status);

        $repairRequest->update([
            'status' => RequestStatus::Canceled,
            'assigned_to' => null,
        ]);

        $this->logEvent($repairRequest->id, $actor, 'cancel', $fromStatus, RequestStatus::Canceled->value, null);
    }

    public function take(int $requestId, User $actor): bool
    {
        $updated = RepairRequest::where('id', $requestId)
            ->where('status', RequestStatus::Assigned)
            ->where('assigned_to', $actor->id)
            ->update([
                'status' => RequestStatus::InProgress,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return false;
        }

        $this->logEvent($requestId, $actor, 'take', RequestStatus::Assigned->value, RequestStatus::InProgress->value, null);

        return true;
    }

    public function done(RepairRequest $repairRequest, User $actor): void
    {
        if ($repairRequest->assigned_to !== $actor->id || $repairRequest->status !== RequestStatus::InProgress) {
            throw new RuntimeException('Нельзя завершить эту заявку.');
        }

        $fromStatus = $this->statusValue($repairRequest->status);

        $repairRequest->update([
            'status' => RequestStatus::Done,
        ]);

        $this->logEvent($repairRequest->id, $actor, 'done', $fromStatus, RequestStatus::Done->value, null);
    }

    private function logEvent(int $requestId, ?User $actor, string $action, ?string $fromStatus, ?string $toStatus, ?array $meta): void
    {
        RequestEvent::create([
            'repair_request_id' => $requestId,
            'actor_id' => $actor?->id,
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'meta' => $meta,
            'created_at' => now(),
        ]);
    }

    private function statusValue(RequestStatus|string|null $status): ?string
    {
        if ($status instanceof RequestStatus) {
            return $status->value;
        }

        return $status;
    }
}
