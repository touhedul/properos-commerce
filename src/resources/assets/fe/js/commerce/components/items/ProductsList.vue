<template>
    <div class="tt-layout tt-sticky-block__parent tt-layout__sidebar-left">
        <aside class="tt-layout__sidebar tt-sticky-block">
            <div class="tt-layout__sidebar-sticky tt-sticky-block__inner">
                <div class="tt-sidebar">
                    <div class="tt-sidebar__btn">
                        <div class="tt-sidebar__btn-open">
                            <i class="icon-filter-1"></i>
                            <span>FILTER</span>
                        </div>
                        <div class="tt-sidebar__btn-close">
                            <i class="icon-cancel-1"></i>
                            <span>CLOSE</span>
                        </div>
                    </div>
                    <div class="tt-sidebar__content">
                        <div class="tt-layer-nav">
                            <div v-if="collection.name != ''" class="badge" style="margin-bottom:20px;">
                                <span>Collection: {{collection.name}}</span>
                                <a href="/items"><i class="fa fa-close font-medium-2"></i></a>
						    </div>
                            <template v-if="current_categories.length > 1">
                                <div class="tt-layer-nav__title">Categories</div>
                                <ul class="tt-layer-nav__categories tt-categories tt-categories__toggle" id="categories-filter">
                                    <li v-for="(category, index) in current_categories" :id="'checkbox' + category.name" :key="index" :data-id="category.id">
                                        <a @click="selectCat()" href="#">{{category.name}} 
                                        </a>
                                    </li>
                                    <!-- <li v-for="(category, index) in current_categories" :key="index" class="tt-categories__open active">
                                        <label class="tt-checkbox-circle">
                                            <input @change="filterByCategory(1)" :id="'checkbox' + category.name" :value="category.id" v-model="selected_categories_id"
                                                type="checkbox">
                                            <span></span>
                                        </label>
                                        <span style="padding-left: 15px">{{category.name}}</span>
                                    </li> -->
                                </ul>
                            </template>

                            <div class="tt-layer-nav__title">Price</div>
                            <div class="tt-layer-nav__price-section">
                                <div class="tt-layer-nav__price-range">
                                    <input id="price_range" v-model="price_range" type="hidden" data-min="0" data-max="50" value="0;50">
                                </div>
                            </div>
                            <div class="tt-layer-nav__title">My Wishlist</div>
                            <div class="tt-layer-nav__wishlist">
                                <div class="tt-layer-nav__prod-list-info">
                                    <ul v-if="current_wishlist.length > 0">
                                        <li v-for="(item, index) in current_wishlist" style="border-bottom: 1px solid dashed" :key="index">
                                            <a :href="'/items/' + item.sku" style="font-size: 16px">{{item.name}}
                                                <strong> ${{item.price}}</strong>
                                            </a>
                                            <hr>
                                        </li>
                                    </ul>
                                    <p v-else>You have no items in your wishlist.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tt-sidebar__bg"></div>
            </div>
        </aside>
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-listing-page">
                    <div class="tt-page__breadcrumbs" style="margin-bottom: 20px;">
                        <ul class="tt-breadcrumbs">
                            <li>
                                <a href="/">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li>
                                <a href="/items">
                                    Products
                                </a>
                            </li>
                            <li v-if="current_category">
                                <a :href="'/items/category/' + current_category.id">
                                    {{current_category.name}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tt-listing-page__view-options tt-vw-opt" style="margin-bottom: 15px; margin-top:10px;">
                        <div class="row">
                            <div id="div-sort" class="col-xl-6 col-lg-5 col-md-5 col-xs-12" style="padding-left:40px;">
                                <label style="margin:0;font-size:12px;padding-right:5px;color:#777;font-weight:400;">Sort By:</label>
                                <select id="sortby" class="select2" style="font-size:12px;width:200px;">
                                    <option value="newest" selected>Newest to Oldest</option>
                                    <option value="oldest">Oldest to Newest</option>
                                    <option value="cheapest">Cheapest to Priciest</option>
                                    <option value="priciest">Priciest to Cheapest</option>
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-5 col-xs-8">
                                <div class="tt-vw-opt__info">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-2 col-xs-4">
                                <div v-if="current_items.length > 0" class="tt-vw-opt__grid">
                                    <div class="tt-product-btn-vw" data-control=".tt-product-view">
                                        <label>
                                            <input type="radio" name="product-btn-vw" checked>
                                            <i class="icon-th-large"></i>
                                            <i class="icon-check-empty"></i>
                                        </label>
                                        <label>
                                            <input type="radio" name="product-btn-vw" data-view-class="tt-product-view--list">
                                            <i class="icon-th"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tt-listing-page__products tt-layout__mobile-full">
                        <div v-if="current_items.length > 0" class="tt-product-view row">
                            <div v-for="(item, index) in current_items" :key="index" class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                                <div class="tt-product tt-product__view-sheet">
                                    <div class="tt-product__image">
                                        <a :href="'/items/' + item.sku" style="text-align: center">
                                            <img style="width: 70%; height: auto" :src="(item.images && item.images.length > 0) ? '/storage/' + item.images[0].thumb_path : '/images/item-placeholder.jpg'">
                                        </a>
                                        <div v-if="item.discount_percent && item.discount_percent > 0" class="tt-product__labels" style="text-align:right;right:18%;top:10px;">
                                            <span class="tt-label__sale">{{item.discount_percent}}% OFF</span>
                                        </div>
                                    </div>
                                    <div class="tt-product__hover tt-product__clr-clk-transp">
                                        <div class="tt-product__content ps ps--theme_default" data-ps-id="f25bd335-6361-4b3a-26ed-b798e30921a4">
                                            <h3>
                                                <span class="ttg-text-animation--emersion">
                                                    <a :href="/items/ + item.sku">{{item.name}}</a>
                                                </span>
                                            </h3>
                                            <div class="ttg-text-animation--emersion">
                                                <span class="tt-product__price">
                                                    <span v-if="item.discount_percent != null && item.discount_percent > 0" class="tt-price">
                                                        <span> $ {{(item.price - (item.price*item.discount_percent/100)).toFixed(2)}}</span> &nbsp
                                                        <span style="text-decoration:line-through;font-size:12px;color:#fc2a2e;">$ {{item.price}}</span>
                                                    </span>
                                                    <span v-else class="tt-price">
                                                        <span>$ {{item.price}}</span>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="ttg-text-animation--emersion">
                                                <div class="tt-product__buttons">
                                                    <a href="#" style="cursor: pointer" title="Add to cart" class="tt-btn colorize-btn5 tt-product__buttons_cart addtocart" :data-item_id="item.id">
                                                        <i class="icon-shop24"></i>
                                                        <span>Add to Cart</span>
                                                    </a>
                                                    <a href="#" style="cursor: pointer" title="Add to wishlist" class="tt-btn colorize-btn4 tt-product__buttons_like addtolist"
                                                        :data-item_id="item.sku">
                                                        <i class="icon-heart-empty-2"></i>
                                                    </a>
                                                    <a :href="/items/ + item.sku" title="View details" class="tt-btn colorize-btn4 tt-product__buttons_qv">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                                <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                            </div>
                                            <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;">
                                                <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div v-else class="tt-empty" style="margin-top: 0px">
                            <i class="tt-empty__icon">
                                <img src="/images/empty-search.svg" alt="Image name">
                            </i>
                            <div class="text-center">
                                <h3>Your Search Returns No Results.</h3>
                            </div>
                        </div>
                    </div>
                    <item-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchItems()" :offset="offset"></item-paginator>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CartServices from '../../services/CartServices.js'
    import Paginator from '../FrontPaginator.vue'
    export default {
        components: {
            'item-paginator': Paginator
        },
        mixins: [CartServices],
        props: ['items', 'categories', 'category','sortby','qoh'],
        data() {
            return {
                current_items: [],
                current_categories: [],
                current_category: {},
                show_category_filter: false,
                selected_categories_id: [],
                selected_category_name: '',
                price_range: '',
                min_price: 0,
                max_price: 0,
                current_wishlist: [],
                sort:'newwest',
                paginator: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 8,
                filter_type: '',
                active_filter: false,
                mx_price:0,
                collection: {
                    id:0,
                    name:'',
                    slug:''
                },
                filter_price: false
            }
        },
        methods: {
            selectCat(){
                var _this = this;
                this.selected_categories_id = [];
                setTimeout(function(){
                    $('#categories-filter').find('li').each(function(){
                        if($(this).hasClass('active')){
                            _this.selected_categories_id.push($(this).attr('data-id'));
                        }
                    })
                    _this.searchItems(1);
                },100)
            },
            getCurrentWishList() {
                this.getCurrentWishListCall(this.getCurrentWishListCallback);
            },
            getCurrentWishListCallback(code, data) {
                if (code == '200') {
                    this.current_wishlist = [];
                    this.fillWishList(data.wishlist);
                } else if ('001') {
                    swal("Error!", data.error, "error");
                } else if ('002') {
                    swal("Error!", data.error, "error");
                }
            },
            fillWishList(data) {
                for (var property in data) {
                    if (data.hasOwnProperty(property)) {
                        this.current_wishlist.push(data[property]);
                    }
                }
            },
            searchItems(page){
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: this.offset,
                    with: {
                        category :{
                            where:[
                                ['active', 1]
                            ]
                        },
                        images:{},
                    },
                    where:[
                        ['active',1]
                    ],
                    fields: ['id','name','price','category_id','discount_percent','total_qty','sku','reviews_total', 'options','variants'],
                };

                if(this.qoh == 1){
                    params.where.push(['total_qty','>',0]);
                }

                if(this.selected_categories_id.length > 0){
                    params['where_in'] = [
                        {
                            field: 'category_id',
                            value : this.selected_categories_id
                        }
                    ];
                }

                if (this.filter_price){
                    params['where'] = [
                        ['price','>=', this.min_price],
                        ['price','<=', this.max_price],
                    ]
                }

                if(this.collection.id > 0){
                    params.with['collections'] = {
                        where:[
                            ['collections.id', this.collection.id]
                        ]
                    }
                }

                switch(this.sort){
                    case 'newest':
                        params['orderby'] = {
                            created_at: 'DESC'
                        };
                        break;
                    case 'oldest':
                        params['orderby'] = {
                            created_at: 'ASC'
                        };
                        break;
                    case 'cheapest':
                        params['orderby'] = {
                            price: 'ASC'
                        };
                        break;
                    case 'priciest':
                        params['orderby'] = {
                            price: 'DESC'
                        };
                        break;
                }

                this.searchItemsCall(params, this.searchItemsCallback);
            },
            searchItemsCallback(response){
                if(response.status > 0){
                    if(response.data.length > 0){
                       this.paginator= {
                           total: response.pagination.total,
                           per_page: response.pagination.per_page,
                           from: (response.pagination.current_page-1)*response.pagination.per_page,
                           to: response.pagination.current_page*response.pagination.per_page,
                           last_page: response.pagination.last_page,
                           current_page: response.pagination.current_page
                       };
                   }else{
                       this.paginator= {
                           total: 0,
                           per_page: 10,
                           from: 1,
                           to: 0,
                           last_page: 1,
                           current_page: 1
                       };
                   }
                   this.current_items = response.data;
                }else{
                    swal("Error!", response.errors[0], "error");
                }
                 $.LoadingOverlay("hide");
            },
            matchCustom(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }
                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                if (data.text.toUpperCase().startsWith(params.term.toUpperCase())) {
                var modifiedData = $.extend({}, data, true);
                return 'Sort By: ' + modifiedData;
                }
                // Return `null` if the term should not be displayed
                return null;
            },
        },
        watch: {
            sort(val){
                this.searchItems(1);
            }
        },
        created() {
            if(this.items && this.items.data){
                if (this.items.data.length > 0) {
                    this.current_items = this.items.data;
                    this.paginator = this.items;
                    for(var i in this.current_items){
                        if(this.mx_price*1 < this.current_items[i].price*1){
                            this.mx_price = this.current_items[i].price;
                        }
                    }
                    this.mx_price = (this.mx_price*1).toFixed()*1 + 1*1;
                }

                if (this.items.data[0].collections && this.items.data[0].collections.length > 0) {
                    this.collection.id = this.items.data[0].collections[0].id;
                    this.collection.name = this.items.data[0].collections[0].name;
                    this.collection.slug = this.items.data[0].collections[0].slug;
                }
            }
            if (this.categories && this.categories.length > 0) {
                this.current_categories = this.categories;
            }
            if (this.category) {
                this.current_category = this.category;
                this.selected_categories_id.push(this.current_category.id);
            }
            
            this.getCurrentWishList();
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $("#price_range").ionRangeSlider({
                    min: 0,
                    max: 50,
                    type: "double",
                    onFinish: function (data) {
                        self.min_price = data.from;
                        self.max_price = data.to;
                        self.filter_price = true;
                        self.searchItems(1);
                    }
                });

                $("#sortby").select2({
                    minimumResultsForSearch: -1,
                    matcher: self.matchCustom
                });

                $('#sortby').on('change', function (e) {
                    self.sort = $('#sortby').val();
                });
                if(self.sortby && self.sortby != ''){
                    $("#sortby").val(self.sortby).trigger('change');
                }
            });
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-product__content {
        margin-top: 0px !important;
    }

    .tt-price {
        font-size: 20px;
    }

    .tt-product__buttons {
        margin-top: 0px;
    }

    .tt-label__discount, .tt-label__hot, .tt-label__in-stock, .tt-label__new, .tt-label__out-stock, .tt-label__sale {
        font-size: 12px;
    }

    .badge {
        display: inline-block;
        padding: 8px;
        font-size: 95%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 1.5rem;
        background-color: #bbbbbb40;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #fe5a1a;
        color: white;
        font-size: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px;
        font-size: 12px;
    }

    .select2-results__option {
        padding: 6px;
        user-select: none;
        -webkit-user-select: none;
        font-size: 12px;
    }

    #select2-sortby-container:focus {
        outline: none;
    }
    .select2-container:focus {
        outline: none;
    }
    #div-sort{
            padding-left: 10px!important;
        }

    @media (max-width: 1400px) {
        #div-sort{
            padding-left: 35px!important;
        }
    }
    @media (max-width: 425px) {
        #div-sort{
            padding-left: 70px!important;
        }
    }
    @media (max-width: 375px) {
        #div-sort{
            padding-left: 55px!important;
        }
    }
    @media (max-width: 320px) {
        #div-sort{
            padding-left: 35px!important;
        }
    }
</style>