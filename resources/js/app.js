import './core/bootstrap'; // inside the module
import Alpine from 'alpinejs'; // package import
import NotificationListener from './features/notifications/listener'; // inside the module
import NotificationComponent from './features/notifications/component'; // inside the module

Alpine.store('notifications', NotificationComponent);
NotificationListener();
