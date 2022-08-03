<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Content Management</h4>
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
                                <home-page-settings @fillKeywords="keywordsHanlde"></home-page-settings>
                                <form style="margin-top:40px;">
                                    <h5 class="form-section"><i class="fa fa-font"></i> Default Keywords</h5>
                                    <div class="row">
                                        <div class="col-12" id="home-page-keywords">
                                            <fieldset class="form-group">
                                                <label for="basicInput">
                                                    <b>Keywords</b>
                                                </label>
                                                <textarea rows="4" type="text" v-model="keywords" class="form-control"></textarea>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top:40px;">
                                    <h5 class="form-section"><i class="fa fa-font"></i> Announcement</h5>
                                    <div class="row">
                                        <div class="col-md-8 col-12">
                                            <div id="summernote-announcement" class="summer_n"></div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div>
                                                <label class="label-control" for="text-color" style="font-size: 12px; " >Text Color</label><br>
                                                <input type='text' id="text-color"/>
                                            </div>
                                            <div style="margin-top:10px;">
                                                <label class="label-control" for="text-color" style="font-size: 12px;" >Background Color</label><br>
                                                <input type='text' id="background-color"/>
                                            </div>
                                            <div style="margin-top:20px;">
                                                 <div class="skin skin-square">
                                                    <div class="form-group">
                                                        <fieldset>
                                                            <input type="checkbox" id="active">
                                                            <label for="active">Active</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top:40px;">
                                    <h5 class="form-section"><i class="ft-box"></i> Products Page</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="margin:0;font-size:12px;padding-right:4px;color:#777;font-weight:400;">Sort By:</label>
                                            <select id="sortby" class="select2" style="font-size:12px;width:200px;">
                                                <option value="newest" selected>Newest to Oldest</option>
                                                <option value="oldest">Oldest to Newest</option>
                                                <option value="cheapest">Cheapest to Priciest</option>
                                                <option value="priciest">Priciest to Cheapest</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-actions right" style="margin-top:20px;">
                                    <div class="text-right">
                                        <button @click="save()" class="btn btn-secondary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SettingServices from '../../services/SettingServices.js';
    import HomePage from './HomePageComponent.vue'
    export default {
        mixins:[SettingServices],
        props:['communication'],
        components: {
            'home-page-settings': HomePage
        },
        data() {
            return {
                active: false,
                content:'',
                text_color:'#000',
                background_color:'#fff',
                error:{
                    content:false
                },
                error_message:{
                    content:''
                },
                keywords:'',
                sort:'newest'
            }
        },
        methods: {
            keywordsHanlde(val){
                this.keywords = val;
            },
            save(){
                var params = {
                    key: 'communication',
                    data:{
                        announcement: {
                            message: $('#summernote-announcement').summernote('code'),
                            text_color:  this.text_color,
                            background_color:  this.background_color,
                            active: this.active
                        },
                        products_sort: this.sort,
                        keywords: this.keywords
                    }
                    
                };
                $.LoadingOverlay("show");
                this.updateSettings(params, this.updateSettingsCallback);
                
            },
            updateSettingsCallback(response){
                if(response.status >0){
                    $.LoadingOverlay("hide");
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                     swal("Success!", 'Set up content successfully', "success");
                }else{
                    swal('Error', response.errors[0], 'error');
                    $.LoadingOverlay("hide");
                }
            }
        },
        created() {
            if(this.communication){
                var communication = JSON.parse(this.communication.data);
                if(communication.announcement){
                    this.background_color = communication.announcement.background_color;
                    this.text_color = communication.announcement.text_color;
                }
                
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#active').on('ifChecked', function (event) {
                    self.active = true;
                });

                $('#active').on('ifUnchecked', function (event) {
                    self.active = false;
                });

                $("#text-color").spectrum({
                   color: self.text_color,
                    allowEmpty: true,
                    showPaletteOnly: true,
                    togglePaletteOnly: true,
                    togglePaletteMoreText: 'more',
                    togglePaletteLessText: 'less',
                    palette: [
                        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                        ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                        ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                        ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                        ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                        ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                        ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
                    ],
                    change: function(color) {
                        self.text_color = color.toHexString(); // #ff0000
                    }
                });
                $("#background-color").spectrum({
                    color: self.background_color,
                    allowEmpty: true,
                    showPaletteOnly: true,
                    togglePaletteOnly: true,
                    togglePaletteMoreText: 'more',
                    togglePaletteLessText: 'less',
                    palette: [
                        ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
                        ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
                        ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
                        ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
                        ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
                        ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
                        ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
                        ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
                    ],
                    change: function(color) {
                        self.background_color = color.toHexString(); // #ff0000
                    }
                });

                $('#summernote-announcement').summernote({
                    placeholder: 'Type Message',
                    height: 150,
                    minHeight: null,
                    maxHeight: null,
                    focus: true,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['misc', ['codeview']]
                    ],
                    popover: false,
                    callbacks: {
                        onKeyup: function (e) {
                            self.content = this.code;
                            if (this.code != '') {
                                self.error.content = false;
                                self.error_message.content = '';
                                $('.note-editor').css('border-color', '#a9a9a9');
                            }
                        }
                    }
                });

                if(self.communication){
                    var communication = JSON.parse(self.communication.data);
                    if(communication.announcement){
                        $('#summernote-announcement').summernote('code', communication.announcement.message);
                        communication.announcement.active == true ? $('#active').iCheck('check') : $('#active').iCheck('uncheck');
                    }
                }

                $("#sortby").select2({
                    minimumResultsForSearch: -1,
                    matcher: self.matchCustom
                });

                $('#sortby').on('change', function (e) {
                    self.sort = $('#sortby').val();
                });
                if(self.communication){
                    var communication = JSON.parse(self.communication.data);
                    if(communication.products_sort){
                        self.sort = communication.products_sort;
                        $("#sortby").val(self.sort).trigger('change');
                    }
                }

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