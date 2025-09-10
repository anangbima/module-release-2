<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Models\Notification;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Shared\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // default 'all'

        switch ($filter) {
            case 'read':
                $notifications = $this->notificationService
                    ->getRead(desa_module_template_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
            case 'unread':
                $notifications = $this->notificationService
                    ->getUnread(desa_module_template_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
            case 'all':
            default:
                $notifications = $this->notificationService
                    ->getAll(desa_module_template_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
        }

        $role = desa_module_template_auth_user()->role;

        $data = [
            'title' => 'Notifications',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route(desa_module_template_meta('kebab').'.'.$role.'.index')],
                ['name' => 'Notification', 'url' => '#'],
            ],
            'notifications' => $notifications,
            'role' => $role,
            'filter' => $filter, 
            'totalAll' => $this->notificationService->getAll(desa_module_template_auth_user()->id)->count(),
            'totalRead' => $this->notificationService->getRead(desa_module_template_auth_user()->id)->count(),
            'totalUnread' => $this->notificationService->getUnread(desa_module_template_auth_user()->id)->count(),
        ];

        return view(desa_module_template_meta('kebab').'::web.shared.notification.index', $data);
    }

    protected function groupByDate($notification)
    {
        if ($notification->created_at->isToday()) return 'Today';
        if ($notification->created_at->isYesterday()) return 'Yesterday';
        return $notification->created_at->format('d M Y');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        $notificationUpdated = $this->notificationService->read($notification->id, desa_module_template_auth_user()->id);
        $role = desa_module_template_auth_user()->role;

        $data = [
            'title' => 'Detail Notification',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.'.$role.'.index'),
                ],
                [
                    'name' => 'Notifications',
                    'url' => route(desa_module_template_meta('kebab').'.'.$role.'.notifications.index'),
                ],
                [
                    // 'name' => $notification->data['title'],
                    'name' => 'Detail Notification',
                    'url' => '#',
                ],
            ],
            'role' => $role,
            'notification' => $notificationUpdated,
        ];

        return view(desa_module_template_meta('kebab').'::web.shared.notification.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route(desa_module_template_meta('kebab').'.user.notifications.index')->with('success', 'Notification deleted successfully.');
    }
    
    /**
     * Mark the specified notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $this->notificationService->read($notification->id, desa_module_template_auth_user()->id);

        return redirect()->route(desa_module_template_meta('kebab').'.user.notifications.index')->with('success', 'Notification marked as read successfully.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $this->notificationService->readAll(desa_module_template_auth_user()->id);

        return redirect()->route(desa_module_template_meta('kebab').'.user.notifications.index')->with('success', 'All notifications marked as read successfully.');
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        $this->notificationService->clearAll(desa_module_template_auth_user()->id);

        return redirect()->route(desa_module_template_meta('kebab').'.user.notifications.index')->with('success', 'All notifications deleted successfully.');
    }
}
