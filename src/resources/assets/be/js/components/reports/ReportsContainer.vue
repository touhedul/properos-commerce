<template>
    <div class="row">
        <div class="col-md-12">
            <h3>Reports</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="padding-bottom:0px;">
                            <h4>Sales</h4>
                        </div>
                        <div class="card-body">
                            <a href="/admin/store/reports/sales">Sale by date range</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="padding-bottom:0px;">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <a href="/admin/store/reports/customers">Customers</a>
                            </div>
                            <hr>
                            <div>
                                <a href="/admin/store/reports/subscribers">Newsletter subscribers</a>
                            </div>
                            <hr>
                            <div>
                                <a href="/admin/store/reports/activities-log">Activities Log</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="padding-bottom:0px;">
                            <h4>Inventory</h4>
                        </div>
                        <div class="card-body">
                            <a href="/admin/store/reports/products-sold">Products Sold</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
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