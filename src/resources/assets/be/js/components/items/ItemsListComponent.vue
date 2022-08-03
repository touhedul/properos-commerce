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
                                <ul class="list-group">
                                    <template v-if="current_items.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-1">
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Name</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Price</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Qty on hand</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>Visibility</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>SKU</strong>
                                                </div>
                                                <div class="col-md-1">
                                                    <strong>UPC</strong>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <strong>Marketplace</strong>
                                                </div>
                                                <div class="col-md-2 text-center">

                                                </div>
                                            </div>
                                        </li>
                                        <li v-for="(product, key) in current_items" class="list-group-item" :key="key">
                                            <div class="row">
                                                <div class="col-md-1" style="align-self:center;">
                                                    <img v-if="product.files.length > 0 && product.files[0].thumb_path != null" :src="'/storage/' + product.files[0].thumb_path" alt="..." class="img-thumbnail">
                                                    <img v-else src="/images/placeholder/item-placeholder.jpg" alt="..." class="img-thumbnail">
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    <b>{{product.name}}</b>
                                                </div>
                                                <div class="col-md-1" style="align-self:center;">
                                                    ${{product.price}}
                                                </div>
                                                <div class="col-md-1" style="align-self:center;">
                                                    {{product.total_qty}}
                                                </div>
                                                <div class="col-md-1" style="align-self:center;">
                                                    <span v-if="product.active">visible</span>
                                                    <span v-else>hidden</span>
                                                </div>
                                                <div class="col-md-1" style="align-self:center;">
                                                    <span v-if="product.sku">{{product.sku}}</span>
                                                    <span v-else>N/A</span>
                                                </div>
                                                <div class="col-md-1" style="align-self:center;">
                                                    <span v-if="product.upc">{{product.upc}}</span>
                                                    <span v-else>N/A</span>
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    <ul v-if="product.marketplace_items.length > 0" style="padding-left: 0px;">
                                                        <li v-for="(marketplace_item, index) in product.marketplace_items" style="list-style: none" :key="index">
                                                            <img :src="'/images/marketplace/' + marketplace_item.name + '.png'" class="icon-marketplace">
                                                        </li>
                                                    </ul>
                                                    <div v-else style="font-size: 14px; text-align:center;">none</div>
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    <div class="text-right">
                                                        <div tyle="padding: 5px" style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-item/' + product.id" class="btn btn-primary" style="color: #fff" title="Edit">
                                                                <i class="fa fa-pencil d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                        <!--  <div class="col-xs-4" style="padding: 5px">
                                                            <a class="btn btn-warning" style="color: #fff">
                                                                <i class="fa fa-eye d-none d-sm-inline"></i>
                                                                Details
                                                            </a>
                                                        </div> -->
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removeItem" @click="removeItem(product.id)" class="btn btn-danger" style="color: #fff" title="Remove">
                                                                <i class="fa fa-trash-o d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="list-group-item" style="text-align: center">
                                            <h2>There are no items</h2>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <item-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getItems()" :offset="offset"></item-paginator>
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
            'item-paginator': Paginator
        },
        props: ['items'],
        data() {
            return {
                current_items: [],
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
        methods: {
            removeItem(id) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this item",
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
                        axios.delete('/api/items/destroy/' + id).then(response => {
                            if (response.data.status == 1) {
                                swal({ title: "Deleted!", text: response.data.message, timer: 1000, icon: "success" });
                                let index = this.current_items.findIndex(function (item) {
                                    return item.id == id;
                                });
                                this.current_items.splice(index, 1);
                            } else {
                                swal("Error!", response.data.errors[0], "error");
                            }
                        }).catch(error => {
                            swal("Error!", error, "error");
                        });
                    }
                });
            },
            getItems() {
                $.LoadingOverlay("show");
                axios.get(`/api/admin/items?page=${this.paginator.current_page}`)
                    .then((response) => {
                        $.LoadingOverlay("hide");
                        this.paginator = response.data;
                        this.current_items = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
        },
        created() {
            if (this.items) {
                this.paginator = this.items;
                this.current_items = this.items.data;
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

    .icon-marketplace {
        width: 50px;
        height: auto;
    }

    .img-thumbnail {
        max-width: 100%;
        background-color: #fff;
        border: 0px;
        border-radius: 0px;
    }

    .center-middle {
        text-align: left;
        vertical-align: middle;
        line-height: 100px;
    }
</style>