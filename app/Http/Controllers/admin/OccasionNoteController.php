<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Models\OccasionNote;
use Illuminate\Http\Request;

class OccasionNoteController extends Controller
{
    public function store(Request $request, Occasion $occasion)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $occasion->notes()->create([
            'body'    => $data['body'],
            'user_id' => $request->user()?->id,
        ]);

        return back()->with('success', 'Notitie toegevoegd.');
    }

    public function destroy(Occasion $occasion, OccasionNote $note)
    {
        abort_unless($note->occasion_id === $occasion->id, 404);
        $note->delete();
        return back()->with('success', 'Notitie verwijderd.');
    }
}
