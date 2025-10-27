<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSubmitted;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    // Openingstijden & slot-instellingen
    private const OPEN_TIME    = '08:00';
    private const CLOSE_TIME   = '18:00';
    private const SLOT_MINUTES = 30; // raster

    // Duur per resource (in minuten)
    private const DURATIONS = [
        'aanhanger'  => 60,
        'stofzuiger' => 30,
        'koplampen'  => 60, // nieuw: vaste duur, 1-klik
    ];

    // Toegestane resources
    private const RESOURCES = ['aanhanger','stofzuiger','koplampen'];

    // Labels voor nette weergave in mails
    private const LABELS = [
        'aanhanger'  => 'Aanhanger',
        'stofzuiger' => 'Tapijtreiniger',
        'koplampen'  => 'Koplampen polijsten',
    ];

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
            'typeLabel'   => self::LABELS[$type] ?? ucfirst($type),
            'openTime'    => self::OPEN_TIME,
            'closeTime'   => self::CLOSE_TIME,
            'slotMinutes' => self::SLOT_MINUTES,
            'durationMin' => self::DURATIONS[$type], // <-- voor 1-klik modus
        ]);
    }

    /** AJAX: vrije starttijden */
  public function slots(Request $request)
{
    $type = $this->normalizeType($request->query('type'));
    $date = \Illuminate\Support\Carbon::parse($request->query('date'))->timezone('Europe/Amsterdam');

    $open        = \Illuminate\Support\Carbon::parse($date->format('Y-m-d').' '.self::OPEN_TIME,  'Europe/Amsterdam');
    $close       = \Illuminate\Support\Carbon::parse($date->format('Y-m-d').' '.self::CLOSE_TIME, 'Europe/Amsterdam');
    $durationMin = self::DURATIONS[$type];

    // ✅ stapgrootte per resource: koplampen = 60 min, rest 30 min
    $stepMinutes = ($type === 'koplampen') ? 60 : self::SLOT_MINUTES;

    // bestaande reserveringen op die dag
    $dayReservations = Reservation::ofType($type)
        ->where('status','!=','cancelled')
        ->whereDate('start_at', $date->toDateString())
        ->get(['start_at','end_at']);

    $slots = [];
    for ($t = $open->copy(); $t->lt($close); $t->addMinutes($stepMinutes)) { // 👈 hier $stepMinutes
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

    /** Reservering opslaan + mails sturen */
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
        $end   = Carbon::parse($data['end_at'],   'Europe/Amsterdam');

        // Overlap-check
        if (Reservation::overlaps($type, $start, $end)) {
            return back()->withInput()
                ->withErrors(['start_at' => 'Dit tijdslot overlapt met een bestaande reservering. Kies een andere range.']);
        }

        // Opslaan
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

        // Mail naar admin + klant
        try {
            $label = self::LABELS[$type] ?? ucfirst($type);

            // Admin
            Mail::send(new BookingSubmitted([
                'type'       => $type,
                'type_label' => $label,
                'start_at'   => $start->format('Y-m-d H:i'),
                'end_at'     => $end->format('Y-m-d H:i'),
                'name'       => $data['name'],
                'phone'      => $data['phone'] ?? null,
                'email'      => $data['email'],
            ]));

            // Klant
            Mail::send(new BookingConfirmation([
                'type'       => $type,
                'type_label' => $label,
                'start_at'   => $start->format('Y-m-d H:i'),
                'end_at'     => $end->format('Y-m-d H:i'),
                'name'       => $data['name'],
                'phone'      => $data['phone'] ?? null,
                'email'      => $data['email'],
            ]));

        } catch (\Throwable $e) {
            Log::error('Booking mail failed: '.$e->getMessage(), ['exception' => $e]);
        }

        return redirect()
            ->route('booking.show', ['type' => $type])
            ->with('ok', 'Bedankt! Je reservering is bevestigd en staat in de agenda.');
    }
}
