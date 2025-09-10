import { MODULE_PREFIX } from "../../core/config";

export default {
    count: 0,
    isOpen: false,
    isLoading: false,
    htmlContent: "",
    hasLoaded: false,
    init(initialCount) {
        this.count = initialCount;
    },
    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen && !this.hasLoaded) {
            this.load();
        }
    },
    async load() {
        this.isLoading = true;
        try {
            const response = await fetch(`${MODULE_PREFIX}/admin/notifications`);
            this.htmlContent = await response.text();
            this.hasLoaded = true;
        } catch (error) {
            this.htmlContent =
                '<p class="p-4 text-center text-red-500">Gagal memuat notifikasi.</p>';
        } finally {
            this.isLoading = false;
        }
    },
    addNotification(notification) {
        if (notification.unread_count) {
            this.count = notification.unread_count;
        } else {
            this.count++;
        }
        this.hasLoaded = false;
        this.htmlContent = "";
        Alpine.store("toast").show(notification.message, "message");
    },
};
