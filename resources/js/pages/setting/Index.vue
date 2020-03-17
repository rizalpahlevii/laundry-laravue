<template>
    <div class="container">
        <section class="content-header">
            <h1>Setting Page</h1>
            <breadcrumb></breadcrumb>
        </section>
        <div class="content">
            <div class="row">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>
import Breadcrumb from "../../components/Breadcrumb.vue";
export default {
    name : 'IndexSetting',
    components : {
        'breadcrumb':Breadcrumb
    }
}
</script>