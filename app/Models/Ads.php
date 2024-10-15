<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'budget',
        'button_text',
        'campaign_id',
        'content',
        'cpm',
        'status',
        'title',
        'url',
    ];

    protected $table = 'ads';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}