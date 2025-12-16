<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Query\Builder;

class OrderEvent extends Model
{
    // TODO: double check if this line is necessary if we dont have the column in migration
    const UPDATED_AT = null;

    protected $fillable = [
        'uuid',
        'order_id',
        'sequence',
        'event_type',
        'payload',
        'published_at',
    ];

    // for payload casting into array, it is required, other have to do manual json encode and decode
    // casting the published_at into datetime to get Carbon instance
    // else it would return string
    // however, if the timestamp is only being used as display then string is ok
    protected $casts = [
        'payload' => 'array',
        'published_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function scopeUnpublished(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    protected function scopeAfterSequence(Builder $query, int $sequence): Builder
    {
        return $query->where('sequence', '>', $sequence);
    }
}
