<?php namespace App\Models;

use Carbon\Carbon as Carbon;

use Config;

trait PublishedAtTimestampsFormatTrait
{
    public function getPublishedAtAttribute($value)
    {
        return Carbon::parse($value)->toIso8601String();
    }

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = Carbon::parse($value)->toDateTimeString();
    }

}