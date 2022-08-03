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
                                    <div class="col-12 col-md-6 input-group" style="align-self:center;">
                                        <div class="skin skin-square">
                                            <div class="form-group">
                                                <fieldset>
                                                    <input type="checkbox" id="pay_due">
                                                    <label for="pay_due">Payment Due</label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group">
                                    <template v-if="current_subscriptions.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <strong>Customer</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Plan</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Status</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Next payment date</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Expire at</strong>
                                                </div>
                                                <div class="col-md-2 text-center">

                                                </div>
                                            </div>
                                        </li>
                                        <li v-for="(subscription, key) in current_subscriptions" class="list-group-item" :key="key">
                                            <div class="row">
                                                <div class="col-md-2" style="align-self:center;">
                                                    <b>{{subscription.user.firstname}} {{subscription.user.lastname}}</b>
                                                </div>
                                                <div v-if="subscription.next_plan_id > 0 && subscription.next_plan" class="col-md-2" style="align-self:center;">
                                                    {{subscription.next_plan.title}} - ${{subscription.next_plan.price}}/{{interval[subscription.next_plan.interval]}}
                                                </div>
                                                <div v-else-if="subscription.current_plan_id > 0 && subscription.plan" class="col-md-2" style="align-self:center;">
                                                    {{subscription.plan.title}} - ${{subscription.plan.price}}/{{interval[subscription.plan.interval]}}
                                                </div>
                                                <div v-else class="col-md-2" style="align-self:center;">
                                                    -
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    {{subscription.status}}
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    {{moment(subscription.next_payment_date, 'YYYY-MM-DD HH:mm:ss').format('MMM DD, YYYY')}}
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    {{moment(subscription.expires_at, 'YYYY-MM-DD HH:mm:ss').format('MMM DD, YYYY')}}
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    <div class="text-right">
                                                        <div tyle="padding: 5px" style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-subscription/' + subscription.id" class="btn btn-primary" style="color: #fff" title="Edit">
                                                                <i class="fa fa-pencil d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removesubscription" @click="removeSubscription(subscription.id)" class="btn btn-danger" style="color: #fff" title="Remove">
                                                                <i class="fa fa-trash-o d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="list-group-item" style="text-align: center">
                                            <h4>You have not subscription created</h4>
                                            <a href="/admin/store/add-subscription" class="create-link">Create your first subscription now</a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getSubscriptions()" :offset="offset"></paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriptionServices from '../../services/SubscriptionServices.js';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins: [SubscriptionServices],
        components: {
            'paginator': Paginator
        },
        data() {
            return {
                current_subscriptions: [],
                moment:moment,
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 8,
                 interval:{
                    'year':'yr',
                    'month':'mo',
                },
                query:'',
                pay_due: false
            }
        },
        watch:{
            query:_.debounce(function(){
                this.getSubscriptions(1);
                },300)
        },
        methods: {
            removeSubscription(id) {
                swal({
                    title: "Remove subscription",
                    text: "Are you sure you want remove this subscription definitely?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        this.removeSubscriptionCall(id, this.removeSubscriptionCallback);
                    }
                });
            },
            removeSubscriptionCallback(response) {
                $.LoadingOverlay("hide");
                if (response.status > 0) {
                    if (this.current_subscriptions.length > 0) {
                        for (var i in this.current_subscriptions) {
                            if (this.current_subscriptions[i].id == response.data.id) {
                                this.current_subscriptions.splice(i, 1);
                            }
                        }
                    } else {
                        this.current_subscriptions = [];
                    }
                }
            },
            getSubscriptions(page) {
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: 10,
                    fields: ['id','status','next_payment_date','expires_at','user','plan','next_plan','next_plan_id','current_plan_id'],
                    with:{
                        'plan':{},'next_plan':{}
                        }
                };
                if(this.query != ''){
                    params.with['user'] = {
                        query: {
                            fields: ['firstname', 'lastname'],
                            value: '+*' + this.query.trim().replace(" ", "* +*") + '*'
                        }
                    };
                }else{
                    params.with['user'] = {};
                }
                if(this.pay_due){
                    params['where'] = [
                        ['next_payment_date','<', moment().format('YYYY-MM-DD HH:mm:ss')],
                        ['status', 'active'],
                    ];
                }
                this.searchSubscriptions(params, this.searchSubscriptionsCallback);
            },
            searchSubscriptionsCallback(response){
                if(response.status > 0){
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
                    this.current_subscriptions = response.data;
                }else{

                }
                $.LoadingOverlay("hide");
            }
        },
        created() {
            this.getSubscriptions();
        },
        mounted() {
            var _this = this;
            $('.skin-square input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
            });

            $('#pay_due').on('ifChecked', function (event) {
                _this.pay_due = true;
                _this.getSubscriptions(1);
            });

            $('#pay_due').on('ifUnchecked', function (event) {
                _this.pay_due = false;
                _this.getSubscriptions(1);
            });
        }
    }
</script>
<style>
    .create-link {
        font-size: 18px;
        color: #13919b !important;
    }
    .custom-card {
        height: 480px;
    }

    .action-buttom{
        position:absolute; 
        bottom:10px; 
        right:21px;
    }
    @media (max-width: 990px){
        .custom-card {
            height: 99%;
        }
        .action-buttom{
            position:unset; 
            bottom:unset; 
            right:unset;
            padding-right:21px;
        }
    }
    #first {
        background-color: #8080801f;
    }
    .list-group-item {
        padding-bottom: 5px;
        padding-top: 5px;
    }
    
</style>