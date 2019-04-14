<?php

namespace Tests\Feature\Http\Controllers\API\Auth;

use App\Model\Business\Classificators\Color;
use App\Model\Business\Classificators\Size;
use App\Model\Business\Classificators\Type;
use App\Model\Business\Order;
use App\Model\Business\Product;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Tests\Feature\APITest;

class OrderControllerTest extends APITest
{
    public function testSuccessfulCreation() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();
        $size = Size::whereTitle('Medium')->first();

        $white = Color::whereValue(0)->first();
        $red = Color::whereValue(0xff0000)->first();
        $black = Color::whereValue(0x333333)->first();

        $whiteMug = Product::create([
            'title' => 'Regular White Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $white->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $redMug = Product::create([
            'title' => 'Red Mug',
            'price' => '2.49',
            'type_id' => $type->id,
            'color_id' => $red->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $blackMug = Product::create([
            'title' => 'Regular Black Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $black->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);


        $items = [
            [
                'product_id' => $blackMug->id,
                'quantity' => '100',
            ],
            [
                'product_id' => $whiteMug->id,
                'quantity' => '50',
            ],
            [
                'product_id' => $redMug->id,
                'quantity' => '19',
            ],
        ];

        $response = $this->createOrder([
            'items' => $items,
        ]);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'id',
        ]);

        $response->assertJsonFragment([
            'sum' => 345.81,
        ]);
    }

    public function testZeroQuantityCreation() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();
        $size = Size::whereTitle('Medium')->first();
        $color = Color::whereValue(0x333333)->first();

        $product = Product::create([
            'title' => 'Regular Black Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => '0',
            ],
        ];

        $response = $this->createOrder([
            'items' => $items,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('items.0.quantity');
    }

    public function testTooLowSumCreation() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();
        $size = Size::whereTitle('Medium')->first();
        $color = Color::whereValue(0x333333)->first();

        $product = Product::create([
            'title' => 'Regular Black Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => '5',
            ],
        ];

        $response = $this->createOrder([
            'items' => $items,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('items');
    }

    public function testDuplicateProductOrderCreationFail() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();
        $size = Size::whereTitle('Medium')->first();

        $white = Color::whereValue(0)->first();
        $black = Color::whereValue(0x333333)->first();

        $whiteMug = Product::create([
            'title' => 'Regular White Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $white->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $blackMug = Product::create([
            'title' => 'Regular Black Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $black->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $items = [
            [
                'product_id' => $blackMug->id,
                'quantity' => '98',
            ],
            [
                'product_id' => $whiteMug->id,
                'quantity' => '50',
            ],
            [
                'product_id' => $blackMug->id,
                'quantity' => '2',
            ],
        ];

        $response = $this->createOrder([
            'items' => $items,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors([
            'items.0.product_id',
            'items.2.product_id',
        ]);
    }

    public function testEmptyOrderCreationFail() : void
    {
        $this->login();

        $response = $this->createOrder([
            'items' => [],
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors('items');
    }

    public function testOrdersPerCountryRestriction() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();

        $color = Color::whereValue(0xffffff)->first();
        $size = Size::whereTitle('Small')->first();

        $product = Product::create([
            'title' => 'Regular Mug',
            'price' => '1.00',
            'type_id' => $type->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $defaultCountryCode = config('business.default_country_code');

        for ($index = 0; $index <= config('business.limit_per_country_max_orders'); $index++) {
            $order = Order::create([
                'sum' => '1.99',
                'country_code' => $defaultCountryCode,
                'user_id' => $user->id,
            ]);
            $order->items()->createMany([
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]);
            $order->save();
        }

        $response = $this->createOrder([
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => '49',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    public function testIndex() : void
    {
        $user = $this->login();

        $mugType = Type::whereTitle('Mug')->first();
        $tShirtType = Type::whereTitle('T-Shirt')->first();

        $color = Color::whereValue(0xffffff)->first();
        $size = Size::whereTitle('Small')->first();

        $mug = Product::create([
            'title' => 'Regular Mug',
            'price' => '1.00',
            'type_id' => $mugType->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $tShirt = Product::create([
            'title' => 'Regular T-Shirt',
            'price' => '1.99',
            'type_id' => $tShirtType->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);


        $oneShirt = Order::create([
            'sum' => '1.99',
            'country_code' => 'uk',
            'user_id' => $user->id,
        ]);
        $oneShirt->items()->createMany([
            [
                'product_id' => $tShirt->id,
                'quantity' => 1,
            ]
        ]);
        $oneShirt->save();



        $twoMugs = Order::create([
            'sum' => '2.00',
            'country_code' => 'lv',
            'user_id' => $user->id,
        ]);
        $twoMugs->items()->createMany([
            [
                'product_id' => $mug->id,
                'quantity' => 2,
            ]
        ]);
        $twoMugs->save();



        $twoShirtsOneMug = Order::create([
            'sum' => '4.98',
            'country_code' => 'lt',
            'user_id' => $user->id,
        ]);
        $twoShirtsOneMug->items()->createMany([
            [
                'product_id' => $tShirt->id,
                'quantity' => 2,
            ],
            [
                'product_id' => $mug->id,
                'quantity' => 1,
            ],
        ]);
        $twoShirtsOneMug->save();

        $response = $this->postJson('/api/business/orders/index');

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'total' => 3,
        ]);


        $response = $this->postJson('/api/business/orders/index/'.$mugType->id);

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'data' => [
                $twoShirtsOneMug->jsonSerialize(),
                $twoMugs->jsonSerialize(),
            ],
            'total' => 2,
        ]);
    }

    /**
     * @param mixed[] $data
     * @return TestResponse
     */
    private function createOrder(array $data) : TestResponse
    {
        return $this->postJson('/api/business/orders/create', $data);
    }
}
