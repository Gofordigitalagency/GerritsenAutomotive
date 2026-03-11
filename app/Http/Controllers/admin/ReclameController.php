<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reclame;
use App\Models\ReclameItem;
use App\Models\Occasion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReclameController extends Controller
{
    public function index()
    {
        $reclames = Reclame::latest()->paginate(20);
        return view('admin.reclame.index', compact('reclames'));
    }

    public function create()
    {
$occasions = Occasion::where('model', 'NOT LIKE', '%(VERKOCHT)%')
    ->orderByDesc('created_at')
    ->limit(200)
    ->get();
        return view('admin.reclame.form', [
            'reclame'   => new Reclame(),
            'occasions' => $occasions,
            'selected'  => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:50'],
            'subtitle'     => ['required','string','max:80'],
            'occasion_ids' => ['required','array','min:1','max:4'],
            'occasion_ids.*' => ['integer','exists:occasions,id'],
        ]);

        $reclame = Reclame::create([
            'title'    => $data['title'],
            'subtitle' => $data['subtitle'],
        ]);

        foreach (array_values($data['occasion_ids']) as $i => $id) {
            ReclameItem::create([
                'reclame_id'  => $reclame->id,
                'occasion_id' => $id,
                'position'    => $i + 1,
            ]);
        }

        return redirect()
            ->route('admin.reclame.edit', $reclame)
            ->with('success', 'Reclame aangemaakt.');
    }

    public function edit(Reclame $reclame)
    {
        $reclame->load('items.occasion');
$occasions = Occasion::where('model', 'NOT LIKE', '%(VERKOCHT)%')
    ->orderByDesc('created_at')
    ->limit(200)
    ->get();        $selected  = $reclame->items->pluck('occasion_id')->values()->all();

        return view('admin.reclame.form', compact('reclame','occasions','selected'));
    }

    public function update(Request $request, Reclame $reclame)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:50'],
            'subtitle'     => ['required','string','max:80'],
            'occasion_ids' => ['required','array','min:1','max:4'],
            'occasion_ids.*' => ['integer','exists:occasions,id'],
        ]);

        $reclame->update([
            'title'    => $data['title'],
            'subtitle' => $data['subtitle'],
        ]);

        $reclame->items()->delete();

        foreach (array_values($data['occasion_ids']) as $i => $id) {
            ReclameItem::create([
                'reclame_id'  => $reclame->id,
                'occasion_id' => $id,
                'position'    => $i + 1,
            ]);
        }

        return back()->with('success', 'Reclame opgeslagen.');
    }

    public function exportPdf(Reclame $reclame)
    {
        // items + occasions laden, en sorteren op position
        $reclame->load(['items' => function ($q) {
            $q->orderBy('position');
        }, 'items.occasion']);

        // Maak een nette list van occasions (max 4) met photoDataUri (DOMPDF-proof)
        $items = $reclame->items
            ->map(fn ($it) => $it->occasion)
            ->filter()
            ->values()
            ->take(4)
            ->map(function ($o) {
                $photo = $this->resolvePublicDiskImage($o->hoofdfoto_path ?? null);

                $photoDataUri = null;
                if (!empty($photo) && file_exists($photo) && is_readable($photo)) {
                    $mime = mime_content_type($photo) ?: 'image/jpeg';
                    $photoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photo));
                }

                // zet op model zodat je in blade gewoon $o->photoDataUri kan gebruiken
                $o->photoDataUri = $photoDataUri;

                return $o;
            });

        $pdf = Pdf::loadView('admin.reclame.pdf', [
            'reclame' => $reclame,
            'items'   => $items,
        ])->setPaper('a4', 'portrait');

        // download of stream — jouw keuze
        return $pdf->download('weekaanbieding-' . $reclame->id . '.pdf');
        // return $pdf->stream('weekaanbieding-' . $reclame->id . '.pdf');
    }

    /**
     * Zelfde aanpak als raamkaart:
     * - DB pad "occasions/xxx.jpg" -> absolute path via Storage disk public
     */
    private function resolvePublicDiskImage(?string $raw): ?string
    {
        if (!$raw) return null;

        $p = str_replace('\\', '/', trim($raw));

        // haal storage-prefixen weg
        $p = preg_replace('#^public/storage/#', '', $p);
        $p = preg_replace('#^/public/storage/#', '', $p);
        $p = preg_replace('#^storage/#', '', $p);
        $p = preg_replace('#^/storage/#', '', $p);

        $p = ltrim($p, '/'); // moet bv: occasions/abc.jpg zijn

        if (!Storage::disk('public')->exists($p)) {
            return null;
        }

        return Storage::disk('public')->path($p); // absolute filesystem path
    }
}
