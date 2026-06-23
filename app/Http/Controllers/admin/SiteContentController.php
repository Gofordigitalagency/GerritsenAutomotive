<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteContentController extends Controller
{
    /**
     * Toon de edit-pagina voor een groep settings.
     * Eén controller voor alle groepen — schema bepaalt de velden.
     */
    public function edit(string $group = null)
    {
        $schema = config('site_content', []);
        if (empty($schema)) {
            abort(500, 'Geen site_content schema gevonden.');
        }

        $group = $group ?? array_key_first($schema);
        if (!isset($schema[$group])) {
            abort(404);
        }

        $fields = $schema[$group]['fields'] ?? [];
        $values = SiteSetting::pluck('value', 'key')->toArray();

        return view('admin.site-content.edit', [
            'schema'      => $schema,
            'group'       => $group,
            'groupConfig' => $schema[$group],
            'fields'      => $fields,
            'values'      => $values,
        ]);
    }

    /**
     * Sla settings op voor één groep.
     */
    public function update(Request $request, string $group)
    {
        $schema = config("site_content.$group.fields");
        if (!$schema) abort(404);

        $rules = [];
        foreach ($schema as $key => $meta) {
            $field = $this->fieldName($key);
            switch ($meta['type'] ?? 'text') {
                case 'email':    $rules[$field] = 'nullable|email|max:200';      break;
                case 'phone':    $rules[$field] = 'nullable|string|max:40';      break;
                case 'url':      $rules[$field] = 'nullable|url|max:500';        break;
                case 'color':    $rules[$field] = 'nullable|string|max:50';      break;
                case 'image':    $rules[$field] = 'nullable|image|max:5120';     break; // file
                case 'longtext': $rules[$field] = 'nullable|string|max:5000';    break;
                default:         $rules[$field] = 'nullable|string|max:500';
            }
        }
        $request->validate($rules);

        $pairs = [];
        foreach ($schema as $key => $meta) {
            $field = $this->fieldName($key);
            $type  = $meta['type'] ?? 'text';

            if ($type === 'image') {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('site', 'public');
                    $pairs[$key] = $path;
                }
                // Geen upload? Bestaande waarde laten staan.
                continue;
            }

            // Reguliere tekst-input
            $pairs[$key] = $request->input($field);
        }

        SiteSetting::putMany($pairs);

        return redirect()
            ->route('admin.site-content.edit', $group)
            ->with('ok', 'Wijzigingen opgeslagen.');
    }

    /**
     * Settings keys gebruiken dots ('hero.title') — input names mogen
     * geen dots hebben in Laravel form input. We swappen ze voor `__`.
     */
    public static function fieldName(string $key): string
    {
        return str_replace('.', '__', $key);
    }

    public static function keyFromField(string $name): string
    {
        return str_replace('__', '.', $name);
    }
}
