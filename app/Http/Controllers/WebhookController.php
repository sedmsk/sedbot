<?php

namespace App\Http\Controllers;

use App\Exceptions\LuckyRegisterException;
use App\Exceptions\LuckyUnregisterException;
use App\Facades\Lucky;
use App\Facades\Phrase;
use App\Facades\Telegram;

class WebhookController extends Controller
{
    public function __invoke()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $chatId = $data['message']['chat']['id'];
        logs()->debug('Webhook data:', ['request' => $data]);
        try {
            if (array_key_exists('message', $data) && array_key_exists('entities', $data['message'])) {
                foreach ($data['message']['entities'] as $entity) {
                    switch ($entity['type']) {
                        case 'bot_command':
                            return $this->callCommand($entity, $data);
                        default:
                            Telegram::sendMessage([
                                'chat_id' => $chatId,
                                'text' => Phrase::unknownCommand(),
                            ]);
                    }
                }
            }
        } catch (\Throwable $exception) {
            logs()->critical('Register command error', ['error' => $exception]);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => Phrase::error(),
            ]);
        }

        return 'ok';
    }

    /**
     * @param $entity
     * @param $data
     * @return string
     */
    protected function callCommand($entity, $data): string
    {
        $command = substr($data['message']['text'], $entity['offset'], $entity['length']);

        if (preg_match('/^\/(\w+)$/', trim($command), $matches) === 1) {
            $commandMethod = $matches[1].'Command';

            if (method_exists($this, $commandMethod)) {
                return $this->{$commandMethod}($data);
            }
        }

        Telegram::sendMessage([
            'chat_id' => $data['message']['chat']['id'],
            'text' => Phrase::unknownCommand(),
        ]);

        return 'ok';
    }

    /**
     * Регистрация участника
     * @param array $data
     * @return string
     */
    protected function registerCommand(array $data): string
    {
        try {
            Lucky::register($data);
            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => Phrase::register(),
            ]);
        } catch (LuckyRegisterException $exception) {
            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => Phrase::alreadyRegister(),
            ]);

            return 'ok';
        }

        return 'ok';
    }

    /**
     * Отказ от регистрации
     * @param $data
     * @return string
     */
    protected function unregisterCommand($data): string
    {
        try {
            $chatId = (int) $data['message']['chat']['id'];
            Lucky::unregister((int) $data['message']['from']['id'], $chatId);
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => Phrase::unregister(),
            ]);
        } catch (LuckyUnregisterException $exception) {
            Telegram::sendMessage([
                'chat_id' => $data['message']['chat']['id'],
                'text' => Phrase::alreadyUnregister(),
            ]);
        }

        return 'ok';
    }

    /**
     * Сброс коэффициентов
     * @param $data
     * @return string
     */
    protected function wipeCommand(array $data): string
    {
        $chatId = (int)$data['message']['chat']['id'];
        Lucky::wipe($chatId);
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => Phrase::wipe(),
        ]);
        return 'ok';
    }

        /**
     * Команда выбора "счастливчика"
     * @param $data
     * @return string
     */
    protected function rollCommand($data): string
    {
        $chat = (int) $data['message']['chat']['id'];
        $lucky = Lucky::roll($chat);
        if ($lucky === null) {
            Telegram::sendMessage([
                'chat_id' => $chat,
                'text' => Phrase::emptyUserList(),
            ]);

            return 'ok';
        }

        Telegram::sendMessage([
            'chat_id' => $chat,
            'parse_mode' => 'HTML',
            'text' => implode(' ', [
                "<a href=\"tg://user?id={$lucky->tg_id}\">{$lucky->tg_name}</a>",
                Phrase::random()
            ]),
        ]);

        return 'ok';
    }
}
