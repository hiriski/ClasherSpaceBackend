<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseLayout extends BaseModel
{
    use HasFactory;

    public const BASE_TYPE_TOWN_HALL   = 'townhall';
    public const BASE_TYPE_BUILDER     = 'builder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'link',
        'name',
        'description',
        'userId',
        'townHallLevel',
        'builderHallLevel',
        'baseType',
        'imageUrls',
        'views',
        'likedCount',
        'markedAsWarBase',
    ];

    /**
     * Scope a query to only include base with type "townhall".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsTownHallBase($query)
    {
        return $query->where('status', self::BASE_TYPE_TOWN_HALL);
    }

    /**
     * Scope a query to only include base with type "townhall".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsBuilderBase($query)
    {
        return $query->where('status', self::BASE_TYPE_BUILDER);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(BaseLayoutCategory::class, 'base_layout_to_category', 'baseLayoutId', 'categoryId');
    }

    public function tags()
    {
        return $this->belongsToMany(BaseLayoutTag::class, 'base_layout_to_tag', 'baseLayoutId', 'tagId');
    }
}
