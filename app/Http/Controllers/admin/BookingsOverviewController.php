<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\WorkshopAppointment;
use Illuminate\Http\Request;

class BookingsOverviewController extends Controller
{
    private const TYPES = ['aanhanger', 'stofzuiger', 'koplampen', 'werkplaats'];

    public function index(Request $request)
    {
        $type = $request->get('type', 'aanhanger');
        if (! in_array($type, self::TYPES, true)) {
            $type = 'aanhanger';
        }

        // Counts per type (voor de tab-badges)
        $counts = [
            'aanhanger'  => Reservation::ofType('aanhanger')->count(),
            'stofzuiger' => Reservation::ofType('stofzuiger')->count(),
            'koplampen'  => Reservation::ofType('koplampen')->count(),
            'werkplaats' => class_exists(WorkshopAppointment::class) ? WorkshopAppointment::count() : 0,
        ];

        // Data voor de actieve tab
        if ($type === 'werkplaats') {
            $items = WorkshopAppointment::orderByDesc('created_at')->paginate(20)->withQueryString();
        } else {
            $items = Reservation::ofType($type)->orderByDesc('start_at')->paginate(20)->withQueryString();
        }

        return view('admin.bookings.index', compact('type', 'items', 'counts'));
    }
}
