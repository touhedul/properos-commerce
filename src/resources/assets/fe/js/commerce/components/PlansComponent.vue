<template>
    <div class="tt-product-page__upsell">
        <div v-show="!showAddCard" class="row">
            <div class="col-md-12 text-center">
                <h4>Plans</h4>
            </div>
            <div class="col-md-3 pl-0" v-for="(plan, index) in plans" :key="index" style="min-width:300px; max-width:350px">
                <div class="card text-center" v-bind:class="plan['color_class']" style="margin-bottom:15px;">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title text-uppercase">{{plan['title']}}</h4>
                            <h1 v-if="plan['price'] >= 0">$ <strong>{{plan['price']}}</strong> <small>/mo</small></h1>
                            <h2 v-else class="text-uppercase"><strong>Contact us</strong></h2>
                            <h5 class="text-capitalize" style="margin-top: 25px;margin-bottom: -12px;">Includes</h5>
                        </div>
                        <ul class="list-group list-group-flush" >
                        </ul>
                        <div class="card-body" v-if="plan['price'] != 0">
                            <a v-if="plan['price'] > 0 && plan.id != current_subscription.current_plan_id && current_subscription.id >0" href="#" class="btn btn-success btn-float text-uppercase" @click="changeSubscription(plan['id'])">Get Started</a>
                            <a v-else-if="plan['price'] > 0 && current_subscription.id == 0" href="#" class="btn btn-success btn-float text-uppercase" @click="valPaymentMethod(plan['id'])">Get Started</a>
                            <a v-else-if="plan.id == current_subscription.current_plan_id" href="#" class="btn btn-success btn-float text-uppercase" disabled>Current Plan</a>
                            <a v-else-if="plan['price'] < 0" href="#" class="btn btn-success btn-float text-uppercase">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="showAddCard" style="margin-top: 30px">
             <div class="col-md-12 text-center">
                <h4>Billing Information</h4>
            </div>
            <div class="col-md-12 text-right">
                <a @click="backPlans()" href="#">Back to plans</a>
            </div>
            <div>
                <card-processor-component :clientKey="client_key" :apiLoginID="api_id" :cardProcessor="card_processor" @addPaymentMethod="addPaymentMethodHandle"
                    :description="description"></card-processor-component>
            </div>
        </div>
    </div>
</template>

<script>
import SubscriptionServices from '../services/SubscriptionServices.js'
import CardProcessorComponent from '../../../../../components/CardProcessorComponent.vue'
    export default {
        mixins: [SubscriptionServices],
        props:['subscription', 'card_processor', 'client_key', 'api_id', 'description'],
        components: {
            'card-processor-component': CardProcessorComponent,
        },
        data() {
            return {
                interval:{
                    'year':'yr',
                    'month':'mo',
                },
                plans:[],
                current_subscription:{
                    id:0
                },
                yearly:0,
                moment:moment,
                fields: ['id','title','description','price','interval','status','created_at'],
                params:{},
                showAddCard: false
            }
        },
        methods: {
            getPlansInfoCallback(response){
                if (response.status > 0) {
                    this.plans = response.data;
                    // this.subscription = response.data.subscription;
                } else {
                    toastr.error(response.errors[0], 'Error!', {positionClass: 'toast-top-center', containerId: 'toast-top-center'});
                }
            }, 
            changeSubscription(id){
                if(id != this.current_subscription.current_plan_id){
                    if(this.current_subscription.status == 'trial'){
                        let params = {
                            id: this.current_subscription.id,
                            next_plan_id:id,
                        }
                        $.LoadingOverlay("show");
                        this.setSubscriptionPlanInfo(params,this.setSubscriptionPlanInfoCallback)
                    }else if(this.current_subscription.status == 'active'){
                        let params = {
                            id: this.current_subscription.id,
                            current_plan_id:id,
                            next_plan_id:0,
                        }
                        $.LoadingOverlay("show");
                        this.setSubscriptionPlanInfo(params,this.setSubscriptionPlanInfoCallback)
                    }
                }
            },   
            createSubscription(){
                this.params.status = 'active';

                $.LoadingOverlay("show");
                this.addSubscriptionPlanInfo(this.params,this.addSubscriptionPlanInfoCallback)
            },   
            setSubscriptionPlanInfoCallback(response){
                $.LoadingOverlay("hide");
                if (response.status > 0) {
                    this.current_subscription = Object.assign({}, this.current_subscription, response.data);
                    swal({
                        title:'Success!', 
                        text: 'Your subscription has been update with a new plan.', 
                        icon: 'success'
                    });
                } else {
                        swal('Error', response.errors[0], 'error');
                }
            },
            addSubscriptionPlanInfoCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    swal({
                        title:'Success!', 
                        text: 'Your subscription has been successfully.', 
                        icon: 'success',
                        buttons: true,

                        buttons: {
                            confirm: {
                                text: "ok",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: false
                            }
                        }
                    }).then(isConfirm => {
                        if (isConfirm) {
                             window.location.href = '/';
                        }
                    });
                   
                }else{
                    swal('Error', response.errors[0], 'error');
                }
            },
            valPaymentMethod(id){
                this.params.current_plan_id =id;
                this.checkPaymentMethod(this.checkPaymentMethodCallback);
            },
            checkPaymentMethodCallback(response){
                if(response.status > 0){
                    if(response.data.customer_payment == true){
                        this.params.payment_profile_id = response.data.payment_profile_id;
                        this.createSubscription();
                    }else{
                        this.showAddCard = true;
                    }
                }else{
                    toastr.error(response.errors[0], 'Error!', {positionClass: 'toast-top-center', containerId: 'toast-top-center'});
                }
            },
            addPaymentMethodHandle(data) {
                this.createSubscription();
                // this.current_payment_methods.push(data);
                // this.showAddCard = false;
            },
            backPlans(){
                this.showAddCard = false;
            }

        },
        created() {
            if(this.subscription.id){
                this.current_subscription.id = this.subscription.id;
                this.current_subscription.user_id = this.subscription.user_id;
                this.current_subscription.status = this.subscription.status;
                this.current_subscription.last_payment = this.subscription.last_payment;
                this.current_subscription.next_payment_date = this.subscription.next_payment_date;
                this.current_subscription.last_plan_id = this.subscription.last_plan_id;
                this.current_subscription.next_plan_id = this.subscription.next_plan_id;
                this.current_subscription.current_plan_id = this.subscription.current_plan_id;
                this.current_subscription.status = this.subscription.status;
            }
        },
        mounted() {
            var params = {
                fields: this.fields,
                page:1,
                limit:10
            };
            this.getPlansInfo(params, this.getPlansInfoCallback);
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-post-grid__title {
        font-size: 24px;
    }

    .embed-responsive {
        position: relative !important;
    }

    hr {
        color: #123455;
    }

    #theme input[type=email], #theme input[type=password], #theme input[type=search], #theme input[type=tel], #theme input[type=text], #theme select, #theme textarea {
        background-color: #fff;
        border-color: #fe5a1aa1!important;
        color: #333;
    }
</style>