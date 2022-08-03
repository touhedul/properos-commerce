<template>
    <div class="card-body">
        <div id="accordionWrap1" role="tablist" aria-multiselectable="true" style="width: 100%">
            <div class="card collapse-icon accordion-icon-rotate" style="height: 100%">
                <div id="heading11" class="card-header">
                    <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion11" aria-expanded="false" aria-controls="accordion11"
                        class="card-title lead collapsed">Activities Log</a>
                </div>
                <div id="accordion11" role="tabpanel" aria-labelledby="heading11" class="collapse show">
                    <div class="card-content">
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4 input-group">
                                        <!-- <input type="text" v-model="query" class="form-control" id="query" placeholder="Search..."> -->
                                        <div class="form-group" style="width: 100%;">
                                            <label>User</label>
                                            <select id="select2-users" class="select2-placeholder form-control" data-placeholder="Select user..." style="width: 100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select id="select2-type" class="select2-placeholder form-control" style="width: 100%">
                                                <option value="all" selected>All</option>
                                                <option value="login">Login</option>
                                                <option value="logout">Logout</option>
                                                <option value="reset_password">Reset Password</option>
                                                <option value="event">Event</option>
                                                <option value="lead">Lead</option>
                                                <option value="note">Note</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class='input-group'>
                                                <input id="date" type='text' class="form-control daterange" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activities.length >0" id="card-activities" class="card">
                                    <div class="text-right" style="margin-bottom:10px;">
                                        <img @click="searchActivities(1, true)" class="icons-custom" src="/images/icons/excel.png" style="margin-right:5px;cursor:pointer;">
                                    </div>
                                    <div class="card-content" style="overflow-y: scroll">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="recent-activities" class="table table-hover mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>User</th>
                                                            <th>Type</th>
                                                            <th>Description</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(activity, index) in activities" :key="index">
                                                            <td class="text-truncate">{{activity.name}}</td>
                                                            <td class="text-truncate">{{activity.activity_type}}</td>
                                                            <td class="text-truncate">{{activity.description}}</td>
                                                            <td class="text-truncate">{{(activity.created_at != null) ? moment(activity.created_at, 'YYYY-MM-DD HH:ii:ss').format('MMM DD, YYYY') : '-'}}</td>
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
                                <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchActivities()" :offset="offset"></paginator>
                        </div>
                    </div>
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
                activities: [],
                no_found_msg: 'Activities Not registered yet',
                query:'',
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
                filter:{
                    user_id: 0,
                    type: 'all',
                    start_date:'',
                    end_date:''
                }
            }
        },
        watch:{
            query:_.debounce(function(){
                this.searchActivities(1);
                },300),
            'filter.user_id':_.debounce(function(){
                this.searchActivities(1);
            },300),
            'filter.type':_.debounce(function(){
                this.searchActivities(1);
            },300),
        },
        methods: {
            getActivitiesCallback(code, response, errors) {
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
                        this.activities = response.data;
                    }
                }else{
                    swal("Error!", errors[0], "error");
                }
                $.LoadingOverlay("hide");
            },
            searchActivities(page,exported=false){
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: this.offset,
                    fields:['id','user_id','name','activity_type','activity_id','created_at','description'],
                };
                if(this.filter.user_id > 0){
                    params['where'] = [
                        ['user_id', this.filter.user_id]
                    ];
                }
                if(this.filter.type != 'all'){
                    if(!params.where){
                        params['where'] = [];
                    }
                    params.where.push(['activity_type', this.filter.type]);
                }
                if(this.filter.start_date != ''){
                    if(!params.where){
                        params['where'] = [];
                    }
                    params.where.push(['created_at', '>=',this.filter.start_date]);
                }
                if(this.filter.end_date != ''){
                    if(!params.where){
                        params['where'] = [];
                    }
                    params.where.push(['created_at', '<=',this.filter.end_date]);
                }
                if(exported){
                    params['export'] = exported;
                }
                this.getActivities(params, this.getActivitiesCallback);
            }
        },
        created() {
            this.searchActivities();
        },
        mounted() {
            var self = this;
            this.$nextTick(function(){
                $("#select2-users").select2({
                    ajax: {
                        url: '/api/admin/users/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            var terms = {
                                query: '+*' + params.term.trim().replace(" ", "* +*") + '*', // search term
                                fields: ['id', 'firstname','lastname'],
                                page: params.page,
                                limit: 5
                            } 
                            return terms;
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            if(params.page == 1){
                                response.data.unshift({
                                    id:0,
                                    firstname: 'All',
                                    lastname: '',
                                });
                            }
                            return {
                                results: response.data,
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.firstname +' '+ repo.lastname + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.firstname) {
                            self.filter.user_id = repo.id;
                            return repo.firstname + ' '+ repo.lastname;
                        } else {
                            return repo.text;
                        }
                    }
                });
                $("#select2-type").on('change', function (e) {
                    self.filter.type = $("#select2-type").val()
                });

                $( '.daterange' ).daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });
                $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                    self.filter.start_date = picker.startDate.format('YYYY-MM-DD');
                    self.filter.end_date = picker.endDate.format('YYYY-MM-DD');
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    self.searchActivities(1);
                });

                $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                    self.filter.start_date = '';
                    self.filter.end_date = '';
                    $(this).val('');
                    self.searchActivities(1);
                });
            })
        }
    }
</script>
<style>
    .icons-custom{
        width:20px;
        height:20px;
    }
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        line-height: 1.25;
        color: #4E5154;
        text-align: center;
        background-color: #F5F7FA;
        border: 1px solid #D9D9D9;
        border-radius: .25rem;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
        cursor: pointer;
    }
</style>