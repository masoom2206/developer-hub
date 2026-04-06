<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsoredAd extends Model
{
    protected $fillable = ['company', 'banner_url', 'target_url', 'placement', 'starts_at', 'ends_at'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }
}
