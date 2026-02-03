<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OccasionPdfController extends Controller
{
    public function raamkaart(Occasion $occasion)
    {
        // LOGO (zet logo in public/assets/gerritsen-logo.png)
        $logo = public_path('assets/gerritsen-logo.png');

        // TITEL
        $titel = trim(($occasion->merk ?? '') . ' ' . ($occasion->model ?? '') . ' ' . ($occasion->type ?? ''));

        // ✅ FOTO: hoofdfoto, anders eerste galerij
        $photo = $this->resolvePhotoPath($occasion->hoofdfoto_path ?? null);

        if (!$photo) {
            $galerij = $occasion->galerij ?? [];
            if (is_string($galerij)) $galerij = json_decode($galerij, true) ?: [];
            if (is_array($galerij) && !empty($galerij)) {
                $photo = $this->resolvePhotoPath($galerij[0] ?? null);
            }
        }

        // ✅ OPTIES: combineer alle 4 lijsten tot 1 lijst
        $opties = [];
        foreach (['exterieur_options', 'interieur_options', 'veiligheid_options', 'overige_options'] as $field) {
            $arr = $occasion->{$field} ?? [];
            if (is_string($arr)) $arr = json_decode($arr, true) ?: [];
            if (is_array($arr)) $opties = array_merge($opties, $arr);
        }
        $opties = array_values(array_filter(array_map('trim', $opties)));

        $pdf = Pdf::loadView('admin.pdf.raamkaart', [
            'occasion' => $occasion,
            'titel'    => $titel,
            'photo'    => $photo, // absolute filesystem path (DomPDF safe)
            'logo'     => $logo,  // absolute filesystem path (DomPDF safe)
            'opties'   => $opties,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('raamkaart-' . Str::slug($titel ?: 'occasion') . '.pdf');
    }

    /**
     * Zet DB waarde om naar een absolute filesystem path in storage/app/public/...
     * Werkt ook als DB "public\storage\occasions\xxx.jpg" bevat.
     */
    private function resolvePhotoPath(?string $raw): ?string
    {
        if (!$raw) return null;

        // 1) Windows slashes -> unix slashes
        $p = str_replace('\\', '/', trim($raw));

        // 2) strip mogelijke prefixes
        // public/storage/... of /storage/... of storage/...
        $p = preg_replace('#^public/storage/#', '', $p);
        $p = preg_replace('#^/storage/#', '', $p);
        $p = preg_replace('#^storage/#', '', $p);

        // 3) Als de DB per ongeluk "public/..." heeft: strip "public/"
        $p = preg_replace('#^public/#', '', $p);

        // 4) Normaliseer: occasions/xxx.jpg
        $p = ltrim($p, '/');

        // 5) Absolute path in echte storage (DomPDF kan dit altijd lezen)
        $abs = storage_path('app/public/' . $p);

        return file_exists($abs) ? $abs : null;
    }

    /**
     * Voor als je ooit nog andere velden wilt detecten (optioneel).
     */
    private function toArray($value): ?array
    {
        if (is_array($value)) return $value;

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') return null;

            // JSON?
            if (Str::startsWith($value, ['[', '{'])) {
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // object met items/data/images
                    if (is_array($decoded) && Arr::isAssoc($decoded)) {
                        return $decoded['items'] ?? $decoded['data'] ?? $decoded['images'] ?? null;
                    }
                    return $decoded;
                }
            }
        }

        return null;
    }
}
