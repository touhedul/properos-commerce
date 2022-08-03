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
                                <div class="col-12 col-md-6 input-group" style="margin-bottom:15px;padding:0;">
                                    <form style="width:100%;">
                                        <input type="text" v-model="query" class="form-control" id="query" placeholder="Search..." style="width:100%;">
                                    </form>
                                </div>
                                <ul class="list-group">
                                    <template v-if="current_categories.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-5 offset-md-1">
                                                    <strong>Name</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Active</strong>
                                                </div>
                                                <div class="col-md-3 text-center">

                                                </div>
                                            </div>
                                        </li>
                                        <li v-for="(category, index) in current_categories" class="list-group-item" :key="index">
                                            <div class="row">
                                                <div class="col-md-5 offset-md-1">
                                                    <strong>
                                                        <span>{{category.name}}</span>
                                                    </strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <span v-if="category.active == 1">Yes</span>
                                                    <span v-else>No</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-right">
                                                        <div style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-category/' + category.id" class="btn btn-primary" style="color: #fff">
                                                                <i class="fa fa-pencil d-none d-sm-inline"></i>
                                                                <span class="d-none d-xl-inline">Edit</span>
                                                            </a>
                                                        </div>
                                                        <!-- <div class="col-xs-4" style="padding: 5px">
                                                            <a class="btn btn-warning" style="color: #fff">
                                                                <i class="fa fa-eye d-none d-sm-inline"></i>
                                                                Details
                                                            </a>
                                                        </div> -->
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removeItem" @click="removeCategory(category.id)" class="btn btn-danger" style="color: #fff">
                                                                <i class="fa fa-trash-o d-none d-sm-inline"></i>
                                                                <span class="d-none d-xl-inline">Remove</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="list-group-item" style="text-align: center">
                                            <h2>There are no categories</h2>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <category-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getCategories()" :offset="offset"></category-paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        components: {
            'category-paginator': Paginator
        },
        props: ['categories'],
        data() {
            return {
                current_categories: [],
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
                query:''
            }
        },
        watch:{
            query:_.debounce(function(){
                this.getCategories();
                },300)
        },
        methods: {
            removeCategory(id) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this category",
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
                        axios.delete('/api/categories/destroy/' + id).then(response => {
                            if (response.data.status == 1) {
                                swal({ title: "Deleted!", text: response.data.message, timer: 1000, icon: "success" });
                                let index = this.current_categories.findIndex(function (item) {
                                    return item.id == id;
                                });
                                this.current_categories.splice(index, 1);
                            } else {
                                swal("Error!", response.data.errors[0], "error");
                            }
                        }).catch(error => {
                            swal("Error!", error, "error");
                        });
                    }
                });
            },
            getCategories() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: 10,
                };
                if(this.query != ''){
                    params['query'] = '*' + this.query + '*';
                }
                axios({
                    method: 'post',
                    url: '/api/admin/categories/search',
                     data: params
                }).then(response => {
                    $.LoadingOverlay("hide");
                    if (response.data.status > 0) {
                        if(response.data.data.length > 0){
                            this.paginator = response.data.pagination;
                        }else{
                            this.paginator= {
                                total: 0,
                                per_page: 2,
                                from: 1,
                                to: 0,
                                current_page: 1
                            };
                        }
                        this.current_categories = response.data.data;
                    } else {
                        console.log('handle server error from here');
                    }
                }).catch((error) => {
                    return error;
                });
            },
        },
        created() {
            if (this.categories) {
                this.paginator = this.categories;
                this.current_categories = this.categories.data;
            }
        },
        mounted() {
            console.log('Component mounted.')
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
</style>