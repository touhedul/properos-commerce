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
                                </div>
                                <ul class="list-group">
                                    <template v-if="current_collections.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Name</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Slug</strong>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <strong>Total Products</strong>
                                                </div>
                                                <div class="col-md-2 text-center">

                                                </div>
                                            </div>
                                        </li>
                                        <li v-for="(collection, key) in current_collections" class="list-group-item" :key="key">
                                            <div class="row">
                                                <div class="col-md-3" style="padding-top: 10px">
                                                    {{collection.name}}
                                                </div>
                                                <div class="col-md-3" style="padding-top: 10px">
                                                    {{collection.slug}}
                                                </div>
                                                <div class="col-md-3 text-center" style="padding-top: 10px">
                                                    {{(collection.items) ? collection.items.length : 0}}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-right" style="width: 100%">
                                                        <div style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-collection/' + collection.id" class="btn btn-primary"
                                                                style="color: #fff; margin-top:2; margin-bottom:2px;" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </div>
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removeCollection" @click="removeCollection(collection.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="list-group-item" style="text-align: center">
                                            <h2>There are no collections</h2>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getCollections()" :offset="offset"></paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginator from '../../../../../../components/Paginator.vue'
    import AdminApiCalls from '../../services/CollectionsServices';
    export default {
        components: {
            'paginator': Paginator
        },
        mixins:[AdminApiCalls],
        data() {
            return {
                moment:moment,
                current_collections: [],
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
            status:_.debounce(function(){
                this.getCollections();
                },300),
            query:_.debounce(function(){
                this.getCollections();
                },300)
        },
        methods: {
            removeCollection(id) {
                var _this = this;
                swal({
                    title: "Are you sure?",
                    text: "This action will remove the collection and all it's related information",
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
                        $.LoadingOverlay("show");
                        _this.removeCollectionCall(id, _this.removeCollectionCallback);
                    }
                });
            },
            removeCollectionCallback(response){
                if (response.status == 1) {
                    swal({ title: "Deleted!", text: response.message, timer: 1000, icon: "success" });
                    let index = this.current_collections.findIndex(function (item) {
                        return item.id == response.data.id;
                    });
                    this.current_collections.splice(index, 1);
                } else {
                    swal("Error!", response.errors[0], "error");
                }
                 $.LoadingOverlay("hide");
            },
            getCollections() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: 10,
                    with: ['items'],
                    orderby:{
                        created_at: 'DESC'
                    },
                    fields: ['id','name','slug'],
                };
                if(this.query != ''){
                    params['query'] = '*' + this.query + '*';
                }
                this.searchCollections(params, this.searchCollectionsCallback);
            },
            searchCollectionsCallback(code, response, errors){
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
                    this.current_collections = response.data;
                }else{
                    swal("Error!", response.errors[0], "error");
                }
                $.LoadingOverlay("hide");
            }
        },
        created() {
            this.getCollections();
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