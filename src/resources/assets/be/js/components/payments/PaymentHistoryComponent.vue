<template>
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div v-if="current_payments.length > 0">
                    <ul class="list-group">
                        <li id="first" class="list-group-item d-none d-lg-block shrink">
                            <div class="row">
                                <div class="col-md-1">
                                    <strong>Method</strong>
                                </div>
                                <div class="col-md-1">
                                    <strong>Operation</strong>
                                </div>
                                <div class="col-md-1">
                                    <strong>Amount</strong>
                                </div>
                                <div class="col-md-1">
                                    <strong>Fee</strong>
                                </div>
                                <!--  <div class="col-md-1">
                                            <strong>Date of birth</strong>
                                        </div> -->
                                <div class="col-md-1">
                                    <strong>Status</strong>
                                </div>
                                <div class="col-md-1">
                                    <strong>Provider</strong>
                                </div>
                                <div class="col-md-1">
                                    <strong>Auth. Code</strong>
                                </div>
                                <div class="col-md-2">
                                    <strong>Trans. Id</strong>
                                </div>
                                <div class="col-md-2">
                                    <strong>Date</strong>
                                </div>
                                <div class="col-md-1">
                                    
                                </div>
                            </div>
                        </li>
                        <li if="current_payments.length > 0" v-for="(payment, index) in current_payments" :key="index"
                            class="list-group-item shrink">
                            <div class="row">
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span>
                                        {{payment.type | capitalize}}
                                    </span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span>{{payment.operation | capitalize}}</span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span>
                                        ${{parseFloat(payment.amount).toFixed(2)}}
                                    </span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span v-if="payment.fee">
                                        ${{parseFloat(payment.fee).toFixed(2)}}
                                    </span>
                                    <span v-else>
                                        N/A
                                    </span>
                                </div>
                                <!--  <div class="col-md-1" style="padding-top: 10px; font-size: 14px">
                                            {{moment(users.dob).format('LLLL')}}
                                        </div> -->
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span v-if="payment.transaction_status">
                                        {{payment.transaction_status | capitalize}}
                                    </span>
                                    <span v-else style="text-align: center">
                                        ------
                                    </span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span v-if="payment.provider">
                                        {{payment.provider | capitalize}}
                                    </span>
                                    <span v-else style="text-align: center">
                                        ------
                                    </span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px">
                                    <span v-if="payment.auth_code">
                                        {{payment.auth_code}}
                                    </span>
                                    <span v-else style="text-align: center">
                                        ------
                                    </span>
                                </div>
                                <div class="col-md-2" style="padding-top: 10px">
                                    <span v-if="payment.transaction_id">
                                        {{payment.transaction_id}}
                                    </span>
                                    <span v-else style="text-align: center">
                                        ------
                                    </span>
                                </div>
                                <div class="col-md-2" style="padding-top: 10px">
                                    <span>
                                        {{moment(payment.created_at).format('LLL')}}
                                    </span>
                                </div>
                                <div class="col-md-1" style="padding-top: 10px;text-align:right;">
                                    <a style="font-size:12px;color:red;" v-if="(payment.operation == 'charge' || payment.operation == 'received') && payment.type == 'cash' && payment.amount > payment.refund_amount" @click="openModal(payment.id)">Refund</a>
                                    <a style="font-size:12px;color:red;" v-else-if="(payment.operation == 'charge' || payment.operation == 'received') && payment.amount > payment.refund_amount && getHours(payment.created_at) && (payment.type != 'card' || payment.last_4)" @click="openModal(payment.id)">Refund</a>
                                    <a style="font-size:12px;color:red;" v-else-if="(payment.operation == 'charge' || payment.operation == 'received') && payment.amount > payment.refund_amount && payment.type == 'paypal'" @click="openModal(payment.id)">Refund</a>
                                    <a style="font-size:12px;color:red;" v-else-if="(payment.operation == 'charge' || payment.operation == 'received') && payment.refund_amount == 0 && !getHours(payment.created_at) && (payment.type != 'paypal')" @click="voidAmount(payment.id)">Void</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div style="margin-top:20px;">
                        <paginator v-if="paginator.total > paginator.per_page" :pagination="paginator" @paginate="searchPayments()" :offset="offset"></paginator>
                    </div>
                </div>
                <div v-else class="text-center" style="margin-top: 50px">
                    <h3>No payments registered at this moment</h3>
                </div>
                <div class="modal fade text-left" id="make-refund" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xs" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Make Refund</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Amount (equal or less than ${{(payment_amount*1-refund_amount*1).toFixed(2)}})</label>
                                            <input v-model="amount" type="text" class="form-control">
                                            <span v-if="error.amount" class="message-error">{{error_message.amount}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="skin skin-square">
                                                <span style="padding: 10px">
                                                    <input id="manually" type="checkbox">
                                                    <label>
                                                        Manually
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                <button @click="refundAmount" type="button" class="btn btn-outline-info">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    import PaymentServices from '../../../../../../services/PaymentServices'
    export default {
        props: ['payments', 'order_id'],
        mixins:[PaymentServices],
        components:{
            'paginator': Paginator
        },
        data() {
            return {
                current_payments: [],
                moment:moment,
                amount:'',
                id:0,
                manually:0,
                error:{
                    amount:false
                },
                error_message:{
                    amount:false
                },
                payment_amount:0,
                refund_amount:0,
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 15,
            }
        },
        methods: {
            openModal(id){
                this.id = id;
                for(var i in this.current_payments){
                    if(this.current_payments[i].id == id){
                        this.payment_amount = this.current_payments[i].amount;
                        this.refund_amount = this.current_payments[i].refund_amount;
                    }
                }
                setTimeout(function(){
                    $("#make-refund").modal("show");
                },200);
            },
            refundAmount(){
                if(this.amount != '' && this.amount <= (this.payment_amount*1-this.refund_amount*1)){
                    var params = {
                        amount: this.amount,
                        id: this.id,
                    };
                    if(manually > 0){
                        params.manual = true;
                    }
                     $.LoadingOverlay("show");
                     $("#make-refund").modal("hide");
                    this.refundAmountCall(params, this.refundAmountCallback);
                }else{
                    this.error.amount = true;
                    if(this.amount == ''){
                        this.error_message.amount = 'Amount is required';
                    }else{
                        this.error_message.amount = 'Amount must be equal or less than  $'+(this.payment_amount*1-this.refund_amount*1);
                    }
                }
            },
            refundAmountCallback(response){
                 $.LoadingOverlay("hide");
                if(response.data.status > 0){
                     toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                     location.reload();
                }else{
                    var text = '';
                    for(var i in response.data.errors){
                        text += response.data.errors[i];
                    }
                    swal('Error!',text, 'error')
                }
            },
            voidAmount(id){
                swal({
                    title: "Are you sure?",
                    text: "Confirm that you want to make a void",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "btn-warning",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Yes",
                            value: true,
                            visible: true,
                            className: "btn-primary",
                            closeModal: true
                        }
                    }
                }).then(isConfirm => {
                    if (isConfirm) {
                        var params = {
                            id: id,
                        };
                        $.LoadingOverlay("show");
                        this.voidAmountCall(params, this.voidAmountCallback);
                    }
                });
                
            },
            voidAmountCallback(response){
                 $.LoadingOverlay("hide");
                if(response.data.status > 0){
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    location.reload();
                }else{
                    var text = '';
                    for(var i in response.data.errors){
                        text += response.data.errors[i];
                    }
                    swal('Error!',text, 'error')
                }
            },
            getHours(startTime){
                var end = moment(new Date());
                var duration = moment.duration(end.diff(startTime));
                var hours = duration.asHours();
                if(hours > 24){
                    return true;
                }
                return false;
            },
            searchPayments(page){
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: this.offset,
                    orderby:{
                        created_at: 'DESC'
                    },
                    where:[
                        ['payable_type','orders'],
                        ['payable_id',this.order_id],
                    ]
                };
                this.getPayments(params, this.getPaymentsCallback);
            },
            getPaymentsCallback(code, response, errors){
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
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            if (this.payments && this.payments.data.length > 0) {
                this.current_payments = this.payments.data;
                this.paginator= {
                    total: this.payments.total,
                    per_page: this.payments.per_page,
                    from: this.payments.from,
                    current_page: this.payments.current_page
                };
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function(){
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });

                $('#manually').on('ifChecked', function (event) {
                    self.manually = 1;
                });

                $('#manually').on('ifUnchecked', function (event) {
                    self.manually = 0;
                });
                $("#make-refund").on("hide.bs.modal", function(){
                    self.id = 0;
                    self.amount = '';
                    self.payment_amount =0;
                    self.refund_amount =0;
                    self.manually = 0;
                    $('#manually').iCheck('uncheck');
                });
            })
        }
    }
</script>

<style scoped>
    .card-body {
        min-height: 80vh;
    }

    #first {
        background-color: #e0e2e5;
        font-size: 12px;
    }

    .shrink {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .page-item.active .page-link {
        color: #fff !important;
        background-color: #005cab;
        border-color: #005cab;
    }
    .message-error {
        color: #d61212;
        padding-top: 10px;
        font-size: 12px;
    }
    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }
</style>