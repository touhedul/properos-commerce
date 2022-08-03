<template>
    <div>
        <div id="heading11" class="card-header">
            <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion11" aria-expanded="false" aria-controls="accordion11"
                class="card-title lead collapsed">Customers</a>
        </div>
        <div id="accordion11" role="tabpanel" aria-labelledby="heading11" class="collapse show">
            <div class="card-content">
                <div class="card-body">
                    <form>
                        <div class="col-11 col-md-6 input-group" style="padding-left:21px; padding-right:21px;">
                            <input type="text" v-model="query" class="form-control" id="query" placeholder="Search...">
                        </div>
                    </form>
                    <!--  <b>Report description:</b> Displays a sales list from a range of dates. -->
                        <div v-if="users.length >0" id="card-customers" class="card">
                            <!-- <div class="card-header"> -->
                                <!-- <h4 class="card-title">customers in this range of dates</h4> -->
                                <!-- <a class="heading-elements-toggle">
                                    <i class="fa fa-ellipsis-v font-medium-3"></i>
                                </a> -->
                            <!-- </div> -->
                            <div class="text-right" style="margin-bottom:10px;">
                                <img @click="searchCustomer(true)" class="icons-custom" src="/images/icons/excel.png" style="margin-right:5px;cursor:pointer;">
                                <!-- <a @click="searchCustomer()" class="btn btn-outline-warning" style="float: right; margin-right:5px;" title="Export excel"><i class="fa fa-file-excel-o"></i></a> -->
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="recent-customers" class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Paid</th>
                                                    <th class="text-center">Audit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user, index) in users" :key="index">
                                                    <td class="text-truncate">{{user.firstname}} {{user.lastname}}</td>
                                                    <td class="text-truncate">{{user.phone}}</td>
                                                    <td class="text-truncate">{{user.email}}</td>
                                                    <td class="text-truncate text-center">{{user.orders.length}}</td>
                                                    <td class="text-truncate text-center">${{Helpers.numberFormat(user.total_paid,2)}}</td>
                                                    <td style="text-align:center;"><a :href="'/api/admin/su/'+user.id"><i class="fa fa-eye text-navy"></i></a></td>
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
                        <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchCustomer()" :offset="offset"></order-paginator>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import ReportServices from '../../services/ReportServices.js';
    import Paginator from '../../../../../../components/Paginator.vue'
    import Helpers from '../../../../../../misc/helpers.js'

    export default {
        mixins:[ReportServices],
        components: {
            'order-paginator': Paginator
        },
        data() {
            return {
                users: [],
                Helpers:Helpers,
                no_found_msg: 'Customers not found',
                query:'',
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10
            }
        },
        watch:{
            query:_.debounce(function(){
                this.searchCustomer();
                },300)
        },
        methods: {
            getUsersCallback(code, response, errors) {
                if (code == '200') {
                    if(response.data.exported){
                        swal("Success!", "The report is being exported and will be sent to your email.", "success");
                    }else{
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
                        this.users = response.data;

                        for(var i in this.users){
                            if(this.users[i].orders.length > 0){
                                this.users[i].total_paid = 0.00;
                                for(var j in this.users[i].orders){
                                    this.users[i].total_paid = this.users[i].total_paid*1 + this.users[i].orders[j].paid_amount*1;
                                }
                            }else{
                                this.users[i].total_paid = 0.00;
                            }
                        }
                    }
                }else{
                    swal("Error!", errors[0], "error");
                }
                $.LoadingOverlay("hide");
            },
            print(){
                printJS({ printable: 'recent-orders', type: 'html', header: 'Sales by Date Range ('+ this.start_date + '-'+ this.end_date +')'});
            },
            searchCustomer(exported = false){
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: this.offset,
                    fields:['id','firstname','lastname','phone','email','orders'],
                    with: ['orders'],
                };

                if(this.query != ''){
                    params['query'] = '*' + this.query + '*';
                }
                if(exported){
                    params['export'] = exported;
                   
                }
                this.getUsers(params, this.getUsersCallback);
            }
        },
        created() {
            this.searchCustomer();
        },
        mounted() {
            
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