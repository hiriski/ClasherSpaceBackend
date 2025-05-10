<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class ClashOfClansPlayer extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId',
        'playerTag',
        'data',
        'syncedAt'
    ];
}
