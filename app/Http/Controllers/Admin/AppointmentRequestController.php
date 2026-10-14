<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAppointmentRequestStatusRequest;
use App\Models\AppointmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentRequestController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['new', 'contacted', 'closed'])],
        ]);

        $status = $validated['status'] ?? null;
        $requests = AppointmentRequest::query()
            ->when($status, fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.requests.index', compact('requests', 'status'));
    }

    public function update(UpdateAppointmentRequestStatusRequest $request, AppointmentRequest $appointmentRequest): RedirectResponse
    {
        $status = $request->validated('status');
        $appointmentRequest->update([
            'status' => $status,
            'handled_at' => $status === 'new' ? null : now(),
        ]);

        return back()->with('status', 'Request status updated.');
    }
}
