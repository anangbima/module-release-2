<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Models\Notification;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Shared\NotificationService;

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
                    ->getRead(module_release_2_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
            case 'unread':
                $notifications = $this->notificationService
                    ->getUnread(module_release_2_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
            case 'all':
            default:
                $notifications = $this->notificationService
                    ->getAll(module_release_2_auth_user()->id)
                    ->groupBy(fn($n) => $this->groupByDate($n));
                break;
        }

        $role = module_release_2_auth_user()->role;

        $data = [
            'title' => 'Notifications',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route(module_release_2_meta('kebab').'.'.$role.'.index')],
                ['name' => 'Notification', 'url' => '#'],
            ],
            'notifications' => $notifications,
            'role' => $role,
            'filter' => $filter, 
            'totalAll' => $this->notificationService->getAll(module_release_2_auth_user()->id)->count(),
            'totalRead' => $this->notificationService->getRead(module_release_2_auth_user()->id)->count(),
            'totalUnread' => $this->notificationService->getUnread(module_release_2_auth_user()->id)->count(),
        ];

        return view(module_release_2_meta('kebab').'::web.shared.notification.index', $data);
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
        $notificationUpdated = $this->notificationService->read($notification->id, module_release_2_auth_user()->id);
        $role = module_release_2_auth_user()->role;

        $data = [
            'title' => 'Detail Notification',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.'.$role.'.index'),
                ],
                [
                    'name' => 'Notifications',
                    'url' => route(module_release_2_meta('kebab').'.'.$role.'.notifications.index'),
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

        return view(module_release_2_meta('kebab').'::web.shared.notification.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route(module_release_2_meta('kebab').'.admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }
    
    /**
     * Mark the specified notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $this->notificationService->read($notification->id, module_release_2_auth_user()->id);

        return redirect()->route(module_release_2_meta('kebab').'.admin.notifications.index')->with('success', 'Notification marked as read successfully.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $this->notificationService->readAll(module_release_2_auth_user()->id);

        return redirect()->route(module_release_2_meta('kebab').'.admin.notifications.index')->with('success', 'All notifications marked as read successfully.');
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        $this->notificationService->clearAll(module_release_2_auth_user()->id);

        return redirect()->route(module_release_2_meta('kebab').'.admin.notifications.index')->with('success', 'All notifications deleted successfully.');
    }
}
