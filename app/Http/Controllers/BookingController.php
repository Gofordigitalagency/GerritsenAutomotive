<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Mail\ReservationCreatedMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    // Openingstijden & slot-instellingen
    private const OPEN_TIME    = '08:00';
    private const CLOSE_TIME   = '18:00';
    private const SLOT_MINUTES = 30;                 // raster
    private const DURATIONS    = [                   // duur per resource
        'aanhanger'  => 60,
        'stofzuiger' => 30,
    ];
    private const RESOURCES = ['aanhanger','stofzuiger'];

    private function normalizeType(?string $t): string
    {
        return in_array($t, self::RESOURCES, true) ? $t : 'aanhanger';
    }

    /** Publieke reserveringspagina */
public function show(Request $request)
{
    $type = $this->normalizeType($request->query('type'));
    return view('booking', [
        'type'        => $type,
        'openTime'    => self::OPEN_TIME,
        'closeTime'   => self::CLOSE_TIME,
        'slotMinutes' => self::SLOT_MINUTES,
    ]);
}

    /** AJAX: vrije starttijden voor een datum & resource */
    public function slots(Request $request)
    {
        $type = $this->normalizeType($request->query('type'));
        $date = Carbon::parse($request->query('date'))->timezone('Europe/Amsterdam');

        $open        = Carbon::parse($date->format('Y-m-d').' '.self::OPEN_TIME,  'Europe/Amsterdam');
        $close       = Carbon::parse($date->format('Y-m-d').' '.self::CLOSE_TIME, 'Europe/Amsterdam');
        $durationMin = self::DURATIONS[$type];

        // bestaande reserveringen op die dag
        $dayReservations = Reservation::ofType($type)
            ->where('status','!=','cancelled')
            ->whereDate('start_at', $date->toDateString())
            ->get(['start_at','end_at']);

        $slots = [];
        for ($t = $open->copy(); $t->lt($close); $t->addMinutes(self::SLOT_MINUTES)) {
            $start = $t->copy();
            $end   = $t->copy()->addMinutes($durationMin);

            if ($end->gt($close)) continue;

            $overlap = $dayReservations->first(function($r) use ($start,$end) {
                return $r->end_at->gt($start) && $r->start_at->lt($end);
            });

            if (!$overlap && $start->isFuture()) {
                $slots[] = [
                    'start' => $start->format('Y-m-d H:i'),
                    'label' => $start->format('H:i'),
                ];
            }
        }
        return response()->json($slots);
    }

    /** Reservering opslaan (publiek) */
public function store(Request $request)
{
    $type = $this->normalizeType($request->input('type'));

    $data = $request->validate([
        'type'     => ['required', Rule::in(self::RESOURCES)],
        'name'     => ['required','string','max:120'],
        'phone'    => ['required','string','max:30'],
        'email'    => ['required','email','max:160'],
        'start_at' => ['required','date'],
        'end_at'   => ['required','date','after:start_at'],
    ]);

    $start = Carbon::parse($data['start_at'], 'Europe/Amsterdam');
    $end   = Carbon::parse($data['end_at'], 'Europe/Amsterdam');

    // Blokkeer overlap (server-side)
    if (Reservation::overlaps($type, $start, $end)) {
        return back()->withInput()
            ->withErrors(['start_at' => 'Dit tijdslot overlapt met een bestaande reservering. Kies een andere range.']);
    }

    $reservation = Reservation::create([
        'resource_type' => $type,
        'start_at'      => $start,
        'end_at'        => $end,
        'reserved_by'   => $data['name'],
        'phone'         => $data['phone'],
        'email'         => $data['email'],
        'status'        => 'confirmed',
        'notes'         => null,
        'created_by'    => null,
    ]);

    // E-mails volgen later (Mailgun), code kan al blijven staan in try/catch
    try {
        Mail::to($reservation->email)->send(new ReservationCreatedMail($reservation, true));
        Mail::to(config('booking.admin_email'))->send(new ReservationCreatedMail($reservation, false));
    } catch (\Throwable $e) {}

    return redirect()
        ->route('booking.show', ['type' => $type])
        ->with('ok', 'Bedankt! Je reservering is bevestigd en staat in de agenda.');
}
}
