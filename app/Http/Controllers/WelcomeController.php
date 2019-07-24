<?php

namespace App\Http\Controllers;

use App\Facades\Telegram;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        logs()->debug('Telegram set webhook:', [
            'response' => Telegram::setWebhook([
                'url' => secure_url(route('telegram.webhook', [], false)),
            ]),
        ]);

        return view('welcome');
    }
}
