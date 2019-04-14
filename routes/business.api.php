<?php

use App\Model\Business\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use App\Model\Business\Classificators;

Route::group([
    'prefix' => 'business',
    'middleware' => 'auth:api',
], function () {
    Route::group([
        'prefix' => 'classificators',
    ], function () {
        Route::get('colors', function () {
            return Classificators\Color::paginate();
        });

        Route::get('types', function () {
            return new JsonResource(Classificators\Type::paginate());
        });

        Route::get('sizes', function () {
            return new JsonResource(Classificators\Size::paginate());
        });
    });

    Route::group([
        'prefix' => 'products',
    ], function () {
        Route::post('create', 'ProductsController@create');

        Route::get('index', function () {
            return new JsonResource(Product::paginate());
        });
    });

    Route::group([
        'prefix' => 'orders',
    ], function () {
        Route::group([
            'middleware' => [
                'add_country',
                'per_country',
            ],
        ], function () {
            Route::post('create', 'OrdersController@create');
        });

        Route::post('index/{product_type_id?}', 'OrdersController@index');
    });
});
