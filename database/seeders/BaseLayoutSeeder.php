<?php

namespace Database\Seeders;

use App\Models\BaseLayout;
use App\Models\User;
use App\Models\BaseLayoutCategory;
use App\Models\BaseLayoutTag;
use Illuminate\Database\Seeder;

class BaseLayoutSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'general_user')->get();
        $categories = BaseLayoutCategory::all();
        $tags = BaseLayoutTag::all();

        // Sample base layouts
        $layouts = [
            [
                'name' => 'TH15 War Base Anti 3-Star',
                'link' => 'https://link.clashofclans.com/en?action=OpenLayout&id=TH15%3AWB%3AAAAAGQAAAAIMrKJL2qSz_iqFcpCVL3k0',
                'description' => 'Strong defensive base for war, designed to prevent 3-star attacks. Features centralized Town Hall with multi-layer protection.',
                'townHallLevel' => 15,
                'baseType' => 'townhall',
                'isWarBase' => true,
                'views' => rand(100, 5000),
                'likedCount' => rand(10, 500),
            ],
            [
                'name' => 'TH14 Trophy Push Base',
                'link' => 'https://link.clashofclans.com/en?action=OpenLayout&id=TH14%3AWB%3AAAAAGQAAAAIMrKJL2qSz_iqFcpCVL3k0',
                'description' => 'Optimized for trophy pushing with excellent defensive capabilities.',
                'townHallLevel' => 14,
                'baseType' => 'townhall',
                'isWarBase' => false,
                'views' => rand(100, 5000),
                'likedCount' => rand(10, 500),
            ],
            [
                'name' => 'TH13 Farm Base',
                'link' => 'https://link.clashofclans.com/en?action=OpenLayout&id=TH13%3AWB%3AAAAAGQAAAAIMrKJL2qSz_iqFcpCVL3k0',
                'description' => 'Resource protection focused base layout for farming.',
                'townHallLevel' => 13,
                'baseType' => 'townhall',
                'isWarBase' => false,
                'views' => rand(100, 5000),
                'likedCount' => rand(10, 500),
            ],
            [
                'name' => 'BH9 Trophy Base',
                'link' => 'https://link.clashofclans.com/en?action=OpenLayout&id=BH9%3ABB%3AAAAAGQAAAAIMrKJL2qSz_iqFcpCVL3k0',
                'description' => 'Builder Hall 9 base optimized for versus battles.',
                'builderHallLevel' => 9,
                'baseType' => 'builder',
                'isWarBase' => false,
                'views' => rand(100, 5000),
                'likedCount' => rand(10, 500),
            ],
            [
                'name' => 'TH12 Hybrid Base',
                'link' => 'https://link.clashofclans.com/en?action=OpenLayout&id=TH12%3AWB%3AAAAAGQAAAAIMrKJL2qSz_iqFcpCVL3k0',
                'description' => 'Balanced base for both trophy pushing and resource protection.',
                'townHallLevel' => 12,
                'baseType' => 'townhall',
                'isWarBase' => false,
                'views' => rand(100, 5000),
                'likedCount' => rand(10, 500),
            ],
        ];

        foreach ($layouts as $index => $layoutData) {
            $user = $users->random();
            $layout = BaseLayout::create(array_merge($layoutData, [
                'userId' => $user->id,
            ]));

            // Attach random categories
            if ($categories->isNotEmpty()) {
                $layout->categories()->attach(
                    $categories->random(rand(1, min(3, $categories->count())))->pluck('id')->toArray()
                );
            }

            // Attach random tags
            if ($tags->isNotEmpty()) {
                $layout->tags()->attach(
                    $tags->random(rand(1, min(3, $tags->count())))->pluck('id')->toArray()
                );
            }
        }
    }
}
