<?php

namespace app\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserComposer
{
    public function compose(View $view)
    {
        $view->with('user', Auth::user());
    }
}