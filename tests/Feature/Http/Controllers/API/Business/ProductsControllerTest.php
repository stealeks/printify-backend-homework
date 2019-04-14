<?php

namespace Tests\Feature\Http\Controllers\API\Auth;

use App\Model\Business\Classificators\Color;
use App\Model\Business\Classificators\Size;
use App\Model\Business\Classificators\Type;
use App\Model\Business\Product;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Tests\Feature\APITest;

class ProductsControllerTest extends APITest
{
    public function testSuccessfulCreation() : void
    {
        $this->login();

        $type = Type::whereTitle('Mug')->first();
        $color = Color::whereValue(0)->first();
        $size = Size::whereTitle('Medium')->first();

        $data = [
            'title' => 'Regular White Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
        ];

        $response = $this->requestCreate($data);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'id',
        ]);

        $response->assertJson($data);
    }

    public function testDuplicateCreationFail() : void
    {
        $user = $this->login();

        $type = Type::whereTitle('Mug')->first();
        $color = Color::whereValue(0)->first();
        $size = Size::whereTitle('Medium')->first();

        $product = Product::create([
            'title' => 'Regular White Mug',
            'price' => '1.99',
            'type_id' => $type->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'user_id' => $user->id,
        ]);

        $data = $product->jsonSerialize();

        $data['title'] = 'A';

        $response = $this->requestCreate($data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJsonValidationErrors([
            'title',
            'type_id',
            'color_id',
            'size_id',
        ]);
    }

    private function requestCreate(array $data) : TestResponse
    {
        return $this->postJson('/api/business/products/create', $data);
    }
}
