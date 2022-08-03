<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">General</h4>
                            </div>
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                                <store-location></store-location>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import SettingServices from '../../services/SettingServices.js';
    // import OrdersServices from '../../services/OrdersServices';
    // import Paginator from '../../../../../../components/Paginator.vue'
    import StoreLocation from './StoreLocationComponent.vue'
    export default {
        // mixins:[SettingServices, OrdersServices],
        components: {
            // 'paginator': Paginator,
            'store-location': StoreLocation,
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
        }
    }
</script>
<style>

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }
</style>