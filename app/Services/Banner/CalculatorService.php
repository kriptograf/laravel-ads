<?php

declare(strict_types=1);

namespace App\Services\Banner;

/**
 * Калькулятор для баннерной системы
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class CalculatorService
{
    private $price;

    /**
     * CalculatorService constructor.
     *
     * @param $price
     */
    public function __construct($price)
    {
        $this->price = $price;
    }

    /**
     * Расчет стоимости баннера
     *
     * @param int $views
     *
     * @return integer
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function calculateCost(int $views): int
    {
        return (int)floor($this->price * ($views / 1000));
    }
}