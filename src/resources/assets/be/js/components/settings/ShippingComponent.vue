<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Shipping</h4>
                            </div>
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
                                <form>
                                    <a href="#" style="float: right" @click="openModal()">Add package</a>
                                    <h5 class="form-section"><i class="fa fa-inbox"></i> Packages</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div v-if="current_packages.length > 0" class="table-responsive">
                                                <table id="popular-products" class="table table-hover mb-0" >
                                                    <thead>
                                                        <tr>
                                                            <th style="border-top: none;">Name</th>
                                                            <th style="text-align: center;border-top: none;">Weight</th>
                                                            <th style="text-align: center;border-top: none;">Length</th>
                                                            <th style="text-align: center;border-top: none;">Width</th>
                                                            <th style="text-align: center;border-top: none;">Height</th>
                                                            <th style="text-align: center;border-top: none;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(pack, index) in current_packages" :key="index">
                                                            <td class="text-truncate" style="vertical-align:middle;">{{pack.name}}</td>
                                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{pack.weight}} ({{pack.weight_unit}})</td>
                                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{pack.length}} ({{pack.dimension_unit}})</td>
                                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{pack.width}} ({{pack.dimension_unit}})</td>
                                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">{{pack.height}} ({{pack.dimension_unit}})</td>
                                                            <td class="text-truncate" style="text-align: right;">
                                                                <a @click="openModal(pack.id)" class="btn btn-primary"
                                                                    style="color: #fff; margin-top:2px; margin-bottom:2px;" title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <a id="removePackage" @click="removePackage(pack.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <packages-paginator style="margin-top:10px;" v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchPackage()" :offset="package_offset"></packages-paginator>
                                            </div>
                                            <div v-else class="table-responsive">
                                                <div style="text-align: center">
                                                    <h6 style="color:#777;">No packages added</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-left show" id="packages-type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel1">Add package</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div>
                                        <label class="label-control" for="packege_name">Packege Name</label>
                                        <input type="text"  class="form-control" id="packege_name"  v-model="package.name"  v-bind:class="{'input-error-select' : error.name}">
                                        <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:10px;">
                                    <div>
                                        <label>Weight</label>
                                        <fieldset>
                                            <div class="input-group">
                                                <input v-model="package.weight" type="text" class="form-control" name="weight" id="weight" v-bind:class="{'input-error-select' : error.weight}"
                                                    >
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{package.weight_unit}}
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a v-for="(w_u,index) in weight_units" :key="index" class="dropdown-item" @click="setWeightUnit(w_u)">{{w_u}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-if="error.weight" class="message-error">{{error_message.weight}}</span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:10px;">
                                    <div>
                                        <label>Width</label>
                                        <fieldset>
                                            <div class="input-group">
                                                <input v-model="package.width" type="text" class="form-control" name="width" id="width" v-bind:class="{'input-error-select' : error.width}"
                                                    >
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{package.dimension_unit}}
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a v-for="(w_u,index) in length_units" :key="index" class="dropdown-item" @click="setDimensionUnit(w_u)">{{w_u}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-if="error.width" class="message-error">{{error_message.width}}</span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:10px;">
                                    <div>
                                        <label>Height</label>
                                        <fieldset>
                                            <div class="input-group">
                                                <input v-model="package.height" type="text" class="form-control" name="height" id="height" v-bind:class="{'input-error-select' : error.height}"
                                                    >
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{package.dimension_unit}}
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a v-for="(w_u,index) in length_units" :key="index" class="dropdown-item" @click="setDimensionUnit(w_u)">{{w_u}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-if="error.height" class="message-error">{{error_message.height}}</span>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top:10px;">
                                    <div>
                                        <label>Length</label>
                                        <fieldset>
                                            <div class="input-group">
                                                <input v-model="package.length" type="text" class="form-control" name="length" id="length" v-bind:class="{'input-error-select' : error.length}"
                                                    >
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{package.dimension_unit}}
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a v-for="(w_u,index) in length_units" :key="index" class="dropdown-item" @click="setDimensionUnit(w_u)">{{w_u}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span v-if="error.length" class="message-error">{{error_message.length}}</span>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                            <button @click="addPackage()" type="button" class="btn btn-outline-primary" id="onhidebtn">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SettingServices from '../../services/SettingServices.js';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins:[SettingServices],
        props:['shipping'],
        components: {
            'packages-paginator': Paginator
        },
        data() {
            return {
                package:{
                    id: 0,
                    name:'',
                    weight:'',
                    weight_unit:'lb',
                    width:'',
                    height:'',
                    length:'',
                    dimension_unit:'in'
                },
                current_packages:[],
                packages:[],
                weight_units: ['lb', 'oz', 'g', 'kg'],
                length_units: ['mm', 'cm', 'in'],
                error:{
                    name:false,
                    weight:false,
                    width:false,
                    height:false,
                    length:false,
                },
                error_message:{
                    name:'',
                    weight:'',
                    width:'',
                    height:'',
                    length:'',
                },
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                package_offset:4
            }
        },
        watch:{
            'package.name'(val){
                if(val != ''){
                    this.error.name = false;
                }
            },
            'package.weight'(val){
                if(val != ''){
                    this.error.weight = false;
                }
            },
            'package.width'(val){
                if(val != ''){
                    this.error.width = false;
                }
            },
            'package.height'(val){
                if(val != ''){
                    this.error.height = false;
                }
            },
            'package.length'(val){
                if(val != ''){
                    this.error.length = false;
                }
            }
        },
        methods: {
            // save(){

            //     $.LoadingOverlay("show");
            //     this.updateSettings(params, this.updateSettingsCallback);
                
            // },
            addPackage(){
                if(this.validatePackage()){
                    $.LoadingOverlay("show");
                    if(this.package.id > 0){
                        this.updatePackageCall(this.package, this.addPackageCallback);
                    }else{
                        this.addPackageCall(this.package, this.addPackageCallback);
                    }
                }
            },
            addPackageCallback(response){
                if(response.status > 0){
                    $('#packages-type').modal('hide');
                    this.searchPackage();
                    $.LoadingOverlay("hide");
                    this.package = {
                        id: 0,
                        name:'',
                        weight:'',
                        weight_unit:'lb',
                        width:'',
                        height:'',
                        length:'',
                        dimension_unit:'in'
                    };
                }else{
                     if(Helpers.isAssoc(response.data.errors) > 0){
                        for(let field in response.data.errors){
                            this.error_message[field] = response.data.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', response.data.errors[0], 'error');
                    }
                    $.LoadingOverlay("hide");
                }
            },
            validatePackage(){
                if(this.package.name != '' && this.package.weight != '' && this.package.weight_unit != '' && this.package.width != '' 
                && this.package.height != '' && this.package.length != '' && this.package.dimension_unit != ''){
                    return true;
                }else{
                    if(this.package.name == ''){
                        this.error.name = true;
                        this.error_message.name = 'Package name is required';
                    }
                    if(this.package.weight == ''){
                        this.error.weight = true;
                        this.error_message.weight = 'Package weight is required';
                    }
                    if(this.package.length == ''){
                        this.error.length = true;
                        this.error_message.length = 'Package length is required';
                    }
                    if(this.package.width == ''){
                        this.error.width = true;
                        this.error_message.width = 'Package width is required';
                    }
                    if(this.package.height == ''){
                        this.error.height = true;
                        this.error_message.height = 'Package height is required';
                    }
                }
            },
            searchPackage(page) {
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: this.package_offset,
                };
                this.searchPackagesCall(params, this.searchPackagesCallback);
            },
            searchPackagesCallback(code, response, errors){
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
                    this.current_packages = response.data;
                }else{
                    swal("Error!", response.errors[0], "error");
                }
                $.LoadingOverlay("hide");
            },
            openModal(id = 0){
                if(id > 0){
                    for(var i in this.current_packages){
                        if(this.current_packages[i].id == id){
                            this.package.id = this.current_packages[i].id;
                            this.package.name = this.current_packages[i].name;
                            this.package.weight = this.current_packages[i].weight;
                            this.package.weight_unit = this.current_packages[i].weight_unit;
                            this.package.length = this.current_packages[i].length;
                            this.package.width = this.current_packages[i].width;
                            this.package.height = this.current_packages[i].height;
                            this.package.dimension_unit = this.current_packages[i].dimension_unit;
                        }
                    }
                }
                setTimeout(function(){
                    $("#packages-type").modal('show');
                },200);
            },
            setWeightUnit(w_u) {
                this.package.weight_unit = w_u;
            },
            setDimensionUnit(d_u) {
                this.package.dimension_unit = d_u;
            },
            removePackage(id) {
                var _this = this;
                swal({
                    title: "Are you sure?",
                    text: "This action will remove the package",
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
                        _this.removePackageCall(id, _this.removePackageCallback);
                    }
                });
            },
            removePackageCallback(response){
                var _this = this;
                if (response.data.status == 1) {
                    swal({ title: "Deleted!", text: response.data.message, timer: 1000, icon: "success" });
                    let index = this.current_packages.findIndex(function (item) {
                        return item.id == _this.id;
                    });
                    this.current_packages.splice(index, 1);
                    this.package = {
                        id: 0,
                        name:'',
                        weight:'',
                        weight_unit:'lb',
                        width:'',
                        height:'',
                        length:'',
                        dimension_unit:'in'
                    };
                } else {
                    swal("Error!", response.data.errors[0], "error");
                }
            }
        },
        created() {
            this.searchPackage();
        },
        mounted() {
            var _this = this;
            this.$nextTick(function () {
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#packages-type').on('hide.bs.modal', function(){
                    _this.package = {
                        id: 0,
                        name:'',
                        weight:'',
                        weight_unit:'lb',
                        width:'',
                        height:'',
                        length:'',
                        dimension_unit:'in'
                    };
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
</style>