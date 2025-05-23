<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClashOfClansClan extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'clanTag',
        'data',
        'syncedAt'
    ];
}
