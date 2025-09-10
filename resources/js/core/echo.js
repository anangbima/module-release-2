import Echo from "laravel-echo";
import Pusher from "pusher-js";
import { MODULE_PREFIX } from "./config";

export function initEcho() {
  console.log("[initEcho] starting…");
  console.log("[initEcho] MODULE_PREFIX =", MODULE_PREFIX);

  window.Pusher = Pusher;

  window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],

    authEndpoint: `${window.location.origin}/${MODULE_PREFIX}/broadcasting/auth`,
    auth: {
      headers: {
        "X-CSRF-TOKEN": window.axios.defaults.headers.common["X-CSRF-TOKEN"]
      },
      withCredentials: false,
    }
  });
}
