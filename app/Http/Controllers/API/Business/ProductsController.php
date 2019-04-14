<?php

namespace App\Http\Controllers\API\Business;

use App\Http\Controllers\API\Controller;
use App\Model\Business\Product;
use App\Validator\Business\ProductValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $user = Auth::user();

        $data = $request->all();

        ProductValidator::validate($data);

        $data['user_id'] = $user->id;

        $product = Product::create($data);

        return response()->json($product, Response::HTTP_CREATED);
    }
}
