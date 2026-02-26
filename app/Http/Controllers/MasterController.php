<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Services\RepairRequestService;
use Illuminate\Http\Request;
use RuntimeException;

class MasterController extends Controller
{
    public function __construct(private readonly RepairRequestService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->attributes->get('currentUser');

        $requests = RepairRequest::with(['events.actor'])
            ->where('assigned_to', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('master.index', compact('requests'));
    }

    public function take(Request $request, int $id)
    {
        $user = $request->attributes->get('currentUser');

        $success = $this->service->take($id, $user);

        if (!$success) {
            return response()->view('errors.409', [
                'message' => 'Заявка уже взята или статус изменился.',
            ], 409);
        }

        return back()->with('success', 'Заявка взята в работу.');
    }

    public function done(Request $request, int $id)
    {
        $user = $request->attributes->get('currentUser');

        $repairRequest = RepairRequest::findOrFail($id);

        try {
            $this->service->done($repairRequest, $user);
        } catch (RuntimeException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', 'Заявка завершена.');
    }
}
