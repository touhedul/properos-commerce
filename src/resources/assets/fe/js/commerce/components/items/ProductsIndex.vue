<template>
    <div>
        <div v-if="show_collections_section">
            <div v-for="(collection, index) in current_collections" :key="index" class="tt-product-page__upsell">
                <div class="row section-ribbon">
                    <div class="col-md-12 text-center">
                        <div style="margin-top: 25px;margin-bottom: 25px; font-size: 24px">
                            <b style="text-transform:uppercase;">{{collection.title}}</b>
                        </div>
                    </div>
                </div>
                <div v-if="collection.items" class="tt-carousel-box">
                    <div class="tt-product-view">
                        <div class="tt-carousel-box__slider">
                            <div v-for="(item, index) in collection.items" :key="index" v-if="item.files && item.files.length > 0">
                                <div class="tt-product tt-product__view-sheet">
                                    <div class="tt-product__image" style="text-align: center !important">
                                        <div :href="'/items/' + item.sku" class="img-thumb">
                                            <a :href="'/items/' + item.sku">
                                                <img style="width: 50%; height: auto" :src="item.files.length > 0 ? '/storage/' + item.files[0].thumb_path : '/images/item-placeholder.jpg'">
                                            </a>
                                        </div>
                                        <div v-if="item.discount_percent && item.discount_percent > 0" class="tt-product__labels" style="text-align:right;right:28%;top:10px;">
                                                <span class="tt-label__sale">{{item.discount_percent}}% OFF</span>
                                            </div>
                                        <!-- <div v-if="recommended_product.discount_percent && recommended_product.discount_percent > 0" class="tt-product__labels" style="right:160px;">
                                            <span class="tt-label__discount">{{recommended_product.discount_percent}}% OFF</span>
                                        </div> -->
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
                                                    <span v-if="item.discount_percent && item.discount_percent > 0" class="tt-price">
                                                        <span >${{(item.price-item.price*item.discount_percent/100).toFixed(2)}} </span> &nbsp
                                                        <span style="text-decoration: line-through;font-size:12px;color:#fc2a2e;"> ${{item.price}}</span>
                                                    </span>
                                                    <span v-else class="tt-price">
                                                        <span >${{item.price}}</span>
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
                            <div>
                                <div class="tt-product tt-product__view-sheet">
                                    <div class="tt-product__image" style="text-align: center !important">
                                        <div :href="'/items/collection/'+collection.slug" class="img-thumb">
                                            <a :href="'/items/collection/'+collection.slug">
                                                <img style="width: 50%; height: auto" src="/images/placeholder/more_products.jpg">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="show_popular_section" class="tt-product-page__upsell">
            <div class="row section-ribbon">
                <div class="col-md-12 text-center">
                    <div style="margin-top: 25px;margin-bottom: 25px; font-size: 24px">
                        <b>EXPLORE OUR PRODUCTS</b>
                    </div>
                </div>
            </div>
            <div class="tt-carousel-box">
                <div class="tt-product-view">
                    <div class="tt-carousel-box__slider">
                        <div v-if="recommended_product.files.length > 0" v-for="(recommended_product, index) in current_recommended_product" :key="index">

                            <div class="tt-product tt-product__view-sheet">
                                <div class="tt-product__image" style="text-align: center !important">
                                    <div :href="'/items/' + recommended_product.sku" class="img-thumb">
                                        <a :href="'/items/' + recommended_product.sku">
                                            <img style="width: 50%; height: auto" :src="recommended_product.files.length > 0 ? '/storage/' + recommended_product.files[0].thumb_path : '/images/item-placeholder.jpg'">
                                        </a>
                                    </div>
                                    <div v-if="recommended_product.discount_percent && recommended_product.discount_percent > 0" class="tt-product__labels" style="text-align:right;right:28%;top:10px;">
                                            <span class="tt-label__sale">{{recommended_product.discount_percent}}% OFF</span>
                                        </div>
                                     <!-- <div v-if="recommended_product.discount_percent && recommended_product.discount_percent > 0" class="tt-product__labels" style="right:160px;">
                                        <span class="tt-label__discount">{{recommended_product.discount_percent}}% OFF</span>
                                    </div> -->
                                </div>
                                <div class="tt-product__hover tt-product__clr-clk-transp">
                                    <div class="tt-product__content ps ps--theme_default" data-ps-id="f25bd335-6361-4b3a-26ed-b798e30921a4">
                                        <h3>
                                            <span class="ttg-text-animation--emersion">
                                                <a :href="/items/ + recommended_product.sku">{{recommended_product.name}}</a>
                                            </span>
                                        </h3>
                                        <div class="ttg-text-animation--emersion">
                                            <span class="tt-product__price">
                                                <span v-if="recommended_product.discount_percent && recommended_product.discount_percent > 0" class="tt-price">
                                                    <span>${{(recommended_product.price-recommended_product.price*recommended_product.discount_percent/100).toFixed(2)}} </span> &nbsp
                                                    <span style="text-decoration: line-through;font-size:12px;color:#fc2a2e;"> ${{recommended_product.price}}</span>
                                                </span>
                                                <span v-else class="tt-price">
                                                    <span>${{recommended_product.price}}</span>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="ttg-text-animation--emersion">
                                            <div class="tt-product__buttons">
                                                <a href="#" style="cursor: pointer" title="Add to cart" class="tt-btn colorize-btn5 tt-product__buttons_cart addtocart" :data-item_id="recommended_product.id">
                                                    <i class="icon-shop24"></i>
                                                    <span>Add to Cart</span>
                                                </a>
                                                <a href="#" style="cursor: pointer" title="Add to wishlist" class="tt-btn colorize-btn4 tt-product__buttons_like addtolist"
                                                    :data-item_id="recommended_product.sku">
                                                    <i class="icon-heart-empty-2"></i>
                                                </a>
                                                <a :href="/items/ + recommended_product.sku" title="View details" class="tt-btn colorize-btn4 tt-product__buttons_qv">
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
                        <div>
                            <div class="tt-product tt-product__view-sheet">
                                <div class="tt-product__image" style="text-align: center !important">
                                    <div href="/items" class="img-thumb">
                                        <a href="/items">
                                            <img style="width: 50%; height: auto" src="/images/placeholder/more_products.jpg">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="show_category_section" class="tt-product-page__upsell">
            <div class="row section-ribbon">
                <div class="col-md-12 text-center">
                    <div style="margin-top: 25px;margin-bottom: 25px; font-size: 24px">
                        <b>POPULAR CATEGORIES</b>
                    </div>
                </div>
            </div>
            <div class="tt-carousel-box">
                <div class="tt-product-view">
                    <div class="tt-carousel-box__slider">
                        <div v-for="(category_product, index) in categories_product" :key="index">
                            <div class="tt-product tt-product__view-sheet">
                                <div class="tt-product__image" style="text-align: center !important">
                                    <div class="img-thumb">
                                        <a href="" @click="gotoCategory($event, category_product.id)">
                                            <img style="width: 50%; height: auto" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" :srcset="category_product.files.length > 0 ? '/storage/' + category_product.files[0].thumb_path : '/images/item-placeholder.jpg'">
                                        </a>
                                    </div>
                                </div>
                                <div class="tt-product__hover tt-product__clr-clk-transp">
                                    <div class="tt-product__content ps ps--theme_default" data-ps-id="f25bd335-6361-4b3a-26ed-b798e30921a4">
                                        <h3>
                                            <span class="ttg-text-animation--emersion">
                                                <a href="" @click="gotoCategory($event, category_product.id)">{{category_product.name}}</a>
                                            </span>
                                        </h3>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['categories', 'recommended_product','collections'],
        data() {
            return {
                categories_product: [],
                current_recommended_product: [],
                current_collections: [],
                show_collections_section: false,
                show_popular_section: false,
                show_category_section: false
            }
        },
        methods: {
            gotoCategory(e, id) {
                e.preventDefault();
                window.location.href = '/items/category/' + id;
            }
        },
        created() {

        },
        mounted() {
            if (this.categories.length > 0) {
                this.categories_product = this.categories;
                for (var i in this.categories_product) {
                    if (this.categories_product[i].files.length) {
                        this.show_category_section = true;
                    }
                }
            }
            if (this.recommended_product.length > 0) {
                this.current_recommended_product = this.recommended_product;
                for (var i in this.current_recommended_product) {
                    if (this.current_recommended_product[i].files.length > 0) {
                        this.show_popular_section = true;
                    }
                }
            }
            if (this.collections && this.collections.length > 0) {
                this.current_collections = this.collections;
                // for (var i in this.current_collections) {
                //     if (this.current_collections[i].files.length > 0) {
                        this.show_collections_section = true;
                //     }
                // }
            }
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-product-page__upsell {
        margin-bottom: 0px;
    }

    .img-thumb {
        text-align: -moz-center;
        text-align: -webkit-center;
        text-align: -ms-text-center;

    }

    .tt-product-page__upsell-title {
        margin-bottom: 0px !important;
    }

    .tt-product__content {
        margin-top: 0px !important;
    }

    .section-ribbon {
        width: 100%;
        height: 90px;
        margin-left: 0px;
        margin-right: 0px;
        background: #ffffff;

        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#000000+1,5b5b5b+19,5b5b5b+50,5b5b5b+81,000000+100&0.04+0,0.3+20,0+51,0.3+81,0+100 */
        /* background: -moz-linear-gradient(left, rgba(0, 0, 0, 0.04) 0%, rgba(0, 0, 0, 0.05) 1%, rgba(91, 91, 91, 0.29) 19%, rgba(91, 91, 91, 0.3) 20%, rgba(91, 91, 91, 0.01) 50%, rgba(91, 91, 91, 0) 51%, rgba(91, 91, 91, 0.3) 81%, rgba(0, 0, 0, 0) 100%); */
        /* FF3.6-15 */
        /* background: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.04) 0%, rgba(0, 0, 0, 0.05) 1%, rgba(91, 91, 91, 0.29) 19%, rgba(91, 91, 91, 0.3) 20%, rgba(91, 91, 91, 0.01) 50%, rgba(91, 91, 91, 0) 51%, rgba(91, 91, 91, 0.3) 81%, rgba(0, 0, 0, 0) 100%); */
        /* Chrome10-25,Safari5.1-6 */
        /* background: linear-gradient(to right, rgba(0, 0, 0, 0.04) 0%, rgba(0, 0, 0, 0.05) 1%, rgba(91, 91, 91, 0.29) 19%, rgba(91, 91, 91, 0.3) 20%, rgba(91, 91, 91, 0.01) 50%, rgba(91, 91, 91, 0) 51%, rgba(91, 91, 91, 0.3) 81%, rgba(0, 0, 0, 0) 100%); */
        /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        /* filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0a000000', endColorstr='#00000000', GradientType=1); */
        /* IE6-9 */
    }


</style>