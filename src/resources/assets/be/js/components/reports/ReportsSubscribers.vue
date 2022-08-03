<template>
    <div class="card-body">
        <div id="accordionWrap1" role="tablist" aria-multiselectable="true" style="width: 100%">
            <div class="card collapse-icon accordion-icon-rotate" style="height: 100%">
                <subscribers-list></subscribers-list>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriberList from './SubscribersList.vue'
    export default {
        components:{
            'subscribers-list': SubscriberList
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