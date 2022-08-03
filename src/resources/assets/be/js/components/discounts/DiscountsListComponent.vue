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
                                    <template v-if="current_discounts.length > 0">
                                        <li id="first" class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Code</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Start</strong>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>End</strong>
                                                </div>
                                                <div class="col-md-2 text-center">

                                                </div>
                                            </div>
                                        </li>
                                        <li v-for="(discount, key) in current_discounts" class="list-group-item" :key="key">
                                            <div class="row">
                                                <div class="col-md-6" style="align-self:center;">
                                                    <a :href="'/admin/store/edit-discount/' + discount.id"><b>{{discount.code}}</b></a>
                                                    <p v-if="discount.discount_type=='percentage'" style="font-size:11px; font-weight:bold;margin:0;">
                                                        <span>{{(discount.discount_value % 1 != 0) ? discount.discount_value : (discount.discount_value*1).toFixed()}}% off</span>
                                                        <span v-if="discount.apply_to == 'order'"> on entire order.</span>
                                                        <span v-else-if="discount.apply_to == 'products'"> on selected items.</span>
                                                        <span v-else-if="discount.apply_to == 'categories'"> on selected categories.</span>
                                                        <!-- <span> • </span> -->
                                                    </p>
                                                    <p v-if="discount.discount_type=='fixed_amount'" style="font-size:11px; font-weight:bold;margin:0;">
                                                        <span>${{(discount.discount_value*1).toFixed(2)}} off</span>
                                                        <span v-if="discount.apply_to == 'order'"> on entire order.</span>
                                                        <span v-else-if="discount.apply_to == 'products'"> on selected items.</span>
                                                        <span v-else-if="discount.apply_to == 'categories'"> on selected categories.</span>
                                                        <!-- <span> • </span> -->
                                                    </p>
                                                    <p v-if="discount.discount_type=='free_shipping'" style="font-size:11px; font-weight:bold;margin:0;">
                                                        <span>Free shipping</span>
                                                        <span v-if="discount.apply_to == 'order'"> on entire order.</span>
                                                        <span v-else-if="discount.apply_to == 'products'"> on selected items.</span>
                                                        <span v-else-if="discount.apply_to == 'categories'"> on selected categories.</span>
                                                        <!-- <span> • </span> -->
                                                    </p>
                                                    <p v-if="discount.discount_type=='buy_x_get_y'" style="font-size:11px; font-weight:bold;margin:0;">
                                                        <span>Buy {{discount.buy_qty}}, get {{discount.get_qty}}</span>
                                                        <span v-if="discount.discount_value != null && discount.discount_value < 100">
                                                            at {{(discount.discount_value % 1 != 0) ? discount.discount_value : (discount.discount_value*1).toFixed()}}% off
                                                        </span>
                                                        <!-- <span> • </span> -->
                                                    </p>
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    {{moment(discount.start_date, 'YYYY-MM-DD HH:mm:ss').format('MMM DD')}}
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    {{(discount.end_date != null) ? moment(discount.end_date, 'YYYY-MM-DD HH:mm:ss').format('MMM DD') : '-'}}
                                                </div>
                                                <div class="col-md-2" style="align-self:center;">
                                                    <div class="text-right">
                                                        <div tyle="padding: 5px" style="padding: 5px; display:inline;">
                                                            <a :href="'/admin/store/edit-discount/' + discount.id" class="btn btn-primary" style="color: #fff" title="Edit">
                                                                <i class="fa fa-pencil d-none d-sm-inline"></i>
                                                            </a>
                                                        </div>
                                                        <div style="padding: 5px; display:inline;">
                                                            <a id="removeDiscount" @click="removeDiscount(discount.id)" class="btn btn-danger" style="color: #fff" title="Remove">
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
                                            <h2>There are no discounts</h2>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <item-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getDiscounts()" :offset="offset"></item-paginator>
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
        props: ['discounts'],
        data() {
            return {
                moment:moment,
                current_discounts: [],
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
        methods: {
            removeDiscount(id) {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this discount",
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
                        axios.delete('/api/discounts/destroy/' + id).then(response => {
                            if (response.data.status == 1) {
                                swal({ title: "Deleted!", text: response.data.message, timer: 1000, icon: "success" });
                                let index = this.current_discounts.findIndex(function (discount) {
                                    return discount.id == id;
                                });
                                this.current_discounts.splice(index, 1);
                            } else {
                                swal("Error!", response.data.errors[0], "error");
                            }
                        }).catch(error => {
                            swal("Error!", error, "error");
                        });
                    }
                });
            },
            getDiscounts() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: 10,
                    fields:'*'
                };
                if(this.query != ''){
                    params['query'] = '*' + this.query + '*';
                }
                axios({
                    method: 'post',
                    url: '/api/admin/discounts/search',
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
                        this.current_discounts = response.data.data;
                    } else {
                        console.log('handle server error from here');
                    }
                }).catch((error) => {
                    return error;
                });
            },
        },
        created() {
            if (this.discounts) {
                this.paginator= {
                    total: this.discounts.total,
                    per_page: this.discounts.per_page,
                    current_page: this.discounts.current_page,
                    last_page: this.discounts.last_page
                };
                this.current_discounts = this.discounts.data;
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