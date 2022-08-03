<template>
    <div class="row">
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card" style="height: 100%;">
                        <div class="card-header">
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a data-action="collapse">
                                            <i class="ft-minus"></i>
                                        </a>
                                    </li>
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
                                    <li>
                                        <a data-action="close">
                                            <i class="ft-x"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6 input-group" style="margin-bottom:15px;">
                                        <form style="width:100%;">
                                            <input type="text" v-model="query" class="form-control" id="query" placeholder="Search..." style="width:100%;">
                                        </form>
                                    </div>
                                    <div class="col-12 col-md-6 input-group" style="margin-bottom:15px;">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <label class="label-control" for="select2-status">Status</label>
                                                <select id="select2-status" class="select2-placeholder form-control" v-model="status">
                                                    <option value="new">New Order</option>
                                                    <option value="pending">Payment Pending</option>
                                                    <option value="error">Payment Error</option>
                                                    <option value="" selected>All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group">
                                    <template v-if="current_orders.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <strong>Order number</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Creator</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Customer</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Total amount</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Shipping</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Payment</strong>
                                                </div>
                                                <!-- <div class="col-md-2">
                                                    <strong>Shipping address</strong>
                                                </div> -->
                                                <div class="col-md-1">
                                                    <strong>Source</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Date</strong>
                                                </div>
                                                <div class="col-md-2 text-center">

                                                </div>
                                                <!-- <div class="col-md-1 text-center">

                                                </div> -->
                                            </div>
                                        </li>
                                        <li v-for="(current_order, key) in current_orders" class="list-group-item" :key="key">
                                            <div class="row">
                                                <div class="col-md-1" style="padding-top: 10px">
                                                    <a href="#">
                                                        <span style="font-size: 16px">
                                                            <a :href="'/admin/store/edit-order/' + current_order.id">{{current_order.order_number}}</a>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div v-if="current_order.creator" class="col-md-1" style="padding-top: 10px">
                                                    {{current_order.creator.firstname}} {{current_order.creator.lastname}}
                                                    <!-- <span style="font-size: 12px; font-weight: bold"> ({{current_order.creator.email}})</span> -->
                                                </div>
                                                <div v-else class="col-md-1" style="padding-top: 10px;text-align:center;">
                                                    -
                                                    <!-- <span style="font-size: 12px; font-weight: bold"> ({{current_order.creator.email}})</span> -->
                                                </div>
                                                <div v-if="current_order.customer" class="col-md-2" style="padding-top: 10px">
                                                    {{current_order.customer.firstname}} {{current_order.customer.lastname}}
                                                    <span style="font-size: 12px; font-weight: bold"> ({{current_order.customer.email}})</span>
                                                </div>
                                                <div v-else class="col-md-2" style="padding-top: 10px">
                                                    <template v-if="current_order.customer_name || current_order.customer_email">
                                                        {{current_order.customer_name}}
                                                        <span style="font-size: 12px; font-weight: bold"> ({{current_order.customer_email}})</span>
                                                    </template>
                                                    <template v-else>
                                                        N/A
                                                    </template>
                                                </div>
                                                <div class="col-md-1" style="padding-top: 10px; font-size: 12px">
                                                    ${{current_order.total_amount}}
                                                </div>
                                                <div class="col-md-1" style="padding-top: 10px; font-size: 14px">
                                                    <span :class="{'badge badge-primary badge-square' : current_order.shipping_status == 'shipped' || current_order.shipping_status =='fullfiling',
                                                                    'badge badge-danger badge-square' : current_order.shipping_status == 'cancelled' || current_order.shipping_status == 'error' || current_order.shipping_status =='returned',
                                                                    'badge badge-warning badge-square' : current_order.shipping_status == 'pending',
                                                                    'badge badge-square badge-primary' : current_order.shipping_status == 'fulfilled' || current_order.shipping_status =='delivered'}">
                                                        {{current_order.shipping_status | capitalize}}
                                                    </span>
                                                </div>
                                                <div class="col-md-1" style="padding-top: 10px; font-size: 14px">
                                                    <span :class="{'badge badge-primary badge-square' : current_order.status == 'paid',
                                                                    'badge badge-danger badge-square' : current_order.status == 'pay__error' || current_order.status == 'cancelled' || current_order.status == 'refunded',
                                                                    'badge badge-warning badge-square' : current_order.status == 'pending'}">
                                                        {{current_order.status | capitalize}}
                                                    </span>
                                                </div>
                                                <!--  <div class="col-md-2" style="padding-top: 10px; font-size: 12px">
                                                        <span>{{current_order.shipping_address1}} {{current_order.shipping_address2}}
                                                            {{current_order.shipping_city}} {{current_order.shipping_zip}}
                                                            {{current_order.shipping_state}} {{current_order.shipping_country}}
                                                        </span>
                                                    </div> -->
                                                <div class="col-md-1" style="padding-top: 10px; font-size: 12px">
                                                    <span v-if="current_order.origin == 'store' || current_order.origin == 'upwork'">
                                                        <img :src="'/images/marketplace/' + current_order.origin + '.png'" :class="{'icon-store' : current_order.origin == 'store', 'icon-amazon' : current_order.origin == 'amazon' || current_order.origin == 'upwork'}">
                                                    </span>
                                                    <span v-else>
                                                        <img :src="'/images/icons/unknown.png'" class="icon-store" :title="current_order.origin">
                                                    </span>
                                                </div>
                                                <div class="col-md-2" style="padding-top: 10px">
                                                    <span style="font-size: 12px">
                                                        {{moment(current_order.created_at).format('LLLL')}}
                                                    </span>
                                                </div>
                                                <!-- <div class="col-md-1">
                                                        <div class="row">
                                                            <div v-if="current_order.payment_method_id" class="col-xs-12" style="padding: 5px; text-align: center">
                                                                <template v-if="current_order.payment_method.name =='paypal'">
                                                                    <span style="font-size: 22px" class="fa fa-paypal"></span>
                                                                    <span> paypal</span>
                                                                </template>
                                                                <template v-else>
                                                                    <span style="font-size: 22px" class="fa fa-credit-card"></span>
                                                                    <span> credit-card</span>
                                                                </template>
                                                            </div>
                                                            <div v-else class="col-xs-12" style="padding: 5px">
                                                                <a @click="setOrder(current_order.id)" data-toggle="modal" data-target="#checkoutOrder" class="btn btn-secondary" style="color: #fff">
                                                                    <i class="fa fa-usd d-none d-sm-inline"></i>
                                                                    Pay order
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                <div class="col-md-2">
                                                    <div class="text-right" style="width: 100%">
                                                        <div style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-order/' + current_order.id" class="btn btn-primary" :disabled="{'disabled' : current_order.status == 'pending' && current_order.shipping_status == 'pending'}"
                                                                style="color: #fff; margin-top:2; margin-bottom:2px;" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </div>
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removeOrder" @click="removeOrder(current_order.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-1">
                                                    <div class="row">
                                                        <div class="col-xs-12" style="padding: 5px">
                                                            <a id="removeOrder" @click="removeOrder(current_order.id)" class="btn btn-danger" style="color: #fff">
                                                                <i class="fa fa-trash-o d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="list-group-item" style="text-align: center">
                                            <h2>There are no orders</h2>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getOrders()" :offset="offset"></order-paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="checkoutOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Pay order</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <label class="label-control" for="projectinput5">Payment method</label>
                                    <div class="input-group" style="margin-bottom: 15px">
                                        <select id="select2-payment-method" class="form-control" v-model="order_payment.payment_method_id" style="width: 100%">
                                            <option selected style="color: #999999">Select payment method...</option>
                                            <option v-for="(payment_method, index) in current_payment_methods" :key="index" :value="payment_method.id">{{payment_method.label}}</option>
                                        </select>
                                    </div>
                                    <template v-if="payment_flag == 'paypal'">
                                        <div class="form-group">
                                            <label for="userinput1">Paypal email</label>
                                            <div class="input-group">
                                                <input type="text" v-model="paypalEmail" class="form-control">
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="payment_flag == 'credit-card'">
                                        <div class="form-group">
                                            <label for="userinput1" style="font-size: 12px">Credit/Debit card information</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="userinput1" style="font-size: 12px">Card holder</label>
                                            <div class="input-group">
                                                <input type="text" v-model="cardInfo.card_holder" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="userinput1" style="font-size: 12px">Card number</label>
                                            <div class="input-group">
                                                <input type="text" v-model="cardInfo.card_number" class="form-control" id="cardNumber">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="userinput1" style="font-size: 12px">Expiration date</label>
                                                        <div class="input-group">
                                                            <input type="text" v-model="cardInfo.exp_date" class="form-control" id="cardExpiration">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="userinput1" style="font-size: 12px">CVV</label>
                                                    <div class="input-group">
                                                        <input type="text" v-model="cardInfo.cvv" class="form-control" id="cardCvv">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="col-md-4" style="margin-top: 20px">
                                    <div v-if="payment_flag == 'paypal'">
                                        <img src="/images/paypal.png" style="width: 100px; height: auto">
                                    </div>
                                    <div v-if="payment_flag == 'cash'">
                                        <img src="/images/cash.png" style="width: 100px; height: auto">
                                    </div>
                                    <div v-if="payment_flag == 'cheque'">
                                        <img src="/images/cheque.png" style="width: 100px; height: auto">
                                    </div>
                                    <div v-if="payment_flag == 'credit-card'">
                                        <img src="/images/creditcard.png" style="width: 100px; height: auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="sendPayment()" type="button" class="btn btn-outline-primary" id="onhidebtn">Send payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    import AdminApiCalls from '../../services/OrdersServices';
    export default {
        components: {
            'order-paginator': Paginator
        },
        props: ['payment_methods'],
        mixins:[AdminApiCalls],
        data() {
            return {
                moment:moment,
                current_orders: [],
                current_payment_methods: [],
                order_payment: {
                    order_id: '',
                    payment_method_id: ''
                },
                cardInfo: {
                    card_holder: '',
                    card_number: '',
                    exp_date: '',
                    cvv: ''
                },
                paypalEmail: '',

                current_payment_methods: {},
                selected_payment_method: '',
                payment_flag: '',
                current_currency: {
                    name: 'American dolar',
                    symbol: '&#36;'
                },
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
                status: '',
                query:''
            }
        },
        watch:{
            status:_.debounce(function(){
                this.getOrders(1);
                },300),
            query:_.debounce(function(){
                this.getOrders(1);
                },300)
        },
        methods: {
            sendPayment() {
                var final_order_payment = {
                    order_id: this.order_payment.order_id,
                    payment_method_id: this.order_payment.payment_method_id
                }
                if (this.payment_flag == 'credit-card') {
                    final_order_payment.cardInfo = this.cardInfo;
                } else if (this.payment_flag == 'paypal') {
                    final_order_payment.paypalEmail = this.paypalEmail;
                }
                var self = this;
                axios({
                    method: 'post',
                    url: '/api/orders/payOrder',
                    data: final_order_payment
                }).then(response => {
                    if (response.data.status == 1) {
                        window.location.href = '/admin/store/orders';
                    } else {
                        swal("Error!", response.data.errors[0], "error");
                    }
                }).catch(error => {
                    swal("Error!", error, "error");
                });
            },
            removeOrder(id) {
                swal({
                    title: "Are you sure?",
                    text: "This action will remove the order and all it's related information",
                    icon: "warning",
                    buttons: true,

                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "btn-warning",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Yes, delete it",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                }).then(isConfirm => {
                    if (isConfirm) {
                        axios.delete('/api/orders/destroy/' + id).then(response => {
                            if (response.data.status == 1) {
                                swal({ title: "Deleted!", text: response.data.message, timer: 1000, icon: "success" });
                                let index = this.current_orders.findIndex(function (item) {
                                    return item.id == id;
                                });
                                this.current_orders.splice(index, 1);
                            } else {
                                swal("Error!", response.data.errors[0], "error");
                            }
                        }).catch(error => {
                            swal("Error!", error, "error");
                        });
                    }
                });
            },
            setPaymentMethod(e) {
                for (var i in this.current_payment_methods) {
                    if (this.order_payment.payment_method_id == this.current_payment_methods[i].id) {
                        this.payment_flag = this.current_payment_methods[i].name
                    }
                }
            },
            resetOrderPayment() {
                var self = this;
                Object.keys(self.order_payment).forEach(function (key) {
                    self.order_payment[key] = '';
                });
            },
            resetCardInfo() {
                var self = this;
                Object.keys(self.cardInfo).forEach(function (key) {
                    self.cardInfo[key] = '';
                });
            },
            resetPaypalEmail() {
                this.paypalEmail = '';
            },
            setOrder(id) {
                if (id > 0) {
                    this.order_payment.order_id = id;
                }
            },
            getOrders(page) {
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: 10,
                    with: ['customer','paymentMethod','orderItems','creator'],
                    orderby:{
                        created_at: 'DESC'
                    },
                    fields: ['id','order_number','customer','total_amount','customer_name','customer_email','origin','created_at', 'status','shipping_status'],
                };
                switch(this.status){
                    case 'new':
                        params['where'] = [
                            ['shipping_status', 'pending'],
                            ['status', '<>','cart'],
                        ];
                    break;
                    case 'pending':
                        params['where'] = [
                            ['status','pending']
                        ];
                    break;
                    case 'error':
                        params['where'] = [
                            ['status','pay__error']
                        ];
                    break;
                    default:
                        params['where'] = [
                            ['status', '<>','cart'],
                        ];
                    break
                }
                if(this.query != ''){
                    params['query'] = '+*' + this.query.trim().replace(" ", "* +*") + '*';
                }
                this.searchOrders(params, this.searchOrdersCallback);
            },
            searchOrdersCallback(code, response, errors){
                if(code == 200){
                    if(response.data.length > 0){
                        this.paginator = response.pagination;
                    }else{
                        this.paginator= {
                            total: 0,
                            per_page: 2,
                            from: 1,
                            to: 0,
                            current_page: 1
                        };
                    }
                    this.current_orders = response.data;
                }else{
                    swal("Error!", response.errors[0], "error");
                }
                $.LoadingOverlay("hide");
            }
        },
        created() {
            this.current_payment_methods = this.payment_methods;
            if(window.location.href.split('#').length >1){
                this.status = 'new';
            }
            this.getOrders(1);
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('#checkoutOrder').on('show.bs.modal', function () {
                    $("#select2-payment-method").select2();
                    $('#select2-payment-method').on('change', function (e) {
                        self.order_payment.payment_method_id = parseInt($('#select2-payment-method').select2('data')[0].id);
                        self.setPaymentMethod();
                    });
                });
                $("#select2-status").select2();
                 $('#select2-status').on('change', function (e) {
                        self.status = $('#select2-status').val();
                    });
                $('#checkoutOrder').on('hide.bs.modal', function () {
                    self.resetOrderPayment();
                    self.resetCardInfo();
                    self.resetPaypalEmail();
                });
            });
        }
    }
</script>
<style>
    .list-group-item {
        padding-bottom: 5px;
        padding-top: 5px;
    }

    #first {
        background-color: #8080801f;
    }

    .swal-text {
        text-align: center;
        color: #61534e;
        font-size: 13px;
    }

    .swal-title {
        text-align: center;
        color: #61534e;
        font-size: 18px;
    }

    .icon-store {
        width: 30px;
        height: auto;
    }

    .icon-amazon {
        width: 50px;
        height: auto;
    }

    .select2-container {
        width: 150px!important;
    }
</style>