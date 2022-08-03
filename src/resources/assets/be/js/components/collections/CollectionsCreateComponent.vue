<template>
    <div>
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card" style="height: 100% !important;">
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
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <h4 class="form-section">Add Collection</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="userinput1">Name</label>
                                                <input type="text" v-model="collection.name" name="collection" id="collection" class="form-control" v-bind:class="{'input-error-select' : error.name}">
                                                <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 mb-2">
                                            <label for="slug">Slug</label>
                                            <input type="text" v-model="collection.slug" name="slug" id="slug" class="form-control" v-bind:class="{'input-error-select' : error.slug}" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5" style="align-self:center;">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="select2-products" class="select2-placeholder form-control" style="width: 100%">
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button @click="addProduct()" class="btn btn-primary" type="button">
                                                            <i class="ft-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p v-if="collection.items.length > 0" style="margin-bottom:4px; font-size:15px;"><strong>Products list</strong></p>
                                            <ul id="sortable" class="list-group">
                                                <li v-for="(product, key) in collection.items" class="list-group-item" :key="key" style="padding:0;" :data-previndex="product.sort_order">
                                                    <div class="row" style="padding:10px;">
                                                        <div class="col-md-5" style="align-self:center;">
                                                            ({{product.sort_order}})  {{product.name}}
                                                        </div>
                                                        <div class="col-md-5" style="align-self:center;">
                                                            <strong>Price: </strong>{{product.price}}
                                                        </div>
                                                        <div class="col-md-1 text-right" style="align-self:center;">
                                                            <div style="padding: 5px; display:inline;">
                                                                <a @click="removeProduct(key)" title="Remove product">
                                                                    <i class="fa fa-remove d-none d-sm-inline" style="color:red;font-size:18px;"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:40px;">
                                        <div class="col-md-12 text-right">
                                            <button @click="cancel()" type="button" class="btn btn-warning mr-1">
                                                <i class="ft-x"></i> Cancel
                                            </button>
                                            <button @click="saveCollection()" type="button" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="modal fade text-left show" id="locations" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Select a location</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select id="countries" v-model="temp_location.country.code" class="select2 form-control" style="width: 100%">
                                <option value="any">Any country</option>
                                <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                            </select>
                        </div>
                        <div class="form-group" v-show="temp_location.country.code != 'any'">
                            <div v-show="temp_location.country && temp_location.country.code == 'US'">
                                <select id="states" class="select2 form-control"  v-model="temp_location.state.abbr" 
                                    style="width: 100%" data-placeholder="Select state...">
                                    <option value="any">Any state</option>
                                    <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                </select>
                            </div>
                            <div class="input-group" v-show="temp_location.country && temp_location.country.code != 'US'">
                                <input type="text" class="form-control" id="state" v-model="temp_location.state.name" placeholder="State name">
                            </div>
                        </div>
                        <div class="form-group" v-if="temp_location.state.abbr != 'any' && temp_location.country.code != 'any' && temp_location.state.name != ''">
                            <div class="input-group">
                                <input type="text" class="form-control" id="city" v-model="temp_location.city.name" placeholder="City name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="addLocation()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add location</button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import Helpers from '../../../../../../misc/helpers.js';
    import CollectionsServices from '../../services/CollectionsServices';
    export default {
        props: ['editable_collection'],
        mixins: [Helpers, CollectionsServices],
        data() {
            return {
                // current_categories: this.categories,
                collection : {
                    id: 0,
                    name:'',
                    slug:'',
                    items:[]
                },

                error: {
                    name: false,
                    slug: false,
                },
                error_message: {
                    name: '',
                    slug: '',
                },
                moment:moment,
                item:{
                    id:0,
                    name:'',
                    price:'',
                    discount_percent:'',
                    sort_order:0,

                }
            }
        },
        methods: {
            initProductsList(){
                var self = this;
                this.$nextTick(function(){
                     $("#select2-products").select2({
                        placeholder: 'Select products...',
                        ajax: {
                            url: '/api/admin/items/search',
                            dataType: 'json',
                            delay: 250,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: function (params) {
                                params.term = (typeof params.term == 'undefined') ? '' : params.term;
                                return {
                                     query: '+*' + params.term.trim().replace(" ", "* +*") + '*', // search term
                                    fields: ['id', 'name','price','discount_percent','sku'],
                                    page: params.page,
                                    where: [
                                        ['active',1]
                                    ],
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
                            var markup = "<div class='select2-result-repository clearfix'>" + repo.name +""+((repo.sku) ? " ("+repo.sku+")" : "")+ "</div>";
                            return markup;
                        }, 
                        templateSelection: function (repo) {
                            if (repo.name) {
                                self.item.id = repo.id;
                                self.item.name = repo.name;
                                self.item.price = repo.price;
                                self.item.discount_percent = repo.discount_percent;
                                return repo.name
                            } else {
                                return repo.text;
                            }
                        }
                    });
                })
               
            },
            addProduct(){
                if(this.item.id >0){
                    var addData = true;
                    for(var i in this.collection.items){
                        if(this.collection.items[i].id == this.item.id){
                            addData = false;
                            break;
                        }
                    }
                    if(addData){
                        this.item.sort_order = this.collection.items.length*1 +1*1;
                        this.collection.items.push(this.item);
                    }
                    this.item = {
                        id:0,
                        name:'',
                        price:'',
                        discount_percent:'',
                        sort_order:0,
                    };

                    $('#select2-products').val(null).trigger('change');
                }
            },
            removeProduct(index){
                this.collection.items.splice(index,1);
            },
            saveCollection(){
                if(this.collection.name != ''){
                    $.LoadingOverlay("show");
                    if(this.collection.id > 0){
                        this.updateCollectionCall(this.collection, this.saveCollectionCallback);
                    }else{
                        this.createCollectionCall(this.collection, this.saveCollectionCallback);
                    }
                }else{
                    this.error.name = true;
                    this.error_message.name = 'Collection name is required'
                }
            },
            saveCollectionCallback(response){
                if(response.status > 0 ){
                    if(this.collection.id > 0){
                        toastr.success('Collection updated successfully', 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }else{
                        this.collection.id = response.data.id;
                        toastr.success('Collection created successfully', 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }else{
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
            }
        },
        watch: {
            'collection.name'(val){
                if(val != ''){
                    this.error.name = false;
                    var string = val.replace(/[^\w\s]/gi, '');
                    string = string.replace(/\s/g, '_');
                    this.collection.slug = string.toLowerCase();
                }else{
                    this.collection.slug = val;
                }
            },
            'collection.items'(val){
                if(this.collection.items.length == 1){
                   
                    // this.error.apply_to = false;
                }
            },
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            if (this.editable_collection) {
                this.collection.id = this.editable_collection.id;
                this.collection.name = this.editable_collection.name;
                this.collection.slug = this.editable_collection.slug;

                if(this.editable_collection.items && this.editable_collection.items.length > 0){
                    for(var i in this.editable_collection.items){
                        this.collection.items.push({
                            id: this.editable_collection.items[i].id,
                            name: this.editable_collection.items[i].name,
                            price: this.editable_collection.items[i].price,
                            discount_percent: this.editable_collection.items[i].discount_percent,
                            sort_order: this.editable_collection.items[i].pivot.sort_order
                        })
                    }
                }
            }
            var self = this;
            this.$nextTick(function () {
                self.initProductsList();
            });
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $(".select2").select2();

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                 $("#sortable").sortable({
                    start: function(e, ui) {
                        $(this).attr('data-previndex', ui.item.index()*1+1*1);
                    },
                    stop(event, ui){
                        var newIndex = ui.item.index()*1+1*1;
                        var oldIndex = ui.item.attr('data-previndex');
                        if(newIndex < oldIndex){
                            for(var i in self.collection.items){
                                if(self.collection.items[i].sort_order == oldIndex){
                                    self.collection.items[i].sort_order = newIndex;
                                }else if(self.collection.items[i].sort_order >= newIndex && self.collection.items[i].sort_order <= oldIndex){
                                    self.collection.items[i].sort_order = self.collection.items[i].sort_order*1+1*1;
                                }
                            }
                        }
                        if(newIndex > oldIndex){
                            for(var i in self.collection.items){
                                if(self.collection.items[i].sort_order == oldIndex){
                                    self.collection.items[i].sort_order = newIndex;
                                }else if(self.collection.items[i].sort_order <= newIndex && self.collection.items[i].sort_order > oldIndex){
                                    self.collection.items[i].sort_order = self.collection.items[i].sort_order*1-1*1;
                                }
                            }
                        }
                        // $(this).removeAttr('data-previndex');
                        self.collection.items.push();
                    }  
                });
            });
        }
    }
</script>
<style>
    form .form-section {
        border-bottom: 1px solid #eceff2;
    }

    .dz-image>img {
        width: 120px !important;
        height: auto !important;
    }

    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .error-dropzone {
        border: 2px dashed #b60707 !important;
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }

    .swal-text {
        text-align: center !important;
    }

    .dropdown-item:focus,
    .dropdown-item:hover {
        background-color: #fff;
        color: #000;
    }
</style>