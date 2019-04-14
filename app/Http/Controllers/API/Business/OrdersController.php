<?php

namespace App\Http\Controllers\API\Business;

use App\Http\Controllers\API\Controller;
use App\Middleware\AddCountry;
use App\Model\Business\Classificators\Type;
use App\Model\Business\Order;
use App\Validator\Business\OrderValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $data = $request->all();

        OrderValidator::validate($data);

        $items = $data['items'];

        $order = DB::transaction(function () use ($request, $items) {
            $user = auth()->user();

            $order = Order::create([
                'sum' => 0,
                'country_code' => $request->header(AddCountry::HEADER),
                'user_id' => $user->id,
            ]);

            $order->items()->createMany($items);

            foreach ($order->items as $item) {
                $order->sum += ($item->quantity * $item->product->price);
            }

            $order->save();

            $minSum = config('business.min_order_sum');
            if ($order->sum < $minSum) {
                throw ValidationException::withMessages([
                    'items' => 'Sum is too low. Minimal sum is '.$minSum,
                ]);
            }

            return $order;
        });

        return response()->json($order, Response::HTTP_CREATED);
    }

    /**
     * @param string|null $typeId
     * @return JsonResponse
     */
    public function index(?string $typeId = null) : JsonResponse
    {
        $builder = Order::select();

        if ($typeId) {
            $type = Type::find($typeId);
            if (!$type) {
                throw new BadRequestHttpException();
            }

            // todo: use oneToMany relation?

            $builder->whereHas('items', function (Builder $builder) use ($type) {
                $builder->whereHas('product', function (Builder $builder) use ($type) {
                    $builder->where('type_id', '=', $type->id);
                });
            });
        }

        return response()->json($builder->paginate());
    }
}
