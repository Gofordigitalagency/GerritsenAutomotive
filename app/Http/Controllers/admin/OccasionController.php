<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Http\Requests\StoreOccasionRequest;
use App\Rules\TotalUploadSize;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OccasionController extends Controller
{
    /* ===== Overzicht ===== */
    public function index()
    {
        $items = Occasion::latest()->paginate(20);
        return view('admin.occasions.index', compact('items'));
    }

    /* ===== Create / Edit ===== */
    public function create()
    {
        return view('admin.occasions.form', ['occasion' => new Occasion()]);
    }

    public function edit(Occasion $occasion)
    {
        return view('admin.occasions.form', compact('occasion'));
    }

    /* ===== Store ===== */
    public function store(StoreOccasionRequest $request)
    {
        $data = $request->validated();

        // Textareas -> arrays (opties)
        $data['exterieur_options'] = $this->linesToArray($data['exterieur_options_text'] ?? null);
        $data['interieur_options'] = $this->linesToArray($data['interieur_options_text'] ?? null);
        $data['veiligheid_options'] = $this->linesToArray($data['veiligheid_options_text'] ?? null);
        $data['overige_options']    = $this->linesToArray($data['overige_options_text'] ?? null);
        unset(
            $data['exterieur_options_text'],
            $data['interieur_options_text'],
            $data['veiligheid_options_text'],
            $data['overige_options_text']
        );

        // Hoofdfoto
        if ($request->hasFile('hoofdfoto')) {
            $data['hoofdfoto_path'] = $request->file('hoofdfoto')->store('occasions', 'public');
        }

        // Galerij (meerdere foto's) — respecteer sleepvolgorde via gallery_new_order (JSON)
        $galleryPaths = [];
        $files = $request->file('gallery', []);
        if (!empty($files)) {
            $order = json_decode($request->input('gallery_new_order', '[]'), true);
            if (is_array($order) && count($order)) {
                // Bouw nieuwe volgorde op basis van "oude indices"
                $files = collect($order)->map(fn($i) => $files[$i] ?? null)->filter()->values()->all();
            }

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $galleryPaths[] = $file->store('occasions', 'public');
                }
            }
        }
        $data['galerij'] = $galleryPaths ?: null;

        // Slug
        $data['slug'] = Str::slug(($data['merk'] ?? '') . ' ' . ($data['model'] ?? '') . ' ' . ($data['bouwjaar'] ?? ''))
            . '-' . Str::random(4);

        Occasion::create($data);

        return redirect()->route('admin.occasions.index')->with('ok', 'Aangemaakt');
    }

    /* ===== Update ===== */
    public function update(StoreOccasionRequest $request, Occasion $occasion)
    {
        $data = $request->validated();

        // Textareas -> arrays (opties)
        $data['exterieur_options'] = $this->linesToArray($data['exterieur_options_text'] ?? null);
        $data['interieur_options'] = $this->linesToArray($data['interieur_options_text'] ?? null);
        $data['veiligheid_options'] = $this->linesToArray($data['veiligheid_options_text'] ?? null);
        $data['overige_options']    = $this->linesToArray($data['overige_options_text'] ?? null);
        unset(
            $data['exterieur_options_text'],
            $data['interieur_options_text'],
            $data['veiligheid_options_text'],
            $data['overige_options_text']
        );

        // Hoofdfoto
        if ($request->hasFile('hoofdfoto')) {
            if ($occasion->hoofdfoto_path) {
                Storage::disk('public')->delete($occasion->hoofdfoto_path);
            }
            $data['hoofdfoto_path'] = $request->file('hoofdfoto')->store('occasions', 'public');
        }

        // Galerij: voeg nieuwe toe, maar respecteer sleepvolgorde van de NIEUWE uploads
        $gallery = $occasion->galerij ?? [];

        $newFiles = $request->file('gallery', []);
        if (!empty($newFiles)) {
            $order = json_decode($request->input('gallery_new_order', '[]'), true);
            if (is_array($order) && count($order)) {
                $newFiles = collect($order)->map(fn($i) => $newFiles[$i] ?? null)->filter()->values()->all();
            }

            foreach ($newFiles as $file) {
                if ($file && $file->isValid()) {
                    $gallery[] = $file->store('occasions', 'public');
                }
            }
        }
        $data['galerij'] = $gallery;

        $occasion->update($data);

        return redirect()->route('admin.occasions.index')->with('ok', 'Bijgewerkt');
    }

    /* ===== Destroy ===== */
    public function destroy(Occasion $occasion)
    {
        // Hoofdfoto
        if ($occasion->hoofdfoto_path) {
            Storage::disk('public')->delete($occasion->hoofdfoto_path);
        }
        // Galerij
        foreach (($occasion->galerij ?? []) as $p) {
            Storage::disk('public')->delete($p);
        }

        $occasion->delete();
        return back()->with('ok', 'Verwijderd');
    }

    /* ===== Galerij acties (losse knoppen) ===== */

    // Foto's toevoegen zonder overige wijzigingen
    public function addGallery(Request $request, Occasion $occasion)
    {
        // Extra check: max aantal + totale MB (zelfde grenzen als StoreOccasionRequest)
        $request->validate([
            'gallery'   => ['required','array','max:15', new TotalUploadSize(30)], // totaal ≤ 30MB
            'gallery.*' => ['required','image','mimes:jpg,jpeg,png,webp','max:6144'], // ≤ 6MB per foto
        ]);

        $gallery = $occasion->galerij ?? [];

        $files = $request->file('gallery', []);
        // (optioneel) respecteer ook hier een gallery_new_order uit een losse upload-sectie
        $order = json_decode($request->input('gallery_new_order', '[]'), true);
        if (is_array($order) && count($order)) {
            $files = collect($order)->map(fn($i) => $files[$i] ?? null)->filter()->values()->all();
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $gallery[] = $file->store('occasions', 'public');
            }
        }

        $occasion->galerij = $gallery;
        $occasion->save();

        return back()->with('ok', 'Foto’s toegevoegd.');
    }

public function toggleStatus(Occasion $occasion)
{
    // MODEL-veld gebruiken voor VERKOCHT-tag
    $model = $occasion->model ?? '';

    // Staat er al VERKOCHT?
    if (str_contains($model, '(VERKOCHT)')) {
        // VERKOCHT verwijderen
        $model = trim(str_replace('(VERKOCHT)', '', $model));
    } else {
        // VERKOCHT toevoegen
        $model = trim($model . ' (VERKOCHT)');
    }

    $occasion->model = $model;
    $occasion->save();

    return back()->with('ok', 'Status aangepast');
}

    // Bestaande galerij-foto verwijderen (index i)
    public function removeGallery(Occasion $occasion, int $i)
    {
        $gallery = $occasion->galerij ?? [];
        if (!array_key_exists($i, $gallery)) {
            return back()->with('ok', 'Foto niet gevonden.');
        }

        // bestand verwijderen
        Storage::disk('public')->delete($gallery[$i]);

        // reset cover als het dezelfde is
        if ($occasion->hoofdfoto_path === $gallery[$i]) {
            $occasion->hoofdfoto_path = null;
        }

        unset($gallery[$i]);
        $occasion->galerij = array_values($gallery);
        $occasion->save();

        return back()->with('ok', 'Foto verwijderd.');
    }

    // Foto uit galerij als hoofdfoto instellen
    public function setCover(Occasion $occasion, int $i)
    {
        $gallery = $occasion->galerij ?? [];
        if (!array_key_exists($i, $gallery)) {
            return back()->with('ok', 'Foto niet gevonden.');
        }

        $occasion->hoofdfoto_path = $gallery[$i];
        $occasion->save();

        return back()->with('ok', 'Hoofdfoto ingesteld.');
    }

    // ✅ NIEUW: volgorde van bestaande galerij opslaan
    public function galleryReorder(Occasion $occasion, Request $request)
    {
        $order = $request->input('order'); // array met oude indices in nieuwe volgorde
        $gallery = $occasion->galerij ?? [];
        $count = count($gallery);

        if (!is_array($order) || $count === 0) {
            return back()->withErrors(['order' => 'Ongeldige volgorde.']);
        }

        // Veiligheid & normalisatie
        $order = array_values(array_filter(array_map('intval', $order), fn($i) => $i >= 0 && $i < $count));
        if (count($order) !== $count) {
            return back()->withErrors(['order' => 'Volgorde-lengte klopt niet.']);
        }

        $new = [];
        foreach ($order as $oldIndex) {
            $new[] = $gallery[$oldIndex];
        }

        $occasion->galerij = $new;
        $occasion->save();

        return back()->with('ok', 'Volgorde opgeslagen.');
    }

    /* ===== Helpers ===== */

    private function linesToArray(?string $txt): ?array
    {
        if (!$txt) return null;

        return collect(preg_split('/\r\n|\r|\n/', (string)$txt))
            ->map(fn($s) => trim($s))
            ->filter()
            ->values()
            ->all();
    }
}
