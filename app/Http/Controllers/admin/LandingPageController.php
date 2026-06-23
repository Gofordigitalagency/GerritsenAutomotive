<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LandingPageController extends Controller
{
    /** Slugs die al door vaste routes gebruikt worden — niet toegestaan. */
    private const RESERVED_SLUGS = [
        'aanbod', 'werkplaats', 'diensten', 'over', 'contact', 'preview',
        'login', 'logout', 'admin', 'api', 'occasions', 'binnenkort',
        'reserveren', 'auto-verkopen', 'sell-car', 'home', 'storage',
    ];

    public function index()
    {
        $pages = LandingPage::latest()->get();

        return view('admin.landingpages.index', compact('pages'));
    }

    public function create()
    {
        $page = new LandingPage(['is_published' => true]);

        return view('admin.landingpages.form', compact('page'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, null);

        $page = new LandingPage();
        $this->fill($page, $data, $request);
        $page->save();

        return redirect()
            ->route('admin.landingpages.index')
            ->with('ok', 'Landingspagina aangemaakt.');
    }

    public function edit(LandingPage $landingpage)
    {
        return view('admin.landingpages.form', ['page' => $landingpage]);
    }

    public function update(Request $request, LandingPage $landingpage)
    {
        $data = $this->validateData($request, $landingpage->id);

        $this->fill($landingpage, $data, $request);
        $landingpage->save();

        return redirect()
            ->route('admin.landingpages.index')
            ->with('ok', 'Landingspagina bijgewerkt.');
    }

    public function destroy(LandingPage $landingpage)
    {
        if ($landingpage->hero_image) {
            Storage::disk('public')->delete($landingpage->hero_image);
        }
        $landingpage->delete();

        return redirect()
            ->route('admin.landingpages.index')
            ->with('ok', 'Landingspagina verwijderd.');
    }

    // ---------------------------------------------------------------------

    private function validateData(Request $request, ?int $ignoreId): array
    {
        // Slug afleiden uit titel als hij leeg is gelaten.
        if (blank($request->input('slug')) && filled($request->input('title'))) {
            $request->merge(['slug' => Str::slug($request->input('title'))]);
        }

        return $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => [
                'required', 'string', 'max:200',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(self::RESERVED_SLUGS),
                Rule::unique('landing_pages', 'slug')->ignore($ignoreId),
            ],
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'hero_eyebrow'     => 'nullable|string|max:120',
            'hero_title'       => 'required|string|max:200',
            'hero_subtitle'    => 'nullable|string|max:500',
            'hero_image'       => 'nullable|image|max:5120',
            'cta_label'        => 'nullable|string|max:80',
            'cta_url'          => 'nullable|string|max:500',
            'body'             => 'nullable|string|max:20000',
            'faq'              => 'nullable|array',
            'faq.*.question'   => 'nullable|string|max:300',
            'faq.*.answer'     => 'nullable|string|max:2000',
        ], [
            'slug.regex'  => 'De URL mag alleen kleine letters, cijfers en koppeltekens bevatten.',
            'slug.not_in' => 'Deze URL is gereserveerd voor een bestaande pagina. Kies een andere.',
            'slug.unique' => 'Er bestaat al een landingspagina met deze URL.',
        ]);
    }

    private function fill(LandingPage $page, array $data, Request $request): void
    {
        $page->title            = $data['title'];
        $page->slug             = $data['slug'];
        $page->meta_title       = $data['meta_title'] ?? null;
        $page->meta_description = $data['meta_description'] ?? null;
        $page->hero_eyebrow     = $data['hero_eyebrow'] ?? null;
        $page->hero_title       = $data['hero_title'];
        $page->hero_subtitle    = $data['hero_subtitle'] ?? null;
        $page->cta_label        = $data['cta_label'] ?? null;
        $page->cta_url          = $data['cta_url'] ?? null;
        $page->body             = $data['body'] ?? null;
        $page->show_occasions   = $request->boolean('show_occasions');
        $page->is_published     = $request->boolean('is_published');

        // FAQ: lege rijen weggooien, opslaan als nette lijst.
        $page->faq = collect($data['faq'] ?? [])
            ->map(fn ($row) => [
                'question' => trim($row['question'] ?? ''),
                'answer'   => trim($row['answer'] ?? ''),
            ])
            ->filter(fn ($row) => $row['question'] !== '' && $row['answer'] !== '')
            ->values()
            ->all();

        // Hero-afbeelding: alleen vervangen bij nieuwe upload.
        if ($request->hasFile('hero_image')) {
            if ($page->hero_image) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $page->hero_image = $request->file('hero_image')->store('landing', 'public');
        }
    }
}
