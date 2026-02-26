<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\User;
use App\Services\RepairRequestService;
use Illuminate\Http\Request;
use RuntimeException;

class DispatcherController extends Controller
{
    public function __construct(private readonly RepairRequestService $service)
    {
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = RepairRequest::with(['assignee', 'events.actor'])->orderByDesc('created_at');
        if ($status) {
            $query->where('status', $status);
        }

        $requests = $query->get();
        $masters = User::where('role', 'master')->orderBy('name')->get();

        return view('dispatcher.index', [
            'requests' => $requests,
            'masters' => $masters,
            'status' => $status,
            'statuses' => RequestStatus::values(),
        ]);
    }

    public function assign(Request $request, int $id)
    {
        $repairRequest = RepairRequest::findOrFail($id);

        $data = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $master = User::where('id', $data['assigned_to'])->where('role', 'master')->first();
        if (!$master) {
            return back()->withErrors(['assigned_to' => 'Выберите мастера.']);
        }

        if ($repairRequest->status !== RequestStatus::New) {
            return back()->withErrors(['status' => 'Нельзя назначить мастера для этой заявки.']);
        }

        $user = $request->attributes->get('currentUser');
        try {
            $this->service->assign($repairRequest, $user, $master);
        } catch (RuntimeException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Мастер назначен.');
    }

    public function cancel(int $id)
    {
        $repairRequest = RepairRequest::findOrFail($id);

        if (in_array($repairRequest->status, [RequestStatus::Done, RequestStatus::Canceled], true)) {
            return back()->withErrors(['status' => 'Нельзя отменить эту заявку.']);
        }

        $user = request()->attributes->get('currentUser');
        try {
            $this->service->cancel($repairRequest, $user);
        } catch (RuntimeException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Заявка отменена.');
    }
}
