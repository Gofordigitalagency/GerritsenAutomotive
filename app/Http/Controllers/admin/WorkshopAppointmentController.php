<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\WorkshopAppointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkshopAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkshopAppointment::query();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.workshop.index', compact('appointments'));
    }

    public function show(WorkshopAppointment $workshop)
    {
        return view('admin.workshop.show', ['appointment' => $workshop]);
    }

    public function updateStatus(Request $request, WorkshopAppointment $workshop)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'done'])],
        ]);

        $workshop->update($data);

        return back()->with('ok', 'Status bijgewerkt.');
    }

    public function destroy(WorkshopAppointment $workshop)
    {
        $workshop->delete();

        return redirect()->route('admin.workshop.index')->with('ok', 'Afspraak verwijderd.');
    }
}
