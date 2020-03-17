import Vue from "vue";
import Vuex from "vuex";

import auth from "./stores/auth.js";
import outlet from "./stores/outlet.js";
import courier from "./stores/courier.js";
import product from "./stores/product.js";

Vue.use(Vuex);

// define route store vuex
const store = new Vuex.Store({
    // semua module yang dibuat akan ditetapkan didalam bagian ini dan dipisahkan dengan koma untuk setipa module-nya
    modules: {
        auth, outlet, courier, product
    },


    //STATE HAMPIR SERUPA DENGAN PROPERTY DATA DARI COMPONENT HANYA SAJA DAPAT DIGUNAKAN SECARA GLOBAL
    state: {
        token: localStorage.getItem('token'),
        errors: []
    },

    getters: {
        // membuat sebuah getters dg nama isAuth , dimana getters ini akan bernilai true or false berdasarkan state token
        isAuth: state => {
            return state.token != "null" && state.token != null
        }
    },
    mutations: {
        // sebuah mutations yg berfungsi untuk memanipulasi value dari state token

        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    }


});


export default store;