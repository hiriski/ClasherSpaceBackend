<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseLayoutCategory extends Seeder
{

    private $tableName = 'base_layout_categories';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table($this->tableName)->delete();
        $data = [
            [
                'id'                => 1,
                'name'              => 'War',
                'slug'              => Str::slug('War'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 2,
                'name'              => 'Farming',
                'slug'              => Str::slug('Farming'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 3,
                'name'              => 'Defense',
                'slug'              => Str::slug('Defense'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 4,
                'name'              => 'Trophy',
                'slug'              => Str::slug('Trophy'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
            [
                'id'                => 5,
                'name'              => 'Troll / Funny',
                'slug'              => Str::slug('Troll / Funny'),
                'isInitial'         => true,
                'createdAt'         => Carbon::now(),
            ],
        ];
        DB::table($this->tableName)->insert($data);
    }
}
