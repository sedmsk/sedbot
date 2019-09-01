<?php
declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Lucky
 * @package App\Facades
 * @method static string wipe()
 * @method static string quote()
 */
class Phrase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'phrase';
    }
}
