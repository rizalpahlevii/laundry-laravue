import $axios from "../api.js";

const state = () => ({

});

const mutations = {

}

const actions = {
    submit({ commit }, payload) {
        // reset local token menjadi null
        localStorage.setItem('token', null);

        // reset state token menjadi null
        commit('SET_TOKEN', null, { root: true });
        //KARENA MUTATIONS SET_TOKEN BERADA PADA ROOT STORES, MAKA DITAMBAHKAN PARAMETER { root: true }


        // menggunakan promise agar fungsi selanjutnya berjalan ketika fungsi ini selesai


        return new Promise((resolve, reject) => {
            // mengirim request ke server  dengan uri /login dan payload adalah data yang dikirimkan dari component login.vue
            $axios.post('/login', payload)
                .then((response) => {
                    if (response.data.status == 'success') {
                        // set token on local storage and state 
                        localStorage.setItem('token', response.data.data);
                        commit('SET_TOKEN', response.data.data, { root: true });
                    } else {
                        commit('SET_ERRORS', { invalid: 'Email/Password salah' }, { root: true });
                    }
                    // resolve agar dianggap selesai
                    resolve(response.data);
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        commit('SET_ERRORS', error.response.data.errors, { root: true });
                    }
                });
        });

    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations
}