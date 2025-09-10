<?php

namespace Modules\DesaModuleTemplate\View\Components;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render()
    {
        return view(desa_module_template_meta('kebab').'::layouts.guest');
    }
}