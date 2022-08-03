<template>
    <div>
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card" style="height: 1043px;">
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
                                <form>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3 mb-2">
                                                <h4 class="form-section">Add category</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 offset-md-3 mb-2">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" v-model="category.name" name="name" id="name" class="form-control" v-bind:class="{'input-error' : error.name}"
                                                        placeholder="Type category name">
                                                    <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 offset-md-3 mb-2">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" v-model="category.description" id="description" rows="5" v-bind:class="{'input-error' : error.description}"
                                                        class="form-control" placeholder="Type category description"></textarea>
                                                    <span v-if="error.description" class="message-error">{{error_message.description}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 offset-md-3 mb-2">
                                                <div class="skin skin-square">
                                                    <div class="form-group text-right">
                                                        <fieldset>
                                                            <input type="checkbox" id="active">
                                                            <label for="active">Active</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 offset-md-3 mb-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div>
                                                            <label>Upload category pictures</label>
                                                            <form action="#" class="dropzone dropzone-area dz-clickable" v-bind:class="{'error-dropzone' : error.picture}" id="dpz-multiple-files">
                                                                <div class="dz-message">Drop Files Here To Upload</div>
                                                            </form>
                                                        </div>
                                                        <span v-if="error.picture" class="message-error">{{error_message.picture}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions right col-md-6 offset-md-3">
                                        <a href="/admin/store/categories" class="btn btn-warning mr-1">
                                            <i class="ft-x"></i> Cancel
                                        </a>
                                        <template v-if="category.id > 0">
                                            <button @click="updateCategory(category.id)" type="button" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i>
                                                <span>Update</span>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button @click="addCategory()" type="button" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i>
                                                <span>Save</span>
                                            </button>
                                        </template>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['categories', 'editable_category'],
        data() {
            return {
                current_categories: this.categories,
                category: {
                    id: '',
                    name: '',
                    description: '',
                    active: false
                },
                dropzone: {},
                error: {
                    name: false,
                    description: false,
                    picture: false
                },
                error_message: {
                    name: '',
                    description: '',
                    picture: ''
                }
            }
        },
        methods: {
            show() {
                console.log(this.category.active);
            },
            addCategory() {
                if (this.category.name != '' && this.category.description != '') {
                    var self = this;
                    axios({
                        method: 'post',
                        url: '/api/categories/store',
                        data: self.category
                    }).then(response => {
                        if (response.data.status == 1) {
                            this.category.id = response.data.data.id;
                            this.dropzone.processQueue();
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch(error => {
                        swal("Error!", error, "error");
                    });
                } else {
                    if (this.category.name == '') {
                        this.error.name = true;
                        this.error_message.name = 'Category name is required';
                    }
                    if (this.category.description == '') {
                        this.error.description = true;
                        this.error_message.description = 'Category description is required';
                    }
                    // if (this.dropzone.files.length == 0) {
                    //     this.error.picture = true;
                    //     this.error_message.picture = 'Category picture is required';
                    // }
                }

            },
            updateCategory($id) {
                if (this.category.name != '' && this.category.description != '') {
                    var self = this;
                    axios({
                        method: 'put',
                        url: '/api/categories/update/' + $id,
                        data: self.category
                    }).then(response => {
                        if (response.data.status == 1) {
                            this.category.id = response.data.data.id;
                            this.dropzone.processQueue();
                            swal({ title: "Updated!", text: response.data.message, timer: 1000, icon: "success" });
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch(error => {
                        swal("Error!", error, "error");
                    });
                }
                else {
                    if (this.category.name == '') {
                        this.error.name = true;
                        this.error_message.name = 'Category name is required';
                    }
                    if (this.category.description == '') {
                        this.error.description = true;
                        this.error_message.description = 'Category description is required';
                    }
                }
            }
        },
        watch: {
            'category.name'(val) {
                if (this.val != '') {
                    this.error.name = false;
                    this.error_message.name = '';
                }
            },
            'category.description'(val) {
                if (this.val != '') {
                    this.error.description = false;
                    this.error_message.description = '';
                }
            }
        },
        created() {
            if (this.editable_category) {
                this.category.id = this.editable_category.id ? this.editable_category.id : '';
                this.category.name = this.editable_category.name ? this.editable_category.name : '';
                this.category.description = this.editable_category.description ? this.editable_category.description : '';
            }

        },
        mounted() {
            var self = this;
            this.$nextTick(function () {

                self.category.active = self.editable_category.active;
                self.category.active == 1 ? $('#active').iCheck('check') : $('#active').iCheck('chuncheckeck');

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#active').on('ifChecked', function (event) {
                    self.category.active = 1;
                });

                $('#active').on('ifUnchecked', function (event) {
                    self.category.active = 0;
                });

                this.dropzone = Dropzone.options.dpzMultipleFiles = {
                    url: "/api/files/upload",
                    paramName: "picture",
                    maxFilesize: 10,
                    clickable: true,
                    autoProcessQueue: false,

                    parallelUploads: 2,
                    addRemoveLinks: true,
                    dictRemoveFile: " Remove",

                    sending: function (file, xhr, formData) {
                        formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
                        formData.append("owner_id", self.category.id);
                        formData.append("owner_type", "category");
                    },

                    success: function (file, response) {
                        file.id = response.data.id;
                    },

                    removedfile: function (file) {
                        if (file.id) {
                            axios({
                                method: 'delete',
                                url: '/api/files/destroy/' + file.id
                            }).then(response => {
                                if (response.data.status == 1) {
                                    file.previewElement.remove();
                                } else {
                                    swal("Error!", response.data.errors[0], "error");
                                }
                            }).catch((error) => {
                                swal("Error!", error, "error");
                            });
                        } else {
                            file.previewElement.remove();
                        }
                    },

                    complete: function (file, xhr, formData) {
                        $.LoadingOverlay("hide");
                        if (file.accepted) {
                            window.location.href = '/admin/store/categories';
                        }
                    },

                    init: function (file) {
                        self.dropzone = Dropzone.forElement("#dpz-multiple-files");
                        this.on("success", function () {
                            self.dropzone.options.autoProcessQueue = true;
                        });

                        if (self.editable_category) {
                            if (self.editable_category.files.length > 0) {
                                for (var i in self.editable_category.files) {
                                    var mockFile = {
                                        id: self.editable_category.files[i].id,
                                        name: self.editable_category.files[i].name,
                                        size: 12345,
                                        type: 'image/jpeg'
                                    };
                                    this.emit('addedfile', mockFile);
                                    this.emit('thumbnail', mockFile, '/storage/' + self.editable_category.files[i].thumb_path);
                                    mockFile.previewElement.classList.add('dz-success');
                                    mockFile.previewElement.classList.add('dz-complete');
                                }
                            }
                        }

                        this.on("thumbnail", function (file) {
                            if (file.accepted !== false) {
                                var minImageWidth = 640;
                                var maxImageWidth = 2048;
                                var minImageHeight = 480;
                                var maxImageHeight = 2048;
                                if (file.width > maxImageWidth || file.width < minImageWidth || file.height > maxImageHeight || file.height < minImageHeight) {
                                    file.rejectDimensions();
                                }
                                else {
                                    file.acceptDimensions();
                                }
                            }

                            self.error.picture = false;
                            self.error_message.picture = '';
                        });

                        this.on("error", function (file, error) {
                            if (!file.accepted) {
                                this.removeFile(file);
                                toastr.error(error, 'Picture upload error.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                            }
                        });
                    },
                    accept: function (file, done) {
                        file.acceptDimensions = done;
                        file.rejectDimensions = function () { done("Invalid dimension. Min file size : 1024x768. Max file size: 1600x1200"); };
                    }
                }
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

    .dropdown-item:focus,
    .dropdown-item:hover {
        background-color: #fff;
        color: #000;
    }

    .input-error {
        color: #d61212 !important;
        ;
        border: 1px solid #b60707 !important;
        ;
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
</style>