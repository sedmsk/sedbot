<?php
declare(strict_types=1);

namespace App\Helpers;

use GuzzleHttp\Client;

/**
 * Class Phrase
 * @package App\Helpers
 */
class Phrase
{
    private const COMPLEMENTS = [
        'я верю, что у меня всё получится!',
        'ты прекрасно сегодня выглядишь!',
        'мы отлично понимаем другу друга!',
        'всё стало прекрасно с твоим появлением!',
        'благодаря тебе я становлюсь лучше.',
        'ты всегда можешь оторваться от любых дел, чтобы побыть со мной.',
        'при взгляде на тебя мое сердце замирает.',
        'когда я с тобой, все остальное не имеет значения.',
        'самое замечательное - это быть рядом с тобой!',
        'ты всегда прекрасно выглядишь.',
        'ты всегда знаешь, чем меня порадовать.',
        'ты всегда радуешь меня просто тем, что ты есть.',
        'ты умеешь найти выход из любой ситуации.',
        'ты делаешь нашу жизнь счастливой.',
        'все мои знакомые от тебя в восторге.',
        'наши отношения не замутнены никакими подозрениями.',
        'я вижу и ценю, когды ты делаешь добрые дела окружающим.',
        'когда ты рядом, мир вокруг наполняется счастьем.',
        'я очень скучаю, пока жду тебя.',
        'мне нравится твоя утонченность.',
        'ты безумно вкусно готовишь.',
        'я ценю, что ты стремишься стать еще лучше, чтобы нравиться мне еще больше!',
        'мы можем всё, когда мы вместе!',
    ];

    /**
     * Фраза при сбросе всех коэффициентов
     * @return string
     */
    public static function wipe(): string
    {
        return 'Давайте жить дружно!';
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function quote(): string
    {
        $http = new Client();
        $response = $http->request(
            'GET',
            'https://api.forismatic.com/api/1.0/?method=getQuote&key=457653&format=json&lang=ru'
        );

        if ($response->getStatusCode() !== 200) {
            return "Добрую половину из вас я знаю вдвое хуже, чем следует,"
            . " а худую половину — вдвое меньше, чем надо бы.\n— Бильбо Бэггинс";
        }

        $quote = json_decode($response->getBody(), true);
        return $quote['quoteText'] . ($quote['quoteAuthor'] ? "\n{$quote['quoteAuthor']}" : '');
    }
}
