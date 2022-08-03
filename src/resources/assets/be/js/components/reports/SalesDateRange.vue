<template>
    <div>
        <div id="heading11" class="card-header">
            <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion11" aria-expanded="false" aria-controls="accordion11"
                class="card-title lead collapsed">Sales by date range</a>
        </div>
        <div id="accordion11" role="tabpanel" aria-labelledby="heading11" class="collapse show">
            <div class="card-content">
                <div class="card-body">
                    <!--  <b>Report description:</b> Displays a sales list from a range of dates. -->
                    <form class="form form-horizontal">
                        <div class="form-body">
                            <h5 class="form-section">Displays a sales list from a range of dates.</h5>
                            <div class="form-group row">
                                <label class="col-md-3 label-control" for="projectinput1">Start date</label>
                                <div class="col-md-4">
                                    <input type='text' v-model="start_date" id="start_date" class="form-control" placeholder="Select start date" v-bind:class="{'input-error-select' : error.start_date}"
                                    />
                                    <span v-if="error.start_date" class="message-error">{{error_message.start_date}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 label-control" for="projectinput2">End date</label>
                                <div class="col-md-4">
                                    <input type='text' v-model="end_date" id="end_date" class="form-control" placeholder="Select end date" v-bind:class="{'input-error-select' : error.end_date}"
                                    />
                                    <span v-if="error.end_date" class="message-error">{{error_message.end_date}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right">
                            <button @click="getReport()" type="button" class="btn btn-primary">
                                <i class="fa fa-check-square-o"></i> Get Report
                            </button>
                           <!--  <button type="button" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> Cancel
                            </button> -->
                        </div>
                        <div v-if="show_result" id="card-orders" class="card">
                            <div class="card-header" style="padding-bottom:5px;">
                                <h4 class="card-title">Orders in this range of dates</h4>
                                <!-- <a class="heading-elements-toggle">
                                    <i class="fa fa-ellipsis-v font-medium-3"></i>
                                </a> -->
                            </div>
                            <div class="text-right" style="margin-bottom:8px;">
                                <img @click="print()" class="icons-custom" src="/images/icons/print.png" style="margin-right:5px;cursor:pointer;">
                                <img @click="getReport(true)" class="icons-custom" src="/images/icons/excel.png" style="margin-right:5px;cursor:pointer;">
                            </div>
                            <div class="card-content" style="overflow-y: scroll">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="recent-orders" class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Order number</th>
                                                    <th>Customer Name / Email</th>
                                                    <th>Origin</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Order Placed At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(order, index) in orders" :key="index">
                                                    <td class="text-truncate">{{order.order_number}}</td>
                                                    <td class="text-truncate">
                                                        <a v-if="order.customer">{{order.customer.firstname}}  {{order.customer.lastname}}
                                                            <div style="font-size: 12px">
                                                                <b>{{ order.customer.email}}</b>
                                                            </div>
                                                        </a>
                                                        <a v-else>{{order.customer_name}}
                                                            <div style="font-size: 12px">
                                                                <b>{{ order.customer_email}}</b>
                                                            </div>
                                                        </a>
                                                    </td>
                                                    <td class="text-truncate">
                                                        <span>
                                                            <img :src="'/images/marketplace/' + order.origin + '.png'" :class="{'icon-store' : order.origin == 'store', 'icon-amazon' : order.origin == 'amazon'}">
                                                        </span>
                                                    </td>
                                                    <td class="text-truncate">$ {{order.paid_amount}}</td>
                                                    <td class="text-truncate">{{order.status}}</td>
                                                    <td class="text-truncate">{{moment(order.created_at, 'YYYY-MM-DD HH:mm:ss').format('MMM DD,YYYY   HH:mm')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            <div style="padding: 20px"><h4>{{no_found_msg}}</h4></div>
                        </div>
                        <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getReport()" :offset="offset"></order-paginator>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        components: {
            'order-paginator': Paginator
        },
        data() {
            return {
                moment:moment,
                start_date: '',
                end_date: '',
                start_datepicker: {},
                end_datepicker: {},
                orders: [],
                error: {
                    start_date: false,
                    end_date: false,
                },
                error_message: {
                    start_date: '',
                    end_date: ''
                },
                offset:10,
                show_result: false,
                no_found_msg: '',
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
            }
        },
        methods: {
            getReport(exportData = false) {
                if (this.start_date != '' && this.end_date != '') {
                    var data = {
                        start_date: this.start_date,
                        end_date: this.end_date,
                        export: exportData,
                        limit:this.offset,
                        where: ['status', '<>', 'cart'],
                        page:this.paginator.current_page
                    }
                    $.LoadingOverlay("show");
                    axios({
                        method: 'post',
                        url: '/api/admin/reports/sales-date-range',
                        data: data
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            if(response.data.data.exported){
                                swal("Success!", "The report is being exported and will be sent to your email.", "success");
                            }else{
                                this.orders = response.data.data;
                                if (this.orders.length > 0) {
                                    this.show_result = true;
                                    this.paginator = response.data.pagination;
                                } else {
                                    this.show_result = false;
                                    this.paginator= {
                                        total: 0,
                                        per_page: 2,
                                        from: 1,
                                        to: 0,
                                        current_page: 1
                                    };
                                    this.no_found_msg = 'There are no sales in this range of dates';
                                }
                            }
                        } else {
                            this.error.start_date = true;
                            this.error.end_date = true;
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", 'Date format is incorrect', "error");
                    });
                } else {
                    if (this.start_date == '') {
                        this.error.start_date = true;
                        this.error_message.start_date = 'Start date is required';
                    }
                    if (this.end_date == '') {
                        this.error.end_date = true;
                        this.error_message.end_date = 'End date is required';
                    }
                }
            },
            print(){
                var data = {
                        start_date: this.start_date,
                        end_date: this.end_date,
                        limit:1000,
                        where: ['status', 'paid'],
                        page:1
                    }
                    $.LoadingOverlay("show");
                    axios({
                        method: 'post',
                        url: '/api/admin/reports/sales-date-range',
                        data: data
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            printJS({
                                printable: response.data.data, 
                                properties: ['order_number', 'customer_name', 'customer_email','origin','paid_amount','status','created_at'], 
                                type: 'json',
                                header: 'Orders between ('+ this.start_date + '-'+ this.end_date +')',
                                gridHeaderStyle: 'border-bottom: 2px solid #98A4B8;text-weight:bold;',
                                gridStyle: 'border-bottom: 1px solid #98A4B8;padding:10px; text-align:center;'})
                        }
                    }).catch((error) => {
                        swal("Error!", 'Date format is incorrect', "error");
                    });
                // printJS({ printable: 'recent-orders', type: 'html', header: 'Orders between ('+ this.start_date + '-'+ this.end_date +')'});
            }
        },
        created() {

        },
        mounted() {
            
            this.$nextTick(function () {
                var self = this;

                this.start_datepicker = $('#start_date').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    self.error.start_date = false;
                    self.start_date = e.date.format('MM/DD/YYYY hh:mm A');
                });

                this.end_datepicker = $('#end_date').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    self.error.end_date = false;
                    self.end_date = e.date.format('MM/DD/YYYY hh:mm A');
                });
            });
        }
    }
</script>
<style>
    .icon-store {
        width: 30px;
        height: auto;
    }

    .icon-amazon {
        width: 50px;
        height: auto;
    }

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
    .icons-custom{
        width:20px;
        height:20px;
    }
</style>