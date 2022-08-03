<template>
    <div class="row">
        <div class="col-md-12">
            <h3>Settings</h3>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-cog" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/general">General</a>
                                <p style="margin:0;">View and update your store details</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-file" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/invoicing">Invoicing</a>
                                <p style="margin:0;">Custom your order numbers</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-clipboard" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/taxes">Taxes</a>
                                <p style="margin:0;">Manage how you charge taxes</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-list-alt" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/inventory">Inventory</a>
                                <p style="margin:0;">Inventory management</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-truck" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/shipping">Shipping</a>
                                <p style="margin:0;">Manage how you ship orders to customers</p>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:15px;">
                            <div style="display:inline-block; padding-right:8px;">
                                <i class="fa fa-clipboard" style="color:#404E67; font-size:22px;"></i>
                            </div>
                            <div style="display:inline-block">
                                <a href="/admin/settings/communications-page">Content management</a>
                                <p style="margin:0;">Manage the content of your website</p>
                            </div>
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