<?php
declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Lucky
 * @package App\Facades
 * @method static string quote()
 * @method static string complement()
 * @method static string random()
 * @method static string unknownCommand()
 * @method static string error()
 * @method static string register()
 * @method static string unregister()
 * @method static string alreadyUnregister()
 * @method static string alreadyRegister()
 * @method static string emptyUserList()
 */
class Phrase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'phrase';
    }
}
