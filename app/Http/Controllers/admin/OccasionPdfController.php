<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OccasionPdfController extends Controller
{
public function raamkaart(Occasion $occasion)
{
    // LOGO
    $logo = public_path('assets/gerritsen-logo.png');

    // TITEL
    $titel = trim(($occasion->merk ?? '') . ' ' . ($occasion->model ?? '') . ' ' . ($occasion->type ?? ''));

    // FOTO: eerst hoofdfoto_path, anders eerste galerij
    $photo = $this->resolvePublicDiskImage($occasion->hoofdfoto_path ?? null);

    if (!$photo) {
        $galerij = $occasion->galerij ?? [];
        if (is_string($galerij)) $galerij = json_decode($galerij, true) ?: [];
        if (is_array($galerij) && !empty($galerij)) {
            $photo = $this->resolvePublicDiskImage($galerij[0] ?? null);
        }
    }

    // DEBUG (tijdelijk)
    // dd([
    //     'hoofdfoto_path_db' => $occasion->hoofdfoto_path,
    //     'photo_abs' => $photo,
    //     'exists' => $photo ? file_exists($photo) : false,
    //     'readable' => $photo ? is_readable($photo) : false,
    // ]);

    // OPTIES
    $opties = [];
    foreach (['exterieur_options','interieur_options','veiligheid_options','overige_options'] as $field) {
        $arr = $occasion->{$field} ?? [];
        if (is_string($arr)) $arr = json_decode($arr, true) ?: [];
        if (is_array($arr)) $opties = array_merge($opties, $arr);
    }
    $opties = array_values(array_filter(array_map('trim', $opties)));

    $pdf = Pdf::loadView('admin.pdf.raamkaart', compact('occasion','titel','photo','logo','opties'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('raamkaart-' . Str::slug($titel ?: 'occasion') . '.pdf');
}

private function resolvePublicDiskImage(?string $raw): ?string
{
    if (!$raw) return null;

    $p = str_replace('\\', '/', trim($raw));

    // strip verkeerde prefixes (als dit in DB zit)
    $p = preg_replace('#^public/storage/#', '', $p);
    $p = preg_replace('#^/storage/#', '', $p);
    $p = preg_replace('#^storage/#', '', $p);

    if (!Storage::disk('public')->exists($p)) return null;

    return Storage::disk('public')->path($p);
}

    private function detectMainPhotoPath(Occasion $o): ?string
    {
        // 1) Probeer directe velden (pas de lijst gerust uit)
        $candidates = [
            $o->hoofdfoto ?? null,
            $o->hoofd_foto ?? null,
            $o->cover ?? null,
            $o->cover_image ?? null,
            $o->main_photo ?? null,
            $o->main_photo_path ?? null,
            $o->foto ?? null,
            $o->image ?? null,
            $o->afbeelding ?? null,
            $o->thumbnail ?? null,
        ];

        foreach ($candidates as $path) {
            $abs = $this->toAbsolutePublicPath($path);
            if ($abs && file_exists($abs)) return $abs;
        }

        // 2) Probeer gallery velden (array of json)
        $galleryCandidates = [
            $o->gallery ?? null,
            $o->fotos ?? null,
            $o->images ?? null,
            $o->afbeeldingen ?? null,
        ];

        foreach ($galleryCandidates as $gallery) {
            $arr = $this->toArray($gallery);

            // Pak cover als die bestaat, anders eerste
            $coverIndex = is_numeric($o->cover_index ?? null) ? (int)$o->cover_index : null;

            $first = null;
            if (is_array($arr) && count($arr)) {
                if ($coverIndex !== null && isset($arr[$coverIndex])) {
                    $first = $arr[$coverIndex];
                } else {
                    $first = $arr[0];
                }
            }

            // sommige structures: [{path:"/storage/..."}]
            if (is_array($first)) {
                $first = $first['path'] ?? $first['url'] ?? $first['src'] ?? null;
            }

            $abs = $this->toAbsolutePublicPath($first);
            if ($abs && file_exists($abs)) return $abs;
        }

        return null;
    }

    private function detectOptions(Occasion $o): array
    {
        // 1) Als je een relatie hebt: $occasion->options (collection)
        if (method_exists($o, 'options')) {
            try {
                $rel = $o->options()->pluck('name')->toArray();
                if (!empty($rel)) return $rel;
            } catch (\Throwable $e) {}
        }

        // 2) Probeer mogelijke velden (string / json)
        $raw = $o->opties
            ?? $o->options
            ?? $o->uitrusting
            ?? $o->kenmerken
            ?? $o->features
            ?? $o->highlights
            ?? null;

        // 3) json array? newline? comma?
        $arr = $this->toArray($raw);

        if (is_array($arr) && count($arr)) {
            // flatten en trim
            $flat = [];
            foreach ($arr as $item) {
                if (is_array($item)) {
                    $item = $item['name'] ?? $item['title'] ?? $item['label'] ?? null;
                }
                if (is_string($item)) $flat[] = trim($item);
            }
            return array_values(array_filter($flat));
        }

        if (is_string($raw) && strlen(trim($raw))) {
            // newline first, else comma
            $raw = str_replace("\r\n", "\n", $raw);
            $parts = str_contains($raw, "\n") ? explode("\n", $raw) : explode(',', $raw);
            $parts = array_map('trim', $parts);
            return array_values(array_filter($parts));
        }

        return [];
    }

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

    // 3) als het nu bv "occasions/xxx.jpg" is: maak absolute path
    $abs = storage_path('app/public/' . ltrim($p, '/'));

    return file_exists($abs) ? $abs : null;
}

    private function toAbsolutePublicPath($value): ?string
    {
        if (!is_string($value) || trim($value) === '') return null;

        $value = trim($value);

        // Als het al een absolute pad is
        if (Str::startsWith($value, [public_path(), base_path()])) {
            return $value;
        }

        // Als het een URL is (dompdf vindt dit vaak lastig), we skippen dit
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return null;
        }

        // Normale paden zoals /storage/... of storage/...
        $value = ltrim($value, '/');
        return public_path($value);
    }

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
                    // als object met key 'items' of 'data'
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
