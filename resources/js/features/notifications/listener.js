import { MODULE_PREFIX } from "../../core/config";

export default function NotificationListener() {
  console.log("NotificationListener initialized");

  const userIdElement = document.querySelector("meta[name='user-id']");

  if (userIdElement) {
    const userId = userIdElement.getAttribute("content");

    console.log("Subscribing to channel:", `${MODULE_PREFIX}.notifications.${userId}`);

    const privateNotif = Echo.private(`${MODULE_PREFIX}.notifications.${userId}`);

    privateNotif.notification((notification) => {
      Alpine.store("notifications").addNotification(notification);
    });
  }
}