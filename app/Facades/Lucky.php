<?php

namespace App\Facades;

use App\Models\Participant;
use Illuminate\Support\Facades\Facade;

/**
 * Class Lucky
 * @package App\Facades
 * @method static Participant register($telegramRequestData)
 * @method static void unregister($tgId, $tgChatId)
 * @method static Participant|null roll($tgChatId)
 */
class Lucky extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Lucky';
    }
}
