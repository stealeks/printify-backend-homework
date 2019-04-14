<?php

namespace App\Middleware;

use App\ExternalService\IPStack;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AddCountry
{
    public const HEADER = 'Country-Code';

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $countryCode = null;

        $ipAddress = $request->ip();
        if ($ipAddress) {
            $countryCode = IPStack::getCountryCode($ipAddress);
        }

        if (!$countryCode) {
            $countryCode = config('business.default_country_code');
        }

        $request->headers->set(self::HEADER, Str::lower($countryCode));

        return $next($request);
    }
}
