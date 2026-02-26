<?php

namespace App\Http\Controllers;

use App\Services\RepairRequestService;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    public function __construct(private readonly RepairRequestService $service)
    {
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'problem_text' => ['required', 'string'],
        ]);

        $user = $request->attributes->get('currentUser');
        $this->service->create($data, $user);

        return redirect('/requests/create')->with('success', 'Заявка создана.');
    }
}
