import $axios from "../api.js";

const state = () => ({
    users: [],
    roles: [],
    permissions: [],
    role_permission: [],
    authenticated: []
});

const mutations = {
    ASSIGN_USER(state, payload) {
        state.users = payload;
    },
    ASSIGN_ROLES(state, payload) {
        state.roles = payload;
    },
    ASSIGN_PERMISSION(state, payload) {
        state.permissions = payload;
    },
    ASSIGN_ROLE_PERMISSION(state, payload) {
        state.role_permission = payload;
    },
    CLEAR_ROLE_PERMISSION(state, payload) {
        state.role_permission = [];
    },
    ASSIGN_USER_AUTH(state, payload) {
        state.authenticated = payload;
    }
};

const actions = {
    getUserLists({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/user-lists`).then(response => {
                commit("ASSIGN_USER", response.data.data);
                resolve(response.data);
            });
        });
    },
    // function to set role each user
    setRoleUser({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit("CLEAR_ERRORS", "", { root: true });
            $axios
                .post(`/set-role-user`, payload)
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },

    getRoles({ commit }) {
        return new Promise((resolve, reject) => {
            //KIRIM PERMINTAAN KE BACKEND
            $axios.get(`/roles`).then(response => {
                //SIMPAN DATANYA KE DALAM STATE ROLES
                commit("ASSIGN_ROLES", response.data.data);
                resolve(response.data);
            });
        });
    },

    getAllPermission({ commit }) {
        return new Promise((resolve, reject) => {
            //KIRIM PERMINTAAN KE BACKEND
            $axios.get(`/permissions`).then(response => {
                //SIMPAN DATA YANG DITERIMA KE DALAM STATE PERMISSIONS
                commit("ASSIGN_PERMISSION", response.data.data);
                resolve(response.data);
            });
        });
    },

    getRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit("CLEAR_ERRORS", "", { root: true }); //BERSIHKAN STATE ERRORS
            //KIRIM PERMINTAAN KE BACKEND BERDASARKAN ROLE_ID
            $axios
                .post(`/role-permission`, { role_id: payload })
                .then(response => {
                    //SIMPAN DATANYA DENGAN MUTATIONS
                    commit("ASSIGN_ROLE_PERMISSION", response.data.data);
                    resolve(response.data);
                });
        });
    },

    setRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit("CLEAR_ERRORS", "", { root: true });
            //KIRIM PERMINTAAN KE BACKEND
            $axios
                .post(`/set-role-permission`, payload)
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    //APABILA TERJADI ERROR VALIDASI
                    if (error.response.status == 422) {
                        //SET ERRORNYA AGAR DAPAT DITAMPILKAN
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },

    getUserLogin({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`user-authenticated`).then(response => {
                //SIMPAN DATA USER TERSEBUT
                commit("ASSIGN_USER_AUTH", response.data.data);
                resolve(response.data);
            });
        });
    }
};

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
