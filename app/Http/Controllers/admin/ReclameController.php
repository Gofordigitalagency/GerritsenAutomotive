<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclame;
use App\Models\ReclameItem;
use App\Models\Occasion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReclameController extends Controller
{
    public function index()
    {
        $reclames = Reclame::latest()->paginate(20);
        return view('admin.reclame.index', compact('reclames'));
    }

    public function create()
    {
        // haal occasions om te kiezen (maak dit evt. met search/ajax als je veel hebt)
        $occasions = Occasion::orderByDesc('created_at')->limit(200)->get();
        return view('admin.reclame.form', [
            'reclame' => new Reclame(),
            'occasions' => $occasions,
            'selected' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:50'],
            'subtitle' => ['required','string','max:80'],
            'occasion_ids' => ['required','array','min:1','max:4'],
            'occasion_ids.*' => ['integer','exists:occasions,id'],
        ]);

        $reclame = Reclame::create([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
        ]);

        // positions 1..4
        foreach (array_values($data['occasion_ids']) as $i => $id) {
            ReclameItem::create([
                'reclame_id' => $reclame->id,
                'occasion_id' => $id,
                'position' => $i + 1,
            ]);
        }

        return redirect()->route('admin.reclame.edit', $reclame)->with('success', 'Reclame aangemaakt.');
    }

    public function edit(Reclame $reclame)
    {
        $reclame->load('items.occasion');
        $occasions = Occasion::orderByDesc('created_at')->limit(200)->get();
        $selected = $reclame->items->pluck('occasion_id')->values()->all();

        return view('admin.reclame.form', compact('reclame','occasions','selected'));
    }

    public function update(Request $request, Reclame $reclame)
    {
        $data = $request->validate([
            'title' => ['required','string','max:50'],
            'subtitle' => ['required','string','max:80'],
            'occasion_ids' => ['required','array','min:1','max:4'],
            'occasion_ids.*' => ['integer','exists:occasions,id'],
        ]);

        $reclame->update([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
        ]);

        $reclame->items()->delete();

        foreach (array_values($data['occasion_ids']) as $i => $id) {
            ReclameItem::create([
                'reclame_id' => $reclame->id,
                'occasion_id' => $id,
                'position' => $i + 1,
            ]);
        }

        return back()->with('success', 'Reclame opgeslagen.');
    }

    public function exportPdf(Reclame $reclame)
    {
        $reclame->load('items.occasion');

        // EXACT design: dompdf werkt het meest betrouwbaar met table-layout
        $pdf = Pdf::loadView('admin.reclame.pdf', compact('reclame'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('weekaanbieding-'.$reclame->id.'.pdf');
    }
}
