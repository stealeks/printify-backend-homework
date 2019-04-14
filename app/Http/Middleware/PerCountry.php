<?php

namespace App\Middleware;

use App\Model\Business\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PerCountry
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $countryCode = $request->header(AddCountry::HEADER);

        $date = new \DateTime();

        $date->modify(config('business.limit_per_country_date_interval'));

        $count = Order::whereCountryCode($countryCode)
            ->where('created_at', '>=', $date)
            ->count();

        $maxOrders = config('business.limit_per_country_max_orders');
        if ($count > $maxOrders) {
            throw new TooManyRequestsHttpException();
        }

        return $next($request);
    }
}
