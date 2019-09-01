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
        'Я верю, что у меня всё получится!',
        'Ты прекрасно сегодня выглядишь!',
        'Мы отлично понимаем другу друга!',
        'Всё стало прекрасно с твоим появлением!',
        'Благодаря тебе я становлюсь лучше.',
        'Ты всегда можешь оторваться от любых дел, чтобы побыть со мной.',
        'При взгляде на тебя мое сердце замирает.',
        'Когда я с тобой, все остальное не имеет значения.',
        'Самое замечательное - это быть рядом с тобой!',
        'Ты всегда прекрасно выглядишь.',
        'Ты всегда знаешь, чем меня порадовать.',
        'Ты всегда радуешь меня просто тем, что ты есть.',
        'Ты умеешь найти выход из любой ситуации.',
        'Ты делаешь нашу жизнь счастливой.',
        'Все мои знакомые от тебя в восторге.',
        'Наши отношения не замутнены никакими подозрениями.',
        'Я вижу и ценю, когды ты делаешь добрые дела окружающим.',
        'Когда ты рядом, мир вокруг наполняется счастьем.',
        'Я очень скучаю, пока жду тебя.',
        'Мне нравится твоя утонченность.',
        'Ты безумно вкусно готовишь.',
        'Я ценю, что ты стремишься стать еще лучше, чтобы нравиться мне еще больше!',
        'Мы можем всё, когда мы вместе!',
    ];

    /**
     * Генерация комплемента
     * @return string
     */
    public static function complement(): string
    {
        $complements = self::COMPLEMENTS;
        shuffle($complements);
        return $complements[array_rand($complements)];
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
            . " а худую половину — вдвое меньше, чем надо бы. ©Бильбо Бэггинс";
        }

        $quote = json_decode((string) $response->getBody(), true);
        return $quote['quoteText'] . ($quote['quoteAuthor'] ? " ©{$quote['quoteAuthor']}" : '');
    }

    /**
     * Получаем случайную фразу
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function random(): string
    {
        switch (random_int(0, 1)) {
            case 0:
                return self::quote();
            case 1:
                return self::complement();
        }

        return 'Ну вот. Опять ты.';
    }
}
