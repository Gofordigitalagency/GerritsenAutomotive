<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandingPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'cta_label',
        'cta_url',
        'show_occasions',
        'body',
        'faq',
        'is_published',
    ];

    protected $casts = [
        'faq'            => 'array',
        'show_occasions' => 'boolean',
        'is_published'   => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Body (Markdown) als veilige HTML. */
    public function bodyHtml(): string
    {
        return $this->body ? Str::markdown($this->body) : '';
    }

    /** Alleen gevulde FAQ-items. */
    public function faqItems(): array
    {
        return collect($this->faq ?? [])
            ->filter(fn ($item) => filled($item['question'] ?? null) && filled($item['answer'] ?? null))
            ->values()
            ->all();
    }

    /** Publieke URL. */
    public function url(): string
    {
        return url('/' . $this->slug);
    }
}
