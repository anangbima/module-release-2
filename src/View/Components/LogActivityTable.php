<?php

namespace Modules\ModuleRelease2\View\Components;

use Illuminate\View\Component;

class LogActivityTable extends Component
{
    public $logs;
    public $role;

    public function __construct($logs, $role = null)
    {
        $this->logs = $logs;
        $this->role = $this->role = $role ?? module_release_2_auth_user()?->role;
    }
    
    public function render()
    {
        return view(module_release_2_meta('kebab').'::components.log-activity-table');
    }
}