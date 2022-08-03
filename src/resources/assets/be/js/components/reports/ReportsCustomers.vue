<template>
    <div class="card-body">
        <div id="accordionWrap1" role="tablist" aria-multiselectable="true" style="width: 100%">
            <div class="card collapse-icon accordion-icon-rotate" style="height: 100%">
                <customer-list></customer-list>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerList from './CustomersList.vue'
    export default {
        components:{
            'customer-list': CustomerList
        },
        data() {
            return {

            }
        },
        methods: {

        },
        created() {

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style>
</style>