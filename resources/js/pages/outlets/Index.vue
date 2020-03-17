<template>
    <div class="container">
        <section class="content-header">
            <h1>
                Manage Outlets
            </h1>
            <breadcrumb></breadcrumb>
        </section>

        <section class="content">
            <div class="row">
                <router-view></router-view>
            </div>
        </section>
    </div>
</template>
<script>
import BreadCrumb from "../../components/Breadcrumb.vue";
export default {
    name : 'indexOutlet',
    components : {
        'breadcrumb':BreadCrumb
    }
}
</script>