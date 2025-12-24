import { defineStore } from "pinia";

export const useStaff = defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
    }),

    getters: {
        getter: (state) => state.user,
        getToken: (state) => state.token
    },

    actions: {
        setUser(new_user) {
            this.user = new_user
        },

        setToken(new_token) {
            this.token = new_token
        },

        logOut() {
            this.user = null;
            this.token = null;
        }
    }

});