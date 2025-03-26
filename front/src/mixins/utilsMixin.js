import { useAuthStore } from "@/stores/authStore";

export default {
    data() {
        return {
            authStore: useAuthStore(),
        };
    },
    methods: {
        checkUserRole() {
            if (this.authStore.isUserLoggedIn()) {
                const role = this.authStore.user.role;
                return role === 15 || role === 10;
            }
            return false;
        },
        getRoleLabel(role) {
            switch (role) {
                case 0: return "Adhérent";
                case 5: return "Encadrant";
                case 10: return "Responsable";
                case 15: return "Administrateur";
                default: return "Rôle inconnu";
            }
        },
    },
};
