<?php

namespace App\Http\Controllers;

use App\Facades\Telegram;
use App\Models\Participant;

class WebhookController extends Controller
{
    public function __invoke()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        logs()->debug('Webhook data:', ['request' => $data]);

        if ($data['message'] && $data['message']['entities']) {
            foreach ($data['message']['entities'] as $entity) {
                switch ($entity['type']) {
                    case 'bot_command':
                        return $this->callCommand($entity, $data);
                    default:
                        Telegram::sendMessage([
                            'chat_id' => $data['message']['chat']['id'],
                            'text' => 'Я не понимаю чего ты от меня хочешь:(',
                        ]);
                }
            }
        }

        return 'ok';
    }

    protected function callCommand($entity, $data): string
    {
        $command = substr($data['message']['text'], $entity['offset'], $entity['length']);

        if (preg_match('/\/(\w+)/', $command, $matches)) {
            $commandMethod = $matches[1].'Command';

            if (method_exists($this, $commandMethod)) {
                return $this->{$commandMethod}($data);
            }
        }

        Telegram::sendMessage([
            'chat_id' => $data['message']['chat']['id'],
            'text' => 'Я не знаю такую команду:(',
        ]);

        return 'ok';
    }

    protected function registerCommand($data): string
    {
        try {
            Participant::create([
                'tg_id' => $data['message']['from']['id'],
                'tg_name' => trim(implode(' ', [
                    $data['message']['from']['first_name'] ?? '',
                    $data['message']['from']['last_name'] ?? '',
                ]), '\s'),
                'tg_chat' => $data['message']['chat']['id'],
                'factor' => 1.0,
            ]);

            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => 'Поздравяю! Ты в деле',
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            switch ($exception->getCode()) {
                case 23000:
                    Telegram::sendMessage([
                        'chat_id' => $data['message']['chat']['id'],
                        'text' => 'Ты уже в деле!',
                    ]);

                    return 'ok';
                default:
                    return $this->unknownError($data);
            }
        } catch (\Throwable $t) {
            logs()->critical('Register command error', ['error' => $t]);

            return $this->unknownError($data);
        }

        return 'ok';
    }

    protected function unknownError($data): string
    {
        Telegram::sendMessage([
            'chat_id' => $data['message']['chat']['id'],
            'text' => 'Упс, ошибочка:(',
        ]);

        return 'ok';
    }

    protected function unregisterCommand($data): string
    {
        try {
            Participant::where([
                'tg_id' => $data['message']['from']['id'],
                'tg_chat' => $data['message']['chat']['id'],
            ])
                ->firstOrFail()
                ->delete();

            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => "Жаль, что ты больше не хочешь участвовать:(\nВозвращайся в любое время",
            ]);

            return 'ok';
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => 'Ты уже и так не участвуешь:(',
            ]);

            return 'ok';
        } catch (\Throwable $t) {
            logs()->critical('Register command error', ['error' => $t]);

            return $this->unknownError($data);
        }
    }

    protected function rollCommand($data): string
    {
        $participants = Participant::where([
            'tg_chat' => $data['message']['chat']['id'],
        ])
            ->get();

        $list = [];

        foreach ($participants as $participant) {
            for ($i = 0, $l = 100 * $participant->factor; $i < $l; $i++) {
                $list[] = $participant;
            }
        }

        if ($list === []) {
            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => 'К сожалению нет ни одного желающего поучаствовать:(',
            ]);

            return 'ok';
        }

        shuffle($list);

        Telegram::sendMessage([
            'chat_id' => $data['message']['chat']['id'],
            'parse_mode' => 'HTML',
            'text' => implode(' ', [
                '<a href="tg://user?id='.$list[array_rand($list)]->tg_id.'">'.$list[array_rand($list)]->tg_name.'</a>',
                'сегодня "побеждает", дружно поздавляем'
            ]),
        ]);

        return 'ok';
    }
}
