import $axios from "../api.js";

const state = () => ({
    couriers: [],
    page: 1,
    id: ""
});

const mutations = {
    // assign data to state
    ASSIGN_DATA(state, payload) {
        state.couriers = payload;
    },

    // change state page
    SET_PAGE(state, payload) {
        state.page = payload;
    },

    // change  state id

    SET_ID_UPDATE(state, payload) {
        state.id = payload;
    }
};

const actions = {
    getCouriers({ commit, state }, payload) {
        let search = typeof payload != "undefined" ? payload : "";
        return new Promise((resolve, reject) => {
            $axios
                .get(`/couriers?page${state.page}%q=${search}`)
                .then(response => {
                    commit("ASSIGN_DATA", response.data);
                    resolve(response.data);
                });
        });
    },

    submitCourier({ dispatch, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .post("/couriers", payload, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    dispatch("getCouriers").then(() => {
                        resolve(response.data);
                    });
                })
                .catch(error => {
                    if (error.response.data == 422) {
                        commit("SET_ERRORS", error.response.data.errors, {
                            root: true
                        });
                    }
                });
        });
    },

    editCourier({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/couriers/${payload}/edit`).then(response => {
                resolve(response.data);
            });
        });
    },

    updateCourier({ state }, payload) {
        return new Promise((resolve, reject) => {
            $axios
                .post(`/couriers/${state.id}`, payload, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    resolve(response.data);
                });
        });
    },

    removeCourier({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN PERMINTAAN KE SERVER DENGAN METHOD DELETE DAN MENGIRIMKAN ID YANG AKAN DIHAPUS
            $axios.delete(`/couriers/${payload}`).then(response => {
                //MENGAMBIL DATA TERBARU DARI SERVER
                dispatch("getCouriers").then(() => resolve(response.data));
            });
        });
    }
};

export default { namespaced: true, state, actions, mutations };
