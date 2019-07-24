<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Telegram
 *
 * @package App\Facades
 *
 * @method static array setWebhook($params)
 * @method static array sendMessage($params) <ul><li>string $params['url']</li></ul>
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::class;
    }
}
