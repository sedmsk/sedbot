<?php

namespace App\Helpers;

use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;
use RuntimeException;

class Telegram
{
    public function __call($name, $arguments)
    {
        $curl = Curl::to($this->apiUrl($name));
        static::withProxy($curl);
        logs()->debug('Telegram request:', ['data' => $arguments[0] ?? []]);
        $data = $curl->withData($arguments[0] ?? [])->asJsonResponse()->post();
        logs()->debug('Telegram response:', ['data' => $arguments[0] ?? []]);

        return $data;
    }

    /**
     * @param string $method
     * @return string
     */
    protected function apiUrl(string $method): string
    {
        $token = config('services.telegram.token');
        if ($token === null) {
            throw new RuntimeException('Empty Telegram Bot Token');
        }

        return 'https://api.telegram.org/bot'.config('services.telegram.token').'/'.$method;
    }

    /**
     * @param Builder $curl
     */
    protected static function withProxy(Builder $curl): void
    {
        $proxy = config('services.telegram.proxy');

        if ($proxy !== null) {
            $proxy = parse_url($proxy);

            if ($proxy['host'] === null) {
                throw new RuntimeException('Invalid proxy');
            }

            $curl->withProxy(
                $proxy['host'],
                $proxy['port'] ?? '',
                $proxy['scheme'] ? $proxy['scheme'].'://' : '',
                $proxy['user'] ?? '',
                $proxy['pass'] ?? ''
            );
        }
    }
}
