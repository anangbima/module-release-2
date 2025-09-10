<?php

namespace Modules\ModuleRelease2\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render()
    {
        return view(module_release_2_meta('kebab').'::layouts.guest');
    }
}