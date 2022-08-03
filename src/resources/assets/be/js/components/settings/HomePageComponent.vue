<template>
    <div>
        <form>
            <div class="row">
                <div class="col-12">
                    <a href="#" style="float: right" @click="showAddCollection()">Add collection</a>
                    <h5 class="form-section"><i class="fa fa-home"></i> Home Page Collections</h5>
                    <div class="row">
                        <div class="col-12">
                            <div v-if="collections && collections.length > 0" class="table-responsive">
                                <table class="table table-hover mb-0" >
                                    <thead>
                                        <tr>
                                            <th style="border-top: none;">Title</th>
                                            <th style="text-align: center;border-top: none;">Collection Name</th>
                                            <th style="text-align: center;border-top: none;">Item quantity</th>
                                            <th style="text-align: center;border-top: none;">Order</th>
                                            <th style="text-align: center;border-top: none;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(collection, key) in collections" :key="key">
                                            <td class="text-truncate" style="vertical-align:middle;text-transform:capitalize;">{{collection.title}}</td>
                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;text-transform:capitalize;">{{collection.collection_name}}</td>
                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{collection.max_items}}</td>
                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{(collection.random) ? 'random' : 'order'}}</td>
                                            <td class="text-truncate" style="text-align: right;">
                                                <a @click="editCollection(collection)" class="btn btn-primary"
                                                    style="color: #fff; margin-top:2px; margin-bottom:2px;" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a id="removeCollection" @click="removeCollectionSetting(collection.collection_id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getHomePageSettings()" :offset="offset"></paginator>
                            </div>
                            <div v-else class="table-responsive">
                                <div style="text-align: center">
                                    <h6 style="color:#777;">No collections selected on home page</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-left" id="create-collections-modal" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom: 1px solid #98a4b845;">
                                <h4 v-if="setting.collection_id > 0" class="modal-title" id="myModalLabel1">Edit collection</h4>
                                <h4 v-else class="modal-title" id="myModalLabel1">Add new collection</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group mb-1 col-12">
                                        <label for="label">Title</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="setting.title" v-bind:class="{'input-error-select' : error.title}" class="form-control"
                                                id="title">
                                            <span v-if="error.title" class="message-error">{{error_message.title}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-1 col-12">
                                        <label for="label">Collections</label>
                                        <br>
                                        <div>
                                            <select id="select2-collections" class="select2-placeholder form-control" style="width: 100%"></select>
                                            <span v-if="error.collection" class="message-error">{{error_message.collection}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-1 col-12">
                                        <label for="label">Maximum Items</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="setting.max_items" v-bind:class="{'input-error-select' : error.max_items}" class="form-control"
                                                id="max_items">
                                            <span v-if="error.max_items" class="message-error">{{error_message.max_items}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12" style="margin-top:20px;">
                                        <div class="skin skin-square">
                                            <div class="form-group">
                                                <fieldset>
                                                    <input type="checkbox" id="random-option">
                                                    <label for="random-option">Random</label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top:none;padding-top:0px;">
                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-outline-primary" @click="saveSettings()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import SettingServices from '../../services/SettingServices.js';
    import OrdersServices from '../../services/OrdersServices';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins:[SettingServices, OrdersServices],
        components: {
            'paginator': Paginator
        },
        data() {
            return {
                setting:{
                    title:'',
                    collection_id:0,
                    collection_name:'',
                    max_items:'',
                    random: false
                },
                // keywords:'',
                // write_keywords:'',
                error:{
                    title: false,
                    collection:false,
                    max_items:false,
                },
                error_message:{
                    title: '',
                    collection:'',
                    max_items:'',
                },
                collections:[],
                paginator: {
                    total: 0,
                    per_page: 4,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    data_length: 0,
                    last_page: 0
                },
                offset:5,
                disabled: false,
                // edit_home_page_keywords: false,
            }
        },
        methods: {
            showAddCollection(){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                this.setting.title = '';
                this.setting.collection_id = 0;
                this.setting.collection_name = '';
                this.setting.max_items = '';
                this.setting.random = false;

                setTimeout(function(){
                    $('#select2-collections').val(null).trigger('change');
                    $("#create-collections-modal").modal('show');
                },50)
            },
            editCollection(val){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                this.setting.title = val.title;
                this.setting.collection_id = val.collection_id;
                this.setting.collection_name = val.collection_name;
                this.setting.max_items = val.max_items;
                this.setting.random = val.random;

                if(this.setting.random){
                    $("#random-option").iCheck('check');
                }

                let data = {
                    name: this.setting.collection_name,
                    id: this.setting.collection_id
                };
                var option = new Option(data.name, data.id, true, true);
                $("#select2-collections").append(option).trigger('change');
                $("#select2-collections").trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });

                setTimeout(function(){
                    $("#create-collections-modal").modal('show');
                },100)
            },
            saveSettings(){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                if(this.setting.title != '' && this.setting.max_items > 0 && this.setting.collection_id > 0){
                    $.LoadingOverlay("show");
                    var collectionFound = false;
                    for(var i in this.collections){
                        if(this.collections[i].collection_id == this.setting.collection_id){
                            collectionFound = true;
                            this.collections[i].title = this.setting.title;
                            this.collections[i].max_items = this.setting.max_items;
                        }
                    }
                    if(!collectionFound){
                        this.collections.push(this.setting);
                    }
                    var params = {
                        key: 'homepage',
                        data:{
                            collections: this.collections,
                            keywords: this.keywords
                        }
                    }
                    this.updateSettings(params, this.saveSettingsCallback);

                }else{
                    if(this.setting.title == ''){
                        this.error.title = true;
                        this.error_message.title = 'Title is required';
                    }
                    if(this.setting.max_items == '' || this.setting.max_items <= 0){
                        this.error.max_items = true;
                        this.error_message.max_items = 'Max Items is required';
                    }
                    if(this.setting.collection_id <= 0){
                        this.error.collection = true;
                        this.error_message.collection = 'Collection is required';
                    }
                }
            },
            saveSettingsCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getHomePageSettings();
                    this.edit_home_page_keywords = false;
                    $("#create-collections-modal").modal('hide');
                }else{
                    if (Helpers.isAssoc(response.errors)) {
                        let errors = [];
                        for (var i in response.errors) {
                            var string
                            errors.push('<span>' + response.errors[i] + '</span></br>')
                            this.error[i] = true;
                            this.error_message[i] = response.errors[i][0];
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            getHomePageSettings() {
                $.LoadingOverlay("show");
                this.getHomePageSettingsCall(this.getHomePageSettingsCallback);
            },
            getHomePageSettingsCallback(response) {
                $.LoadingOverlay("hide");
                if (response.status == 1) {
                    this.collections = response.data.collections;
                    this.$emit('fillKeywords', response.data.keywords);
                } else {
                    toastr.error(response.errors[0], "An error has occurred", {
                    positionClass: "toast-top-center",
                    containerId: "toast-top-center"
                    });
                }
            },
            removeCollectionSetting(id) {
                var _this = this;
                swal({
                    title: "Are you sure?",
                    text: "Please confirm you want to remove this collection.",
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
                         $.LoadingOverlay("show");
                        for(var i in _this.collections){
                            if(_this.collections[i].collection_id == id){
                                _this.collections.splice(i,1);
                            }
                        }
                        var params = {
                            key: 'homepage',
                            data:{
                                collections: _this.collections
                            }
                        }
                        _this.updateSettings(params, _this.removeCollectionSettingCallback);
                    }
                });
            },
            removeCollectionSettingCallback(response) {
                if (response.status == 1) {
                    toastr.success('Collection deleted successfully', 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                } else {
                    if (Helpers.isAssoc(response.errors)) {
                        let errors = [];
                        for (var i in response.errors) {
                            var string
                            errors.push('<span>' + response.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
                $.LoadingOverlay("hide");
            },
        },
        created() {
            this.getHomePageSettings();
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {

                 $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
                $('#random-option').on('ifChecked', function (event) {
                    self.setting.random = true;
                });

                $('#random-option').on('ifUnchecked', function (event) {
                    self.setting.random = false;
                });

                $("#select2-collections").select2({
                    placeholder: 'Select collection...',
                    ajax: {
                        url: '/api/collections/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            return {
                                query: params.term + '*', // search term
                                fields: ['id', 'names','price','discount_percent'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
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
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.name) {
                            self.setting.collection_id = repo.id;
                            self.setting.collection_name = repo.name;
                            return repo.name
                        } else {
                            return repo.text;
                        }
                    }
                });
                
                $("#create-collections-modal").on('hide.bs.modal', function(){
                    self.setting.title = '';
                    self.setting.collection_id = 0;
                    self.setting.collection_name = '';
                    self.setting.max_items = '';
                    $("#random-option").iCheck('uncheck');
                    $('#select2-collections').val(null).trigger('change');
                })
            });
        }
    }
</script>
<style>

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
    .edit-class{
        display: none;
    }
    #home-page-keywords:hover .edit-class{
        display: inline-block;
    }
</style>