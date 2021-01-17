<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Компонент для вывода модального окна, для причины отмены объявления
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Reject extends Component
{
    public $advert;

    /**
     * Reject constructor.
     *
     * @param $advert
     */
    public function __construct($advert)
    {
        $this->advert = $advert;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.reject');
    }
}
