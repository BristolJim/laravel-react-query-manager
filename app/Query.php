<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Query
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Query newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query query()
 * @mixin \Eloquent
 */
class Query extends Model
{
    protected $fillable = ['name', 'query'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
