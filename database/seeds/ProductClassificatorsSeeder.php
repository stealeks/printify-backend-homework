<?php

use Illuminate\Database\Seeder;
use App\Model\Business\Classificators;
use Illuminate\Support\Facades\DB;

class ProductClassificatorsSeeder extends Seeder
{
    public function run() : void
    {
        // todo: is there way to do bulk insert using ORM?

        DB::table(Classificators\Type::TABLE)->insert([
            [
                'title' => 'T-Shirt',
            ],
            [
                'title' => 'Mug',
            ],
            [
                'title' => 'Notebook',
            ],
        ]);

        DB::table(Classificators\Color::TABLE)->insert([
            [
                'title' => 'White',
                'value' => 0x000000,
            ],
            [
                'title' => 'Red',
                'value' => 0xff0000,
            ],
            [
                'title' => 'Green',
                'value' => 0x00ff00,
            ],
            [
                'title' => 'Blue',
                'value' => 0x0000ff,
            ],
            [
                'title' => 'Black',
                'value' => 0x333333,
            ],
            [
                'title' => 'Best',
                'value' => 0xffffff,
            ],
        ]);

        DB::table(Classificators\Size::TABLE)->insert([
            [
                'title' => 'Extra Small',
            ],
            [
                'title' => 'Small',
            ],
            [
                'title' => 'Medium',
            ],
            [
                'title' => 'Large',
            ],
            [
                'title' => 'Extra Large',
            ],
        ]);
    }
}
