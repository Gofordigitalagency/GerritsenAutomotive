<?php

namespace App\Http\Controllers;

use App\Mail\WorkshopAppointmentMail;
use App\Models\WorkshopAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WorkshopAppointmentController extends Controller
{
    private function wizard(): array
    {
        return session()->get('workshop_wizard', []);
    }

    private function putWizard(array $data): void
    {
        session()->put('workshop_wizard', array_merge($this->wizard(), $data));
    }

    public function step1()
    {
        return view('workshop.step1', [
            'data' => $this->wizard(),
        ]);
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => ['required','string','max:20'],
            'mileage' => ['nullable','integer','min:0','max:2000000'],
        ]);

        $validated['license_plate'] = strtoupper(str_replace([' ', '-'], '', $validated['license_plate']));

        $this->putWizard($validated);

        return redirect()->route('workshop.step2');
    }

    public function step2()
    {
        return view('workshop.step2', [
            'data' => $this->wizard(),
            'services' => config('workshop_services'),
        ]);
    }

    public function postStep2(Request $request)
    {
        $maintenance = config('workshop_services.maintenance');
        $extras = config('workshop_services.extras');

        $validated = $request->validate([
            'maintenance_option' => ['nullable','string'],
            'extra_services' => ['nullable','array'],
            'extra_services.*' => ['string'],
        ]);

        // extra veiligheid: alleen waarden uit config toestaan
        if (!empty($validated['maintenance_option']) && !in_array($validated['maintenance_option'], $maintenance, true)) {
            abort(422);
        }

        $selectedExtras = $validated['extra_services'] ?? [];
        foreach ($selectedExtras as $x) {
            if (!in_array($x, $extras, true)) abort(422);
        }

        $this->putWizard([
            'maintenance_option' => $validated['maintenance_option'] ?? null,
            'extra_services' => $selectedExtras,
        ]);

        return redirect()->route('workshop.step3');
    }

    public function step3()
    {
        return view('workshop.step3', [
            'data' => $this->wizard(),
        ]);
    }

    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'appointment_date' => ['required','date'],
            'appointment_time' => ['required','date_format:H:i'],
            'wait_while_service' => ['required','in:0,1'],
        ]);

        $this->putWizard([
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'wait_while_service' => (bool) $validated['wait_while_service'],
        ]);

        return redirect()->route('workshop.step4');
    }

    public function step4()
    {
        return view('workshop.step4', [
            'data' => $this->wizard(),
        ]);
    }

    public function finish(Request $request)
    {
        $maintenance = config('workshop_services.maintenance');
        $extras = config('workshop_services.extras');

        /**
         * Belangrijk:
         * - Als je de modal gebruikt (1 formulier), komen stap1/2/3 velden ook hier binnen.
         * - Als je de session wizard gebruikt, komen stap1/2/3 al uit session.
         * Daarom valideren we ze hier als "sometimes" / "nullable" en mergen we slim.
         */
        $validated = $request->validate([
            // (voor 1-submit modal)
            'license_plate' => ['sometimes','required','string','max:20'],
            'mileage' => ['sometimes','nullable','integer','min:0','max:2000000'],

            'maintenance_option' => ['sometimes','nullable','string'],
            'extra_services' => ['sometimes','nullable','array'],
            'extra_services.*' => ['string'],

            'appointment_date' => ['sometimes','required','date'],
            'appointment_time' => ['sometimes','required','date_format:H:i'],
            'wait_while_service' => ['sometimes','required','in:0,1'],

            // contact
            'company_name' => ['nullable','string','max:255'],
            'salutation' => ['nullable','in:dhr,mevr'],
            'first_name' => ['required','string','max:120'],
            'middle_name' => ['nullable','string','max:120'],
            'last_name' => ['required','string','max:120'],

            'street' => ['nullable','string','max:255'],
            'house_number' => ['nullable','string','max:30'],
            'addition' => ['nullable','string','max:30'],
            'postal_code' => ['nullable','string','max:20'],
            'city' => ['nullable','string','max:120'],

            'phone' => ['nullable','string','max:30'],
            'email' => ['required','email','max:255'],
            'remarks' => ['nullable','string'],

            'terms_accepted' => ['accepted'],
            'marketing_opt_in' => ['nullable','in:0,1'],
        ]);

        // Normaliseer kenteken ook hier (voor 1-submit modal)
        if (isset($validated['license_plate'])) {
            $validated['license_plate'] = strtoupper(str_replace([' ', '-'], '', $validated['license_plate']));
        }

        // Extra veiligheid: onderhoud/extra alleen uit config
        if (!empty($validated['maintenance_option']) && !in_array($validated['maintenance_option'], $maintenance, true)) {
            abort(422);
        }

        $selectedExtras = $validated['extra_services'] ?? null;
        if (is_array($selectedExtras)) {
            foreach ($selectedExtras as $x) {
                if (!in_array($x, $extras, true)) abort(422);
            }
        }

        // Als session wizard leeg is, vullen we 'm met de stapvelden uit request (modal)
        if (empty($this->wizard())) {
            $this->putWizard($request->only([
                'license_plate',
                'mileage',
                'maintenance_option',
                'extra_services',
                'appointment_date',
                'appointment_time',
                'wait_while_service',
            ]));
        }

        // Merge: session (stap 1-3) + validated contact + defaults
        $data = array_merge($this->wizard(), $validated, [
            'marketing_opt_in' => (bool)($validated['marketing_opt_in'] ?? false),
            'terms_accepted' => true,
            'status' => 'pending',
        ]);

        // Zorg dat wait_while_service boolean is
        if (isset($data['wait_while_service'])) {
            $data['wait_while_service'] = (bool) $data['wait_while_service'];
        }

        // Zorg dat extra_services altijd array is
        $data['extra_services'] = $data['extra_services'] ?? [];

        $appointment = WorkshopAppointment::create($data);

        // Mail naar Gerritsen Automotive (volgens jouw .env)
        $to = env('BOOKING_ADMIN_EMAIL') ?: env('CONTACT_TO_EMAIL');

       if ($to) {
         Mail::to($to)->send(new WorkshopAppointmentMail($appointment->toArray()));
}

        session()->forget('workshop_wizard');

        return redirect('/')->with('success', 'Afspraak aangevraagd! We nemen zo snel mogelijk contact op.');
    }
}
