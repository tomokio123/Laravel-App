<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.guest');
        //このビュー(layouts.guest)を呼び出すように指定している。どこにあるかというと
        //resources/views/layouts/guest.blade.phpファイルにある。
    }
}
