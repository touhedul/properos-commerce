<template>
    <div>
        <div class="card-body">
            <ul class="list-group">
                <template v-if="current_plans.length > 0">
                    <li id="first" class="list-group-item">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Title</strong>
                            </div>
                            <div class="col-md-3">
                                <strong>Description</strong>
                            </div>
                            <div class="col-md-1">
                                <strong>Price</strong>
                            </div>
                            <div class="col-md-1">
                                <strong>Interval</strong>
                            </div>
                            <div class="col-md-1">
                                <strong>Status</strong>
                            </div>
                            <div class="col-md-2">
                                <strong>Created at</strong>
                            </div>
                            <div class="col-md-2 text-center">

                            </div>
                        </div>
                    </li>
                    <li v-for="(plan, key) in current_plans" class="list-group-item" :key="key">
                        <div class="row">
                            <div class="col-md-2" style="align-self:center;">
                                <b>{{plan.title}}</b>
                            </div>
                            <div class="col-md-3" style="align-self:center;">
                                {{plan.description}}
                            </div>
                            <div class="col-md-1" style="align-self:center;">
                                ${{(plan.price).toFixed(2)}}
                            </div>
                            <div class="col-md-1" style="align-self:center;">
                                {{plan.interval}}
                            </div>
                            <div class="col-md-1" style="align-self:center;">
                                {{plan.status}}
                            </div>
                            <div class="col-md-2" style="align-self:center;">
                                {{moment(plan.created_at, 'YYYY-MM-DD HH:mm:ss').format('MMM DD, YYYY')}}
                            </div>
                            <div class="col-md-2" style="align-self:center;">
                                <div class="text-right">
                                    <div tyle="padding: 5px" style="padding: 5px; display:inline;">
                                        <a :href="'/admin/store/edit-plan/' + plan.id" class="btn btn-primary" style="color: #fff" title="Edit">
                                            <i class="fa fa-pencil d-none d-sm-inline"></i>
                                        </a>
                                    </div>
                                    <div style="padding: 5px; display:inline;">
                                        <a id="removePlan" @click="removePlan(plan.id)" class="btn btn-danger" style="color: #fff" title="Remove">
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
                        <h4>You have not plan created</h4>
                        <a href="/admin/store/add-plan" class="create-link">Create your first plan now</a>
                    </li>
                </template>
            </ul>
        </div>
        <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getplans()" :offset="offset"></paginator>
    </div>
</template>

<script>
    import PlanServices from '../../services/PlanServices';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins: [PlanServices],
        components: {
            'paginator': Paginator
        },
        data() {
            return {
                current_plans: [],
                moment:moment,
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 8
            }
        },
        methods: {
            removePlan(id) {
                swal({
                    title: "Remove plan",
                    text: "Are you sure you want remove this plan definitely?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        this.removePlanCall(id, this.removePlanCallback);
                    }
                });
            },
            removePlanCallback(response) {
                $.LoadingOverlay("hide");
                if (response.status > 0) {
                    if (this.current_plans.length > 0) {
                        for (var i in this.current_plans) {
                            if (this.current_plans[i].id == response.data.id) {
                                this.current_plans.splice(i, 1);
                            }
                        }
                    } else {
                        this.current_plans = [];
                    }
                }
            },
            // getplans() {
            //     $.LoadingOverlay("show");
            //     axios.get(`/api/admin/blogs?page=${this.paginator.current_page}`)
            //         .then((response) => {
            //             $.LoadingOverlay("hide");
            //             this.paginator = response.data;
            //             this.current_plans = response.data.data;
            //         })
            //         .catch(() => {
            //             console.log('handle server error from here');
            //         });
            // },
            getPlans() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: 10,
                    fields: ['id','title','description','price','interval','status','created_at'],
                };
                // switch(this.status){
                //     case 'new':
                //         params['where'] = [
                //             ['shipping_status', 'pending']
                //         ];
                //     break;
                //     case 'pending':
                //         params['where'] = [
                //             ['status','pending']
                //         ];
                //     break;
                //     case 'error':
                //         params['where'] = [
                //             ['status','pay__error']
                //         ];
                //     break;
                //     default:
                //     break
                // }
                // if(this.query != ''){
                //     params['query'] = '*' + this.query + '*';
                // }
                this.searchPlans(params, this.searchPlansCallback);
            },
            searchPlansCallback(response){
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
                    this.current_plans = response.data;
                }else{

                }
                $.LoadingOverlay("hide");
            }
        },
        created() {
            // if (this.plans) {
            //     this.paginator = this.plans;
            //     this.current_plans = this.plans.data;
            // }
            this.getPlans();
        },
        mounted() {

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