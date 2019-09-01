<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Participant
 * @package App\Models
 * @property int tg_id
 * @property string tg_name
 * @property int tg_chat
 * @property int factor
 */
class Participant extends Model
{
    /** @var int максимальное значение коэффициента */
    public const MAX = 1;
    /** @var int максимальное значение коэффициента */
    public const MIN = 0;
    /** @var int шаг изменения коэффициента */
    private const STEP = 0.25;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tg_id', 'tg_name', 'tg_chat', 'factor',
    ];


    /**
     * Увеличиваем коэффициент выпадания
     * @return Participant
     */
    public function factorUp(): self
    {
        $next = $this->factor + self::STEP;
        $this->factor = $next > self::MAX ? self::MAX : $next;
        return $this;
    }

    /**
     * Уменьшаем коэффициент выпадания
     * @return Participant
     */
    public function factorDown(): self
    {
        $next = $this->factor - self::STEP;
        $this->factor = $next < self::MIN ? self::MIN : $next;
        return $this;
    }
}
