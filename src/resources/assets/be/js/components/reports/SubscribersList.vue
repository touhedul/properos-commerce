<template>
    <div>
        <div id="heading11" class="card-header">
            <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion11" aria-expanded="false" aria-controls="accordion11"
                class="card-title lead collapsed">Newsletter Subscribers</a>
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
                        <div v-if="subscribers.length >0" id="card-subscribers" class="card">
                            <!-- <div class="card-header"> -->
                                <!-- <h4 class="card-title">customers in this range of dates</h4> -->
                                <!-- <a class="heading-elements-toggle">
                                    <i class="fa fa-ellipsis-v font-medium-3"></i>
                                </a> -->
                            <!-- </div> -->
                            <div class="text-right" style="margin-bottom:10px;">
                                <img @click="searchSubscriber(true)" class="icons-custom" src="/images/icons/excel.png" style="margin-right:5px;cursor:pointer;">
                            </div>
                            <div class="card-content" style="overflow-y: scroll">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="recent-subscribers" class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Join At</th>
                                                    <th>Leave At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user, index) in subscribers" :key="index">
                                                    <td class="text-truncate">{{user.email}}</td>
                                                    <td class="text-truncate">{{moment(user.created_at, 'YYYY-MM-DD HH:ii:ss').format('MMM DD, YYYY')}}</td>
                                                    <td class="text-truncate">{{(user.deleted_at != null) ? moment(user.deleted_at, 'YYYY-MM-DD HH:ii:ss').format('MMM DD, YYYY') : '-'}}</td>
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
                        <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchSubscriber()" :offset="offset"></paginator>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import ReportServices from '../../services/ReportServices.js';
    import Paginator from '../../../../../../components/Paginator.vue'

    export default {
        mixins:[ReportServices],
        components: {
            'paginator': Paginator
        },
        data() {
            return {
                moment:moment,
                subscribers: [],
                no_found_msg: 'Newsletter Subscribers Not found',
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
                    this.searchSubscriber();
                    },300)
        },
        methods: {
            getSubscribersCallback(code, response, errors) {
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
                        this.subscribers = response.data;
                    }
                }else{
                    swal("Error!", errors[0], "error");
                }
                $.LoadingOverlay("hide");
            },
            searchSubscriber(exported=false){
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: this.offset,
                    fields:['id','email','created_at','deleted_at'],
                    withTrashed: true,
                };
                if(this.query != ''){
                    params['query'] = '*' + this.query + '*';
                }
                if(exported){
                    params['export'] = exported;
                }
                this.getSubscribers(params, this.getSubscribersCallback);
            }
        },
        created() {
            $.LoadingOverlay("show");
            this.getSubscribers({
                page: this.paginator.current_page,
                limit: this.offset,
                fields:['id','email','created_at','deleted_at'],
                withTrashed: true
            }, this.getSubscribersCallback);
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