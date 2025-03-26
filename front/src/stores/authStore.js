import { defineStore } from "pinia";
import { authService } from "@/services/authService";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    isLoggedIn: false,
    user: null,
    token: null,
    tokenExpiration: null,
    error: null,
  }),

  actions: {
    async register(userData) {
      try {
        this.error = null;
        await authService.register(userData);
        await this.login({
          email: userData.email,
          password: userData.password,
        });
        return true;
      } catch (error) {
        this.error = error.message;
        throw error;
      }
    },

    async login(credentials) {
      try {
        this.error = null;
        const response = await authService.login(credentials);

        if (response.success && response.data) {
          const decodedToken = this.decodeJWT(response.data.accessToken);

          this.token = response.data.accessToken;
          this.user = {
            id: decodedToken.id,
            email: decodedToken.email,
            role: decodedToken.role,
          };
          this.isLoggedIn = true;
          this.tokenExpiration = decodedToken.exp * 1000; 

          localStorage.setItem("token", this.token);
          localStorage.setItem("user", JSON.stringify(this.user));
          localStorage.setItem("tokenExpiration", this.tokenExpiration);

          return true;
        }
        return false;
      } catch (error) {
        this.error = error.message;
        throw error;
      }
    },

    decodeJWT(token) {
      try {
        if (!token) return null;
        const base64Url = token.split(".")[1];
        const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
        const jsonPayload = decodeURIComponent(
          atob(base64)
            .split("")
            .map((c) => {
              return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
            })
            .join("")
        );
        return JSON.parse(jsonPayload);
      } catch (error) {
        console.error("Erreur lors du décodage du token:", error);
        return null;
      }
    },

    isUserLoggedIn() {
      return this.isLoggedIn;
    },

    logout() {
      this.isLoggedIn = false;
      this.user = null;
      this.token = null;
      this.tokenExpiration = null;
      this.error = null;

      localStorage.removeItem("token");
      localStorage.removeItem("user");
      localStorage.removeItem("tokenExpiration");
    },

    initializeStore() {
      const token = localStorage.getItem("token");
      const user = localStorage.getItem("user");
      const tokenExpiration = localStorage.getItem("tokenExpiration");

      if (token && user && tokenExpiration) {
        const now = new Date().getTime();
        if (now < tokenExpiration) {
          this.token = token;
          this.user = JSON.parse(user);
          this.isLoggedIn = true;
          this.tokenExpiration = tokenExpiration;
        } else {
          this.logout();
        }
      }
    },
  },
});
