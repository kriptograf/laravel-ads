<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Компонент модального окна
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
