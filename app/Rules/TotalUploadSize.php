<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TotalUploadSize implements ValidationRule
{
    public function __construct(protected int $maxMb) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalBytes = collect($value ?? [])->sum(fn($f) => $f?->getSize() ?? 0);
        if ($totalBytes > $this->maxMb * 1024 * 1024) {
            $fail("De totale uploadgrootte mag niet groter zijn dan {$this->maxMb} MB.");
        }
    }
}