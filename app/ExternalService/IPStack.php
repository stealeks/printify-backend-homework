<?php

namespace App\ExternalService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Response;

class IPStack
{
    public static function getCountryCode(string $ipAddress) : ?string
    {
        $client = new Client();

        try {
            $response = $client->get(self::getURL($ipAddress), [
                'timeout' => 1,
            ]);

            if ($response->getStatusCode() == Response::HTTP_OK) {
                $json = $response->getBody()->getContents();

                $array = json_decode($json, true);
                if (is_array($array) and isset($array['country_code'])) {
                    return $array['country_code'];
                }
            }
        } catch (ConnectException $exception) {
        }

        // todo: log failed

        return null;
    }

    private static function getURL(string $ipAddress) : string
    {

        return \vsprintf('http://api.ipstack.com/%s?access_key=%s', [
            $ipAddress,
            config('ipstack.token'),
        ]);
    }
}
