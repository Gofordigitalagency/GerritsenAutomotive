<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    private function typeFromRoute(Request $request): string
    {
        $name = $request->route()?->getName() ?? '';
        return str_contains($name, 'aanhanger') ? 'aanhanger' : 'stofzuiger';
    }

    public function index(Request $request)
    {
        $type = $this->typeFromRoute($request);

        $reservations = Reservation::ofType($type)
            ->orderBy('start_at','desc')
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations','type'));
    }

    public function create(Request $request)
    {
        $type = $this->typeFromRoute($request);
        $reservation = new Reservation(['status' => 'confirmed']);
        return view('admin.reservations.create', compact('type','reservation'));
    }

    public function store(Request $request)
{
    $type = $this->typeFromRoute($request);

    $data = $request->validate([
        'reserved_by' => ['nullable','string','max:120'],
        'phone'       => ['nullable','string','max:30'],
        'email'       => ['nullable','email','max:160'],
        'start_at'    => ['required','date'],
        'end_at'      => ['required','date','after:start_at'],
        'status'      => ['required', Rule::in(['confirmed','pending','cancelled'])],
        'notes'       => ['nullable','string','max:1000'],
    ]);

    // Alleen als info: is er overlap? (niet blokkeren)
    $heeftOverlap = \App\Models\Reservation::overlaps($type, $data['start_at'], $data['end_at']);

    \App\Models\Reservation::create($data + [
        'resource_type' => $type,
        'created_by'    => optional($request->user())->id,
    ]);

    return redirect()
        ->route("admin.$type.index")
        ->with('ok', 'Reservering aangemaakt.'.($heeftOverlap ? ' (Let op: overlapt met een andere reservering)' : ''));
}

    public function edit(Request $request, Reservation $reservation)
    {
        $type = $this->typeFromRoute($request);
        abort_if($reservation->resource_type !== $type, 404);
        return view('admin.reservations.edit', compact('reservation','type'));
    }

  public function update(Request $request, \App\Models\Reservation $reservation)
{
    $type = $this->typeFromRoute($request);
    abort_if($reservation->resource_type !== $type, 404);

    $data = $request->validate([
        'reserved_by' => ['nullable','string','max:120'],
        'phone'       => ['nullable','string','max:30'],
        'email'       => ['nullable','email','max:160'],
        'start_at'    => ['required','date'],
        'end_at'      => ['required','date','after:start_at'],
        'status'      => ['required', Rule::in(['confirmed','pending','cancelled'])],
        'notes'       => ['nullable','string','max:1000'],
    ]);

    // Alleen als info: is er overlap? (niet blokkeren)
    $heeftOverlap = \App\Models\Reservation::overlaps($type, $data['start_at'], $data['end_at'], $reservation->id);

    $reservation->update($data);

    return redirect()
        ->route("admin.$type.index")
        ->with('ok','Reservering bijgewerkt.'.($heeftOverlap ? ' (Let op: overlapt met een andere reservering)' : ''));
}

    public function destroy(Request $request, Reservation $reservation)
    {
        $type = $this->typeFromRoute($request);
        abort_if($reservation->resource_type !== $type, 404);

        $reservation->delete();

        return redirect()->route("admin.$type.index")->with('ok','Reservering verwijderd.');
    }

    /** Agenda (FullCalendar) */
        public function calendar()
        {
            $tz = 'Europe/Amsterdam';

            $events = Reservation::where('status','!=','cancelled')
                ->orderBy('start_at')
                ->get()
                ->map(function ($r) use ($tz) {
                    return [
                        'id'    => $r->id,
                        'title' => ucfirst($r->resource_type) . ($r->reserved_by ? ' – '.$r->reserved_by : ''),
                        'start' => $r->start_at->copy()->timezone($tz)->toIso8601String(),
                        'end'   => $r->end_at->copy()->timezone($tz)->toIso8601String(),
                        'color' => $r->resource_type === 'aanhanger' ? '#0ea5e9' : '#f97316',
                        'url'   => route('admin.'.$r->resource_type.'.edit', $r),
                    ];
                })->values();

            return view('admin.reservations.calendar', compact('events'));
        }

}

