<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BaseLayoutTag extends Seeder
{
    private $tableName = 'base_layout_tags';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table($this->tableName)->delete();
        $data = [
            [
                'id'                => 1,
                'name'              => 'Hybrid',
                'slug'              => Str::slug('Hybrid'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 2,
                'name'              => 'Anti Air',
                'slug'              => Str::slug('Anti Air'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 3,
                'name'              => 'Anti Dragon',
                'slug'              => Str::slug('Anti Dragon'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 4,
                'name'              => 'Anti 2 Stars',
                'slug'              => Str::slug('Anti 2 Stars'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 5,
                'name'              => 'Anti 3 Stars',
                'slug'              => Str::slug('Anti 3 Stars'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 6,
                'name'              => 'Anti Goblin',
                'slug'              => Str::slug('Anti Goblin'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 7,
                'name'              => 'Legend League',
                'slug'              => Str::slug('Legend League'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
        ];
        DB::table($this->tableName)->insert($data);
    }
}
