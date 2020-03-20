<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <router-link
                    :to="{ name: 'products.add' }"
                    class="btn btn-primary btn-sm btn-flat"
                    >Tambah</router-link
                >
                <div class="pull-right">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <div class="panel-body">
                <b-table
                    striped
                    hover
                    bordered
                    :items="products.data"
                    :fields="fields"
                    show-empty
                >
                    <template v-slot:cell(laundry_type)="row">{{
                        row.item.type.name
                    }}</template>
                    <template v-slot:cell(user_id)="row">{{
                        row.item.user.name
                    }}</template>
                    <template v-slot:cell(service)="row">
                        {{ row.item.service }} {{ row.item.service_type }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link
                            :to="{
                                name: 'products.edit',
                                params: { id: row.item.id }
                            }"
                            class="btn btn-warning btn-sm"
                        >
                            <i class="fa fa-pencil"></i>
                        </router-link>
                        <button
                            class="btn btn-danger btn-sm"
                            @click="deleteProduct(row.item.id)"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="products.data">
                            <i class="fa fa-bars"></i>
                            {{ products.data.length }} item dari
                            {{ products.meta.total }} total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="products.meta.total"
                                :per-page="products.meta.per_page"
                                aria-controls="products"
                                v-if="products.data && products.data.length > 0"
                            ></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
    name: "DataCourier",
    created() {
        this.getProducts(); //MELAKUKAN REQUEST KETIKA COMPONENT DI-LOAD
    },
    data() {
        return {
            //FIELDS UNTUK MENGISI HEADER TABLE YANG AKAN DITAMPILKAN
            fields: [
                { key: "name", label: "Nama Item" },
                { key: "unit_type", label: "Tipe" },
                { key: "laundry_type", label: "Jenis Jasa" },
                { key: "price", label: "Harga" },
                { key: "user_id", label: "Admin" },
                { key: "service", label: "Lama Pengerjaan" },
                { key: "actions", label: "Aksi" }
            ],
            //VARIABLE UNTUK FORM SEARCH
            search: ""
        };
    },
    computed: {
        //ME-LOAD STATE DARI MODULE PRODUCTS
        ...mapState("product", {
            products: state => state.products //STATE PRODUCTS
        }),
        page: {
            get() {
                return this.$store.state.product.page; //LOAD STATE PAGE
            },
            set(val) {
                this.$store.commit("product/SET_PAGE", val); //SET STATE PAGE KETIKA VALUE BERUBAH
            }
        }
    },
    watch: {
        //KETIKA TERJADI PERUBAHAN VALUE DARI PAGE
        page() {
            this.getProducts(); //AMBIL DATA TERBARU
        },
        //KETIKA TERJADI PERUBAHAN VALUE DARI SEARCH
        search() {
            this.getProducts(this.search); //AMBIL DATA TERBARU BERDASARKAN VALUE SEARC
        }
    },
    methods: {
        ...mapActions("product", ["getProducts", "removeProduct"]), //LOAD ACTIONS DARI MODULE PRODUCT
        //FUNGSI UNTUK MENG-HANDLE TOMBOL HAPUS PRODUCT
        deleteProduct(id) {
            this.$swal({
                title: "Kamu Yakin?",
                text: "Tindakan ini akan menghapus secara permanent!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya, Lanjutkan!"
            }).then(result => {
                if (result.value) {
                    this.removeProduct(id); //KETIKA YES MAKA FUNGSI INI AKAN DIJALANKAN
                }
            });
        }
    }
};
</script>
