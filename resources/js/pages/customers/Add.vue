
<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Add New Customer</h3>
            </div>
            <div class="panel-body">
              	<!-- LOAD VIEW DARI FORM.VUE -->
                <customer-form></customer-form>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
                        <i class="fa fa-save"></i> Add New
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import FormCustomer from './Form.vue'
    export default {
        name: 'AddCustomer',
        data() {
            return {
                
            }
        },
        methods: {
            ...mapActions('customer', ['submitCustomer']),
            //KETIKA TOMBOL DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
            submit() {
                //MELAKUKAN REQUEST KE SERVER UNTUK MENAMBAHKAN DATA
                this.submitCustomer().then(() => {
                    //KEMUDIAN REDIRECT KE HALAMAN LIST CUSTOMERS
                    this.$router.push({ name: 'customers.data' })
                })
            }
        },
        components: {
            'customer-form': FormCustomer //MEMBUAT CUSTOM TAG UNTUK ME-LOAD FILE FORM.VUE
        }
    }
</script>