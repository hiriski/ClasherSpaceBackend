<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends BaseModel
{
    use HasFactory;

    public $table = 'feedbacks';

    public const TYPE_BUG               = 'bug';
    public const TYPE_MISSING_FEATURE   = 'missing_feature';
    public const TYPE_IMPROVEMENT       = 'improvement';
    public const TYPE_OTHER             = 'other';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'email',
        'body',
    ];
}
