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
        'Я верю, что у тебя всё получится!',
        'Ты прекрасно сегодня выглядишь!',
        'Всё стало прекрасно с твоим появлением!',
        'Благодаря тебе я становлюсь лучше.',
        'Ты всегда можешь оторваться от любых дел, чтобы побыть со мной.',
        'Ты всегда прекрасно выглядишь.',
        'Ты всегда знаешь, чем меня порадовать.',
        'Ты умеешь найти выход из любой ситуации.',
        'Ты делаешь нашу жизнь счастливой.',
        'Наши отношения не замутнены никакими подозрениями.',
        'Я вижу и ценю, когды ты делаешь добрые дела окружающим.',
        'Когда ты рядом, мир вокруг наполняется счастьем.',
        'Мне нравится твоя утонченность.',
        'Я ценю, что ты стремишься стать еще лучше, чтобы нравиться мне еще больше!',
        'Мы можем всё, когда мы вместе!',
    ];

    private const CUSTOM_QUOTES = [
        'Моя собственность. Моя любовь. Моя… моя прелесть.' => 'Голлум',
        'Опасное это дело, Фродо, выходить за порог: стоит ступить на дорогу и, если дашь волю ногам, неизвестно куда тебя занесёт.' => '',
        'Кто торопится, часто бьёт мимо.' => '',
        'Знаешь, мужество прячется в самых неожиданных местах.' => 'Гилдор Инглорион',
        'Добрую половину из вас я знаю вдвое хуже, чем следует, а худую половину — вдвое меньше, чем надо бы.' => 'Бильбо Бэггинс',
        'Впервые слышу от тебя не только дельный, но и приятный совет. Даже опасаюсь: поскольку все твои неприятные советы неизменно были к добру, то не к худу ли приятный?' => 'Бильбо Бэггинс',
        'Не умеешь как следует работать ногами - работай головой.' => 'Сэм Гэмджи',
        'На чувства свои вы всегда и во всем опираться должны.' => 'Магистр Йода',
        'Когда тебе 900 лет исполнится, тоже не молодо будешь выглядеть ты.' => 'Магистр Йода',
        'Нет! Не пробуй. Делай. Или не делай. Не пробуй.' => 'Магистр Йода',
        'Путаешь небо со звездами, отраженными ночью в поверхности пруда.' => '',
        'Ибо спорит либо дурак, либо подлец. Первый – не знает, а спорит, второй знает, но спорит.' => '',
        'Грядет Час Меча и Топора. Час Презрения и Волчьей Пурги.' => '',
        'Мы – пешки в его игре. В игре, правил которой не знаем' => '',
        'Никогда не выпадает вторая оказия создать первое впечатление. Пошли лучше выпьем пива.' => '',
        'Что Вы хотите этим сказать? Желаете мне доброго утра? Или утверждаете, что утро доброе и не важно, что я о нём думаю? Или может, Вы хотите сказать, что испытали на себе доброту этого утра? Или Вы считаете, что все должны быть добрыми в это утро?' => 'Гэндальф',
        'В те времена я жил исключительно добропорядочной жизнью и ничего нежданного никогда не случалось.' => 'Бильбо Бэггинс',
        'Все началось очень просто, как и можно было догадаться: В норе под землей жил-был хоббит. Не в мерзкой грязной сырой норе, где полно червей и воняет плесенью. Это была хоббичья нора. А это значит: вкусная еда, теплый очаг, всякие удобства и домашний уют.' => '',
        'Да пребудет с тобой сила' => '',
        'я твой отец!' => 'Дарт Вейдер',
        'Ты был Избранным! Предрекали что ты уничтожишь ситхов, а не примкнешь к ним! Восстановишь равновесие Силы, а не ввергнешь ее во мрак!' => 'Оби-Ван Кеноби',
        'Моя мама всегда говорила: "Жизнь как коробка шоколадных конфет: никогда не знаешь, какая начинка тебе попадётся"' => 'Форрест Гамп',
        'Обожаю запах напалма по утрам' => '',
        'Если у общества нет цветовой дифференциации штанов, значит, у него нет цели' => '',
        'Ну, граждане алкоголики, хулиганы, тунеядцы, кто хочет сегодня поработать?' => '',
        'О боже мой, они убили Кенни!' => '',
        'Ничего не понимаю! Или что-то случилось, или одно из двух!' => '',
        'Щас спою!' => '',
        'Мы с тобой одной крови — ты и я!' => '',
        'У меня есть предложение, от которого ты не сможешь отказаться!' => '',
        'Ну вот. Опять ты.' => '',
    ];

    private const REGISTER = [
        'Добро пожаловать!',
        'Очень рад! Очень рад!',
        'Поздравляю! Ты в деле.',
        'Принято! Надеюсь, ты не будешь здесь шуметь?',
        'Здравствуй!',
        'Проходи, надеюсь, ты не голоден?',
        'Вы зарегистрированы.',
        'Ваше прибывание здесь согласовано.',
        'От мержа не убежать 😈',
        'Кто ходит в гости по утрам, тот поступает мудро! Добро пожаловать!',
        'Ты в теме! Я буду держать тебя в курсе',
    ];

    private const UNREGISTER = [
        'Ну ты это, заходи, если что.',
        'Надеюсь, ты решил отдохнуть? Удачи!',
        'До свидания!',
        'Хорошо отдохнуть!',
        'Мы будем по тебе скучать!',
        'Жаль, что ты больше не хочешь участвовать😔 Возвращайся в любое время!',
    ];

    /**
     * Генерация комплемента
     * @return string
     */
    public static function complement(): string
    {
        return self::randomValue(self::COMPLEMENTS);
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
     * Получаем случайную фразу (цитату, комплемент, нейтральное обращение)
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function random(): string
    {
        switch (random_int(0, 2)) {
            case 0:
                return self::quote();
            case 1:
                return self::complement();
            case 2:
                return self::customQuote();
        }

        return 'Ну вот. Опять ты.';
    }

    /**
     * @return string
     */
    public static function alreadyUnregister(): string
    {
        return 'Ты уже и так не участвуешь 😳';
    }

    /**
     * @return string
     */
    public static function alreadyRegister(): string
    {
        return 'Ты уже в деле! 😉';
    }

    /**
     * @return string
     */
    public static function unregister(): string
    {
        return self::randomValue(self::UNREGISTER);
    }

    /**
     * @return string
     */
    public static function register(): string
    {
        return self::randomValue(self::REGISTER);
    }

    /**
     * Неизвестная команда
     * @return string
     */
    public static function unknownCommand(): string
    {
        return 'Я не понимаю чего ты от меня хочешь 😭';
    }

    /**
     * @return string
     */
    public static function emptyUserList(): string
    {
        return 'К сожалению нет ни одного желающего поучаствовать 😨';
    }

    /**
     * Сообщение ошибки
     * @return string
     */
    public static function error(): string
    {
        return 'Упс, ошибочка 😡';
    }

    public static function randomValue(array $array): string
    {
        $map = $array;
        shuffle($map);
        return $map[array_rand($map)];
    }

    private static function customQuote(): string
    {
        $rand = array_rand(self::CUSTOM_QUOTES);
        return $rand . (self::CUSTOM_QUOTES[$rand] ? " ©" . self::CUSTOM_QUOTES[$rand] : '');
    }
}
