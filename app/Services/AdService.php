<?php

namespace App\Services;

use App\Models\SponsoredAd;
use Illuminate\Support\Carbon;

class AdService
{
    public function getActiveAd(string $placement): ?SponsoredAd
    {
        return SponsoredAd::where('placement', $placement)
            ->whereDate('starts_at', '<=', Carbon::today())
            ->whereDate('ends_at', '>=', Carbon::today())
            ->inRandomOrder()
            ->first();
    }
}
