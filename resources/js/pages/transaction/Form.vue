<template>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" :class="{ 'has-error': errors.customer_id }">
                <label for="">Customer</label>

                <!-- KITA AKAN MENGGUNAKAN V-SELECT DIMANA DATANYA AKAN DILOAD KETIKA KEYWORD PENCARIAN DITEMUKAN -->
                <v-select :options="customers.data"
                    v-model="transactions.customer_id"
                    @search="onSearch" 
                    label="name"
                    placeholder="Masukkan Kata Kunci" 
                    :filterable="false">
                    <template slot="no-options">
                        Masukkan Kata Kunci
                    </template>
                    <template slot="option" slot-scope="option">
                        {{ option.name }}
                    </template>
                </v-select>
                <p class="text-danger" v-if="errors.customer_id">{{ errors.customer_id[0] }}</p>
            </div>
        </div>
      	<!-- BAGIAN INI AKAN MENAMPILKAN DETAIL CUSTOMER JIKA ISFORM = FALSE, JIKA TRUE, PADA ARTIKEL SELANJUTNYA AKAN DIBUAT FORM UNTUK MENAMBAHKAN CUSTOMER BARU -->
        <div class="col-md-6" v-if="transactions.customer_id != null && !isForm">
            <table>
                <tr>
                    <th width="30%">NIK </th>
                    <td width="5%">:</td>
                    <td>{{ transactions.customer_id.nik }}</td>
                </tr>
                <tr>
                    <th>No Telp </th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.phone }}</td>
                </tr>
                <tr>
                    <th>Alamat </th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.address }}</td>
                </tr>
                <tr>
                    <th>Deposit </th>
                    <td>:</td>
                    <td>Rp {{ transactions.customer_id.deposit }}</td>
                </tr>
                <tr>
                    <th>Point </th>
                    <td>:</td>
                    <td>{{ transactions.customer_id.point }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <hr>
            <button class="btn btn-warning btn-sm" style="margin-bottom: 10px" @click="addProduct">Tambah</button>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="40%">Paket</th>
                            <th>Berat/Satuan</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                  	<!-- TABLE INI BERGUNA UNTUK MENAMBAHKAN ITEM TRANSAKSI -->
                    <tbody>
                        <tr v-for="(row, index) in transactions.detail" :key="index">
                            <td>
                                <v-select :options="products.data"
                                    v-model="row.laundry_price"
                                    @search="onSearchProduct" 
                                    label="name"
                                    placeholder="Masukkan Kata Kunci" 
                                    :filterable="false">
                                    <template slot="no-options">
                                        Masukkan Kata Kunci
                                    </template>
                                    <template slot="option" slot-scope="option">
                                        {{ option.name }}
                                    </template>
                                </v-select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" v-model="row.qty" class="form-control" @blur="calculate(index)">
                                    <span class="input-group-addon">{{ row.laundry_price != null && row.laundry_price.unit_type == 'Kilogram' ? 'gram':'pcs' }}</span>
                                </div>
                                
                            </td>
                            <td>Rp {{ row.price }}</td>
                            <td>Rp {{ row.subtotal }}</td>
                            <td>
                                <button class="btn btn-danger btn-flat" @click="removeProduct(index)">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      
        <!-- KETIKA TRANSAKSI BERHASIL, ALERTNYA DITAMPILKAN -->
        <div class="col-md-12" v-if="isSuccess">
            <div class="alert alert-success">
                Transaksi Berhasil, Total Tagihan: Rp {{ total }}
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import _ from 'lodash'

export default {
    name: 'FormTransaction',
    data() {
        return {
            isForm: false,
            isSuccess: false,
            transactions: {
                customer_id: null,
                //KITA SET DEFAULT DETAILNYA 1 ITEM YANG KOSONG 
                detail: [
                    { laundry_price: null, qty: 1, price: 0, subtotal: 0 }
                ]
            }
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('transaction', {
            customers: state => state.customers, //GET STATE CUSTOMER DARI MODULE TRANSACTION
            products: state => state.products //GET STATE PRODUCT DARI MODULE TRANSACTION
        }),
        total() {
            //MENJUMLAH SUBTOTAL 
            return _.sumBy(this.transactions.detail, function(o) {
                return o.subtotal
            })
        }
    },
    methods: {
        ...mapActions('transaction', ['getCustomers', 'getProducts', 'createTransaction']),
        //METHOD INI AKAN BERJALAN KETIKA PENCARIAN DATA CUSTOMER PADA V-SELECT DIATAS
        onSearch(search, loading) {
            //KITA AKAN ME-REQUEST DATA CUSTOMER BERDASARKAN KEYWORD YG DIMINTA
            this.getCustomers({
                search: search,
                loading: loading
            })
        },
        //METHOD INI UNTUK PENCARIAN DATA PRODUK UNTUK ITEM LAUNDRY
        onSearchProduct(search, loading) {
            //ME-REQUEST DATA PRODUCT
            this.getProducts({
                search: search,
                loading: loading
            })
        },
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addProduct() {
            this.transactions.detail.push({ laundry_price: null, qty: null, price: 0, subtotal: 0 })
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removeProduct(index) {
            if (this.transactions.detail.length > 1) {
                this.transactions.detail.splice(index, 1)
            }
        },
        //KETIKA INPTAN QTY / BERAT /SATUAN UN-FOCUS, MAKA FUNGSI INI AKAN DIJALANKAN
        calculate(index) {
            let data = this.transactions.detail[index]
            if (data.laundry_price != null) {
                //DIMANA KITA AKAN MENGISI PRICE UNTUK SETIAP ITEMNYA DAN PRICENYA DIDAPATKAN DARI DATA PRODUCT LAUNDRY
                data.price = data.laundry_price.price
                //ADAPUN SUBTOTAL AKAN DIHITUNG BERDASARKAN JENISNYA
                if (data.laundry_price.unit_type == 'Kilogram') {
                    //JIKA KILOGRAM MAKA BERAT BARANG * HARGA /1000
                    data.subtotal = (parseInt(data.laundry_price.price) * (parseInt(data.qty) / parseInt(1000))).toFixed(2)
                } else {
                    //JIKA SATUAN, MAKA HARGA * QTY
                    data.subtotal = parseInt(data.laundry_price.price) * parseInt(data.qty)
                }
            }
        },
        //KETIKA TOMBOL CREATE TRANSACTION DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
        submit() {
            this.isSuccess = false
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENYIMPAN DATA TRANSAKSI
            this.createTransaction(this.transactions).then(() => this.isSuccess = true)
        }
    },
    components: {
        vSelect
    }
}
</script>