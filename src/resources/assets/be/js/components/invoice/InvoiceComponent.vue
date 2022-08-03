<template>
    <div>

    </div>
</template>

<script>
    export default {
        props: ['order'],
        data() {
            return {
                error: {
                    upc: false,
                    name: false,
                    description: false,
                    total_qty: false,
                    price: false,
                    asin: false,
                    picture: false,
                    width: false,
                    height: false,
                    weight: false,
                    length: false
                },
                error_message: {
                    upc: '',
                    name: '',
                    description: '',
                    total_qty: '',
                    price: '',
                    asin: '',
                    picture: '',
                    width: '',
                    height: '',
                    weight: '',
                    length: ''
                }
            }
        },
        methods: {

        },
        watch: {

        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {

        },
        mounted() {

        }
    }
</script>
<style>
    
</style>