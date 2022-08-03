<template>
    <div>
        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="/admin/store/items">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-primary bg-darken-2">
                                    <i class="ft-box font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-primary white media-body">
                                    <h5>Products</h5>
                                    <h2 class="text-bold-400 mb-0">
                                        {{total_items}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="/admin/store/categories">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-danger bg-darken-2">
                                    <i class="icon-user font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-danger white media-body">
                                    <h5>Active Categories</h5>
                                    <h2 class="text-bold-400 mb-0">
                                        {{active_categories}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <a href="/admin/store/orders#new">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-warning bg-darken-2">
                                    <i class="ft-file-text font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-warning white media-body">
                                    <h5>New Orders</h5>
                                    <h2 class="text-bold-400 mb-0">
                                        {{new_orders}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-success bg-darken-2">
                                <i class="icon-wallet font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>Total Profit</h5>
                                <h2 class="text-bold-400 mb-0">
                                    ${{parseFloat(total_profit).toFixed(2)}}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Stats -->
        <!--Product sale & buyers -->
        <div class="row match-height">
            <div class="col-xl-8 col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Monthly sales</h4>
                        <a class="heading-elements-toggle">
                            <i class="fa fa-ellipsis-v font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a data-action="reload">
                                        <i class="ft-rotate-cw"></i>
                                    </a>
                                </li>
                                <li>
                                    <a data-action="expand">
                                        <i class="ft-maximize"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body" style="padding-top:0;">
                            <div class="row my-1">
                                <div class="col-lg-6 col-12">
                                    <div class="text-center">
                                        <p class="text-muted" style="margin-bottom:0;">Period Revenue</p>
                                        <h4 style="color:#000; font-weight:bold;">${{getValue(monthly_sales, 'annual', 0).toFixed(2)}}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="text-center">
                                        <p class="text-muted" style="margin-bottom:0;">Period Orders</p>
                                        <h4 style="color:#000; font-weight:bold;">{{monthly_orders}}</h4>
                                    </div>
                                </div>
                            </div>
                            <div v-if="monthly_sales" id="monthly-sales1" class="height-300"></div>
                            <!-- <div v-if="monthly_sales" class="row">
                                <div class="col-12">
                                    <div class="chartjs">
                                        <canvas ref="monthly-sales" height="300"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <div v-else style="text-align: center;">
                                <h3>There are no sales since January</h3>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body sales-growth-chart">
                            <h4 class="card-title">Popular products</h4>
                            <div v-if="popular_products.length > 0" class="table-responsive">
                                <table id="popular-products" class="table table-hover mb-0">
                                     <thead>
                                        <tr>
                                            <th style="border-color: #fff;"></th>
                                            <th style="border-color: #fff;text-align: center;">Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(popular_product, index) in popular_products" :key="index">
                                            <td class="text-truncate" style="border-color: #fff;">{{popular_product.name}}</td>
                                            <td class="text-truncate" style="text-align: center;border-color: #fff;">{{popular_product.count}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="table-responsive">
                                <div style="text-align: center">
                                    <h3>No popular products</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="monthly_sales" class="card-footer">
                        <div class="chart-title mb-1 text-center">
                            <h6>Most selled products since January</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Product sale & buyers -->
        <!--Recent Orders & Monthly Salse -->
        <div class="row match-height">
            <div class="col-xl-8 col-lg-12">
                <div id="card-orders" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Orders</h4>
                        <a class="heading-elements-toggle">
                            <i class="fa fa-ellipsis-v font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a @click="updateRecentOrders()" data-action="reload">
                                        <i class="ft-rotate-cw"></i>
                                    </a>
                                </li>
                                <li>
                                    <a data-action="expand">
                                        <i class="ft-maximize"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content" style="overflow-y: scroll">
                        <div class="card-body">
                            <!-- <p>Total paid invoices 240, unpaid 150.
                                <span class="float-right">
                                    <a href="project-summary.html" target="_blank">Invoice Summary
                                        <i class="ft-arrow-right"></i>
                                    </a>
                                </span>
                            </p> -->

                            <div v-if="current_recent_orders.length > 0" class="table-responsive">
                                <table id="recent-orders" class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order number</th>
                                            <th>Customer Name / Email</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Order Placed At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(current_recent_order, index) in current_recent_orders" :key="index">
                                            <td class="text-truncate">{{current_recent_order.order_number}}</td>
                                            <td class="text-truncate">
                                                <a v-if="current_recent_order.customer">{{current_recent_order.customer.name}}
                                                    <div style="font-size: 12px">
                                                        <b>{{ current_recent_order.customer.email}}</b>
                                                    </div>
                                                </a>
                                                <a v-else>{{current_recent_order.customer_name}}
                                                    <div style="font-size: 12px">
                                                        <b>{{ current_recent_order.customer_email}}</b>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="text-truncate">
                                                <span v-if="current_recent_order.status == 'paid'" class="badge badge-default badge-primary" style="text-transform:capitalize;"><i class="fa fa-money" style="padding-right:3px;"></i>{{current_recent_order.status}}</span>
                                                <span v-else-if="current_recent_order.status == 'pay__error' || current_recent_order.status == 'cancelled' || current_recent_order.status == 'refunded'" class="badge badge-default badge-danger" style="text-transform:capitalize;"><i class="fa fa-money" style="padding-right:3px;"></i>{{(current_recent_order.status == 'pay__error') ? 'Error' : current_recent_order.status}}</span>
                                                <span v-else-if="current_recent_order.status == 'pending'" class="badge badge-default badge-warning" style="text-transform:capitalize;"><i class="fa fa-money" style="padding-right:3px;"></i>{{current_recent_order.status}}</span>
                                                <!-- <br> -->
                                                <span v-if="current_recent_order.shipping_status == 'shipped' || current_recent_order.shipping_status == 'fullfiling' || current_recent_order.shipping_status == 'fulfilled' || current_recent_order.shipping_status == 'delivered'" class="badge badge-default badge-primary" style="text-transform:capitalize;"><i class="fa fa-truck" style="padding-right:3px;"></i>{{current_recent_order.shipping_status}}</span>
                                                <span v-else-if="current_recent_order.shipping_status == 'pending'" class="badge badge-default badge-warning" style="text-transform:capitalize;"><i class="fa fa-truck" style="padding-right:3px;"></i>{{current_recent_order.shipping_status}}</span>
                                                <span v-else-if="current_recent_order.shipping_status == 'error' || current_recent_order.shipping_status == 'cancelled' || current_recent_order.shipping_status == 'returned'" class="badge badge-default badge-danger" style="text-transform:capitalize;"><i class="fa fa-truck" style="padding-right:3px;"></i>{{current_recent_order.shipping_status}}</span>
                                            </td>
                                            <td class="text-truncate">$ {{current_recent_order.total_amount}}</td>
                                            <td class="text-truncate">{{current_recent_order.created_at}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="table-responsive">
                                <div style="text-align: center">
                                    <h3>No recent order</h3>
                                </div>
                            </div>
                        </div>
                        <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getRecentOrders()" :offset="offset"></order-paginator>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Buyers</h4>
                        <a class="heading-elements-toggle">
                            <i class="fa fa-ellipsis-v font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a data-action="reload">
                                        <i class="ft-rotate-cw"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-if="current_recent_buyers.length > 0" class="card-content px-1">
                        <div id="recent-buyers" class="media-list height-300 position-relative">
                            <a v-if="current_recent_buyers.length > 0" v-for="(recent_buyer, index) in current_recent_buyers" href="#" class="media border-0" :key="index">
                                <!-- <div class="media-left pr-1">
                                    <span class="avatar avatar-md avatar-online">
                                        <img class="media-object rounded-circle" src="/images/avatar-s-6.png" alt="Generic placeholder image">
                                        <i></i>
                                    </span>
                                </div> -->
                                <div class="media-body w-100">
                                    <h6 v-if="recent_buyer.user.id" class="list-group-item-heading">{{recent_buyer.user.name}}
                                        <span class="font-medium-4 float-right pt-1">${{recent_buyer.order.paid_amount}}</span>
                                    </h6>
                                    <h6 v-else class="list-group-item-heading">{{recent_buyer.user}}
                                        <span class="font-medium-4 float-right pt-1">${{recent_buyer.order.paid_amount}}</span>
                                    </h6>
                                    <p v-if="recent_buyer.order.order_items && recent_buyer.order.order_items.length >0" class="list-group-item-text mb-0">
                                        <span class="badge badge-primary">{{recent_buyer.order.order_items[0].name}}</span>
                                        <span class="badge badge-warning ml-1">{{recent_buyer.order.order_items[0].qty}}</span>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div v-else class="card-content px-1">
                        <div style="text-align: center">
                            <h3>No recent buyers</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Recent Orders & Monthly Salse -->
    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        components: {
            'order-paginator': Paginator
        },
        props: ['total_items',
            'new_customers',
            'new_orders',
            'total_profit',
            'recent_buyers',
            'recent_orders',
            'monthly_sales',
            'monthly_orders',
            'popular_products',
            'active_categories',
            'new_orders'
        ],
        data() {
            return {
                current_recent_buyers: [],
                current_recent_orders: [],
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
                area_chart_data: [],
                bar_chart_data: [],

                months_numbers: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"]
            }
        },
        methods: {
            getRecentOrders() {
                /* $.LoadingOverlay("show"); */
                $('#card-orders').block({
                    message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
                axios.get(`/api/admin/recent-orders?page=${this.paginator.current_page}`)
                    .then((response) => {
                        $('#card-orders').unblock();
                        this.paginator = response.data;
                        this.current_recent_orders = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
            updateRecentOrders() {
                this.paginator.current_page = 1;
                this.getRecentOrders();
            },

            loadAreaChartData(data) {
                 for (var i in this.months_numbers) {
                    var bar_chart_data_item = {}
                    switch (this.months_numbers[i]) {
                        case '01':
                            bar_chart_data_item.month = 'Jan'
                            break;
                        case '02':
                            bar_chart_data_item.month = 'Feb'
                            break;
                        case '03':
                            bar_chart_data_item.month = 'Mar'
                            break;
                        case '04':
                            bar_chart_data_item.month = 'Apr'
                            break;
                        case '05':
                            bar_chart_data_item.month = 'May'
                            break;
                        case '06':
                            bar_chart_data_item.month = 'Jun'
                            break;
                        case '07':
                            bar_chart_data_item.month = 'Jul'
                            break;
                        case '08':
                            bar_chart_data_item.month = 'Aug'
                            break;
                        case '09':
                            bar_chart_data_item.month = 'Sep'
                            break;
                        case '10':
                            bar_chart_data_item.month = 'Oct'
                            break;
                        case '11':
                            bar_chart_data_item.month = 'Nov'
                            break;
                        case '12':
                            bar_chart_data_item.month = 'Dec'
                            break;

                        default:
                            break;
                    }
                    if (data[this.months_numbers[i]]) {
                        bar_chart_data_item.sales = parseFloat(data[this.months_numbers[i]]['total']).toFixed(2);
                    } else {
                        bar_chart_data_item.sales = 0;
                    }
                    this.bar_chart_data.push(bar_chart_data_item);
                }
            },
             getValue(obj, key, _default) {
                _default = (typeof _default == 'undefined')? '' : _default;
                return key.split(".").reduce(function(o, x) {
                    return (typeof o[x] == "undefined" || o[x] === null || o[x] == null) ? _default : o[x];
                }, obj);
            }, 

            /*  fillRecentBuyers(data) {
                 for (var property in data) {
                     if (data.hasOwnProperty(property)) {
                         this.current_recent_buyers.push(data[property]);
                     }
                 }
             },
 
             fillRecentOrders(data) {
                 for (var property in data) {
                     if (data.hasOwnProperty(property)) {
                         this.current_recent_orders.push(data[property]);
                     }
                 }
             } */
        },
        created() {
            if (Object.keys(this.recent_buyers).length > 0) {
                var count = 0;
                for(var i in this.recent_buyers){
                    if(typeof this.recent_buyers[i] != 'undefined' && this.recent_buyers[i] != null && this.recent_buyers[i] != ''){
                        this.current_recent_buyers[count] = this.recent_buyers[i];
                        count++;
                    }
                }
            }
            if (this.recent_orders) {
                this.paginator = this.recent_orders;
                this.current_recent_orders = this.recent_orders.data;
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                if (this.monthly_sales) {
                    this.loadAreaChartData(this.monthly_sales);
                    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    Morris.Bar({
                        element: 'monthly-sales1',
                        /* data: [{ month: 'Jan', sales: 1835 }, { month: 'Feb', sales: 2356 }, { month: 'Mar', sales: 1459 }, { month: 'Apr', sales: 1289 }, { month: 'May', sales: 1647 }, { month: 'Jun', sales: 2156 }, { month: 'Jul', sales: 1835 }, { month: 'Aug', sales: 2356 }, { month: 'Sep', sales: 1459 }, { month: 'Oct', sales: 1289 }, { month: 'Nov', sales: 1647 }, { month: 'Dec', sales: 2156 }], */
                        data: self.bar_chart_data,
                        xkey: 'month',
                        ykeys: ['sales'],
                        labels: ['Sales'],
                        barGap: 4,
                        barSizeRatio: 0.5,
                        gridTextColor: '#bfbfbf',
                        gridLineColor: '#E4E7ED',
                        numLines: 5,
                        gridtextSize: 14,
                        resize: true,
                        barColors: ['#00B5B8'],
                        hideHover: 'auto',
                        hoverCallback: function (index, options, content, row) {
                            return "$" + row.sales
                        }
                    });
                }
            });
        }
    }
</script>
<style>
</style>