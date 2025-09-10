<?php

namespace Modules\DesaModuleTemplate\View\Components;

use Illuminate\View\Component;

class UserLayout extends Component
{
    public function __construct(public ?string $title = null, public ?array $breadcrumbs = null)
    {
        $this->title = $title ?? config('app.name', 'My Application ');
        $this->breadcrumbs = $breadcrumbs ?? [];
    }

    public function render()
    {
        return view(desa_module_template_meta('kebab').'::layouts.user');
    }
}