<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Exceptions\LuckyRegisterException;
use App\Exceptions\LuckyUnregisterException;
use App\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

/**
 * Class Lucky
 * @package App\Helpers
 */
class Lucky
{
    /**
     * @param array $requestData @see https://tlgrm.ru/docs/bots/api
     * @return Participant
     * @throws LuckyRegisterException
     */
    public static function register(array $requestData)
    {
        if (\array_key_exists('username', $requestData['message']['from'])) {
            $username = '@'.$requestData['message']['from']['username'];
        } else {
            $username = trim(implode(' ', [
                $requestData['message']['from']['first_name'] ?? '',
                $requestData['message']['from']['last_name'] ?? '',
            ]), '\s');
        }

        try {
            $participant = Participant::create([
                'tg_id' => $requestData['message']['from']['id'],
                'tg_name' => $username,
                'tg_chat' => $requestData['message']['chat']['id'],
                'factor' => 1,
            ]);
        } catch (QueryException $exception) {
            if (in_array($exception->getCode(), [23000, 23505])) {
                throw new LuckyRegisterException($exception->getMessage());
            }

            throw $exception;
        }

        return $participant;
    }

    /**
     * @param int $tgId
     * @param int $tgChatId
     * @throws \Exception
     */
    public static function unregister(int $tgId, int $tgChatId): void
    {
        try {
            Participant::where(['tg_id' => $tgId, 'tg_chat' => $tgChatId,])->firstOrFail()->delete();
        } catch (ModelNotFoundException $e) {
            throw new LuckyUnregisterException();
        }
    }

    /**
     * @param int $chat
     * @return Participant|null
     */
    public static function roll(int $chat): ?Participant
    {
        $participants = Participant::where(['tg_chat' => $chat])->get();
        $list = [];
        foreach ($participants as $participant) {
            for ($i = 0, $l = 100 * $participant->factor; $i < $l; $i++) {
                $list[] = $participant;
            }
        }

        if ($list === []) {
            return null;
        }

        shuffle($list);
        $lucky = $list[array_rand($list)];

        /** @var Participant $participant */
        foreach ($participants as $participant) {
            // Для выпавшего понижаем коэффициент
            if ($lucky->id == $participant->id) {
                $participant->factorDown();
            } else {
                // для других повышаем
                $participant->factorUp();
            }
            $participant->save();
        }

        return $lucky;
    }
}
