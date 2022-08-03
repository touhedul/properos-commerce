<template>
<div>
    <div class="tt-layout tt-sticky-block__parent ">
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-product-page">
                    <div class="tt-product-page__breadcrumbs">
                        <ul class="tt-breadcrumbs">
                            <li>
                                <a href="index.html">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li>
                                <a href="/items">Products</a>
                            </li>
                            <li>
                                <a :href="'/items/category/' + current_item.category.id">{{current_item.category.name}}</a>
                            </li>
                            <li>
                                <a :href="'/items/' + current_item.sku">{{current_item.name}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-10 offset-md-2">
                            <div class="tt-product-head tt-sticky-block__parent" style="margin-bottom:0px;">
                                <div class="tt-product-head__sticky tt-sticky-block tt-layout__mobile-full">
                                    <div class="tt-product-head__images tt-sticky-block__inner tt-product-head__single-mobile ">
                                        <div v-if="current_item.files.length > 0" class="tt-product-head__image-main">
                                            <img v-for="(file, index) in current_item.files" :src="'/storage/' + file.thumb_path" :data-zoom-image="'/storage/' + file.thumb_path"
                                                data-full="#" :alt="current_item.name" :key="index">
                                        </div>
                                        <div v-else class="tt-product-head__image-main">
                                            <img src="/images/item-placeholder.jpg" data-zoom-image="/images/item-placeholder.jpg" data-full="#" :alt="current_item.name">
                                        </div>
                                        <div v-if="current_item.files.length > 0" class="tt-product-head__image-preview">
                                            <img v-for="(file, index) in current_item.files" :src="'/storage/' + file.thumb_path" :alt="current_item.name" :key="index">
                                        </div>
                                        <div v-else class="tt-product-head__image-preview">
                                            <img src="/images/item-placeholder.jpg" :alt="current_item.name">
                                        </div>
                                    </div>
                                </div>
                                <div class="tt-product-head__sticky tt-sticky-block">
                                    <div v-if="showShare" class="toogle-background" @click="toggle"></div>
                                    <div class="tt-product-head__info tt-sticky-block__inner">
                                        <form action="#">
                                            <div class="tt-product-head__info-head">
                                                <!--  <div class="tt-product-head__index">SKU:
                                                    <span>0001</span>
                                                </div> -->
                                                <div class="tt-product-head__availability">Availability:
                                                    <span v-if="item.total_qty > 0" class="colorize-success-c">{{item.total_qty}} In Stock</span>
                                                    <span v-else class="colorize-error-c">Out of Stock</span>
                                                </div>
                                            </div>
                                            <div class="tt-product-head__category">
                                                <a :href="'/items/category/' + item.category.id">{{item.category.name}}</a>
                                            </div>
                                            <div>
                                                <h4 style="font-size:24px;">{{item.name}}</h4>
                                            </div>
                                            <div class="tt-product-head__review">
                                                <div class="tt-product-head__stars tt-stars">
                                                    <span class="ttg-icon"></span>
                                                    <span class="ttg-icon" :style="'width:'+ item.reviews_percent + '%;'"></span>
                                                </div>
                                                <div class="tt-product-head__review-count" style="margin-top:6px;">
                                                    <a href="#">{{item.reviews_total}} Review(s)</a>
                                                </div>
                                                <div class="tt-product-head__review-add" style="margin-top:6px;">
                                                    <a @click="showFormReview()" href="#" style="margin-right:0px;">Add Your Review</a>
                                                </div>
                                            </div>
                                            <div class="tt-product-head__price">
                                                <div v-if="item.options && item.options.length > 0 && variants" id="variants">
                                                    <div v-for="(opt, index) in item.options" :key="index" class="form-group" style="font-size: 14px">
                                                        <label>{{opt.label}}: </label>
                                                        <select class="form-control form-control-sm options-select">
                                                            <option v-for="(variant, ind) in variants[opt.label]" :key="ind" :data-option="opt.label" :value="variant">{{variant}}</option>
                                                        </select>   
                                                    </div>
                                                </div>
                                                <div style="font-size: 30px">
                                                    <span v-if="item.discount_percent != null && item.discount_percent > 0" class="tt-price">
                                                         <span style="font-size:14px;padding-right:5px;">Price: </span>
                                                        <span > ${{(item.price - (item.price*item.discount_percent/100)).toFixed(2)}}</span> &nbsp
                                                        <span style="text-decoration:line-through;font-size:12px;color:#fc2a2e;">${{item.price}}</span>
                                                    </span>
                                                    <span v-else class="tt-price">
                                                        <span style="font-size:14px;padding-right:5px;">Price: </span>
                                                        <span >${{item.price}}</span>
                                                    </span>
                                                    <span v-if="item.discount_percent != null && item.discount_percent > 0"><span class="tt-label__sale">{{item.discount_percent}}% off</span></span>
                                                </div>
                                            </div>
                                            <div v-if="item.total_qty > 0" class="tt-product-head__control" style="margin-bottom:0;">
                                                <div class="tt-product-head__counter tt-counter tt-counter__inner" data-min="1" data-max="999">
                                                    <input id="product-qty" v-model="qty" type="text" class="form-control" value="1">
                                                    <div class="tt-counter__control">
                                                        <span @click="incrementQty" class="icon-up-circle" data-direction="next"></span>
                                                        <span @click="decrementQty" class="icon-down-circle" data-direction="prev"></span>
                                                    </div>
                                                </div>
                                                <a @click="addItem()" class="tt-product-head__cart tt-btn tt-btn--cart colorize-btn6" style="cursor: pointer">
                                                    <i class="icon-shop24"></i>
                                                    <span>Add to Cart</span>
                                                </a>
                                                <a v-if="item.total_qty > 0" @click="buyNow()" class="tt-product-head__cart tt-btn tt-btn--cart colorize-btn6 buyNow" style="cursor: pointer;">
                                                     <i class="fa fa-bolt" style="padding-right:5px;"></i>
                                                    <span>Buy Now</span>
                                                </a>
                                                <a class="tt-product-head__cart tt-btn tt-btn--cart colorize-btn6 addtolist" :data-item_id="current_item.sku" style="cursor: pointer">
                                                    <i class="icon-heart-empty-2" style="font-size:14px;"></i>
                                                    <span>Add to Wish List</span>
                                                </a>
                                            </div>
                                            <div class="dropdown" style="margin-bottom:20px;">
                                                <button type="button" class="btn btn-success width-200 btn-share" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#fff!important;padding:10px;width:120px;">
                                                    <i class="fa fa-share-alt" style="color:#fff!important;padding-right:15px;font-size:20px;" ></i> Share
                                                </button>
                                                <div class="dropdown-menu custom-dropdown-menu animate slideIn" style="margin-top:0px;z-index:2060;">
                                                    <div>
                                                        <a :href="url_facebook" class="btn btn-social width-200 btn-facebook" style="padding:10px;width:120px;" target="_blank">
                                                            <span class="fa fa-facebook" style="color:#fff!important;padding-right:15px;font-size:20px;"></span> 
                                                            Facebook
                                                        </a>
                                                    </div>
                                                    <div style="margin-top:5px;">
                                                        <a :href="url_twitter" class="btn btn-social width-200 btn-twitter" style="padding:10px;width:120px;" target="_blank">
                                                            <span class="fa fa-twitter" style="color:#fff!important;padding-right:10px;font-size:20px;"></span> 
                                                            Twitter
                                                        </a>
                                                    </div>
                                                    <div style="margin-top:5px;">
                                                        <a :href="url_linkedin" class="btn btn-social width-200 btn-linkedin" style="padding:10px;width:120px;" target="_blank">
                                                            <span class="fa fa-linkedin" style="color:#fff!important;padding-right:15px;font-size:20px;"></span> 
                                                            Linkedin
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tt-product-page__tabs tt-tabs tt-layout__mobile-full" data-tt-type="horizontal" style="margin-top:0px;">
                        <div class="tt-tabs__head">
                            <div class="tt-tabs__slider">
                                <div class="tt-tabs__btn" data-active="true">
                                    <span>Description</span>
                                </div>
                                <div id="reviews-tab" class="tt-tabs__btn" data-tab="review">
                                    <span>Reviews</span>
                                </div>
                            </div>
                            <div class="tt-tabs__btn-prev"></div>
                            <div class="tt-tabs__btn-next"></div>
                            <div class="tt-tabs__border"></div>
                        </div>
                        <div class="tt-tabs__body tt-tabs-product">
                            <div>
                                <span>Description
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div class="tt-tabs__content-head">Description</div>
                                    {{item.description}}
                                </div>
                            </div>
                            
                            <div>
                                <span>Reviews <i class="icon-down-open"></i></span>
                                <div class="tt-tabs__content">
                                    <div class="tt-tabs__content-head">Customer Reviews</div>
                                    <div class="tt-tabs-product__review tt-review">
                                        <div v-show="!show_review && !reviewed">
                                            <a @click="showFormReview()" class="write-review" style="color: #13919b;font-weight:bold;float:right;cursor:pointer;">Write a review</a>
                                        </div>
                                        <div v-show="show_review && !reviewed" class="tt-review__form" style="margin-top:15px;">
                                            <form>
                                                <div class="row ttg-mt--20">
                                                    <div class="col-sm-4"><label for="reviewTitle">Review Title:</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="reviewTitle" class="form-control"
                                                               placeholder="Give your review a title" v-model="review.title">
                                                    </div>
                                                </div>
                                                <div class="row ttg-mt--20">
                                                    <div class="col-sm-4"><label>E-Rating:</label></div>
                                                    <div class="col-sm-8">
                                                        <div class="tt-review__form-stars tt-stars tt-stars__input">
                                                            <span class="ttg-icon"></span>
                                                            <span class="tt-stars__set ttg-icon"
                                                                  style="width: 20%;"></span>
                                                            <input type="hidden" id="reviewStars" value="70">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ttg-mt--20">
                                                    <div class="col-sm-4"><label for="reviewBody">Body of Review
                                                        (1500):</label></div>
                                                    <div class="col-sm-8">
                                                        <textarea id="reviewBody" class="form-control" v-model="review.comment">Wtite your comments here</textarea>
                                                    </div>
                                                </div>
                                                <div class="row ttg-mt--20">
                                                    <div class="col-sm-8 offset-sm-4">
                                                        <button type="button" @click="submitReview()" class="btn">Submit Review</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div v-show="reviewed" style="margin-top:10px;margin-bottom:10px;">
                                            <span style="color: #13919b;font-weight:bold;">You have reviewed this product</span>
                                            <a @click="showFormReview()" class="write-review" style="color: #13919b;font-weight:bold;float:right;cursor:pointer;">Update review</a>
                                        </div>
                                        <div class="tt-review__comments" v-if="reviews.length >0">
                                            <div  v-for="(review, key) in reviews" :key="key">
                                                <div class="tt-stars">
                                                    <span class="ttg-icon"></span>
                                                    <span class="ttg-icon" :style="'width:'+ review.rate + '%;'"></span>
                                                </div>
                                                <div class="tt-review__comments-title">{{review.title}}</div>
                                                <span><span>{{review.fullname}}</span> on {{moment(review.created_at, 'YYYY-MM-dd HH:mm:ss').format('MMMM DD, YYYY')}}</span>
                                                <p style="margin-top:0px;">{{review.comment}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tt-product-page__upsell" style="margin-top: 20px">
                            <div v-if="current_similar.length > 0" style="font-size: 24px; color: #000">You may also be interested in the following product(s)
                            </div>
                            <div class="tt-carousel-box">
                                <div class="tt-product-view">
                                    <div class="tt-carousel-box__slider">
                                        <div v-if="current_similar.length > 0" v-for="(similar_item, index, key) in current_similar" :key="key">
                                            <div class="tt-product tt-product__view-sheet">
                                                <div class="tt-product__image">
                                                    <a :href="'/items/' + similar_item.sku" class="similar-thumb">
                                                        <img style="width: 70%; height: auto" :src="similar_item.files.length > 0 ? '/storage/' + similar_item.files[0].thumb_path : '/images/item-placeholder.jpg'">
                                                    </a>
                                                </div>
                                                <div class="tt-product__hover tt-product__clr-clk-transp">
                                                    <div class="tt-product__content ps ps--theme_default" data-ps-id="f25bd335-6361-4b3a-26ed-b798e30921a4">
                                                        <h3>
                                                            <span class="ttg-text-animation--emersion">
                                                                <a :href="/items/ + similar_item.sku" style="font-size:14px;">{{similar_item.name}}</a>
                                                            </span>
                                                        </h3>
                                                        <div @click="goItemReview(similar_item.sku)" class="tt-product-head__review ttg-text-animation--emersion" style="display:inline-flex;cursor:pointer;">
                                                            <div class="tt-product-head__stars tt-stars" style="margin-right: 10px;">
                                                                <span class="ttg-icon"></span>
                                                                <span class="ttg-icon" :style="'width:'+ similar_item.reviews_percent + '%;'"></span>
                                                            </div>
                                                            <div class="tt-product-head__review-count" style="margin-top:6px;margin-right:0px;">
                                                                <a href="" style="margin-right:0px;">{{similar_item.reviews_total}}</a>
                                                            </div>
                                                        </div>
                                                        <div class="ttg-text-animation--emersion">
                                                            <span class="tt-product__price">
                                                                <span class="tt-price">
                                                                    <span>${{similar_item.price}}</span>
                                                                    <!-- <span v-if="item.price < item.msrp">${{item.msrp}}</span> -->
                                                                </span>
                                                            </span>
                                                        </div>
                                                        <div class="ttg-text-animation--emersion">
                                                            <div class="tt-product__buttons">
                                                                <a style="cursor: pointer; text-decoration: none" title="Add to cart" class="tt-btn colorize-btn5 tt-product__buttons_cart addtocart"
                                                                    :data-item_id="similar_item.id">
                                                                    <i class="icon-shop24"></i>
                                                                    <span>Add to Cart</span>
                                                                </a>
                                                                <a style="cursor: pointer; text-decoration: none" title="Add to wishlist" class="tt-btn colorize-btn4 tt-product__buttons_like addtolist"
                                                                    :data-item_id="similar_item.sku">
                                                                    <i class="icon-heart-empty-2"></i>
                                                                </a>
                                                                <a :href="/items/ + similar_item.sku" title="View details" class="tt-btn colorize-btn4 tt-product__buttons_qv">
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
    import CartServices from '../../services/CartServices.js'
    import ReviewServices from '../../services/ReviewServices.js'
    export default {
        mixins: [CartServices, ReviewServices],
        props: ['item', 'similar_items','host'],
        data() {
            return {
                moment:moment,
                current_item: '',
                current_similar: [],
                qty: 1,
                show_review: false,
                reviewed: false,
                review:{
                    item_id: 0,
                    title:'',
                    comment:'',
                    rate:20
                },
                errors:{
                    title: false,
                    comment: false
                },
                reviews:[],
                variants:{},
                showShare: false,
                url_facebook:'',
                url_twitter:'',
                url_linkedin:''
            }
        },
        methods: {
            goItemReview(sku){
                window.location.href="/items/" + sku+"#reviews"
            },
            getValue(){
                console.log($("#reviewStars").val());
            },
            addItem() {
                var params = {};
                $("#variants").find('select').each(function(){
                    params[$(this).find(":selected").data('option')] = $(this).val();
                });
                this.addItemCall(this.current_item.id, this.qty, params, this.addItemCallback);
            },
            addItemSimilar(item_id) {
                this.addItemCall(item_id, 1, this.addItemCallback);
            },
            addItemCallback(code, data) {
                if (code == '200') {
                    this.qty = 1;
                    paintCart();
                    highlightCart();
                }
                if (code == '003') {
                    swal('Warning!', data[0], 'warning');
                }
            },
            incrementQty() {
                this.qty++;
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                this.showShare = false;
            },
            decrementQty() {
                if(this.qty > 1){
                    this.qty--;
                }else{
                    this.qty = 1;
                }
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                this.showShare = false;
            },
            showFormReview(){
                this.show_review = true;
                this.reviewed = false;
            },
            submitReview(){
                this.reviewed = false;
                var has_error = false;
                this.review.item_id = this.current_item.id;
                for(var i in this.review){
                    if(this.review[i] == ''){
                        this.errors[i] = true;
                        has_error = true;
                    }else{
                        this.errors[i] = true;
                    }
                }
                if(!has_error){
                    this.review.rate = $("#reviewStars").val();
                    this.addReview(this.review, this.addReviewCallback);
                }
            },
            addReviewCallback(data){
                if(data.status > 0){
                    if(data.data.url){
                        window.location.href = data.data.url;
                    }else{
                        this.reviewed = true;
                        for(var i in this.reviews){
                            if(this.reviews[i].id == data.data.id){
                                this.reviews.splice(i,1);
                            }
                        }
                        data.data.comment = this.review.comment;
                        data.data.rate = this.review.rate;
                        data.data.title = this.review.title;
                        this.reviews.push(data.data);
                    }
                }else{
                    if(Helpers.isAssoc(data)){
                        var text = '';
                        for(var i in data.errors){
                            text += data.errors[i];
                        }
                        swal("Error!", text, "error");
                    }else{
                        swal("Error!", data, "error");
                    }
                    
                }
            },
            getReviewsCallback(code, data){
                if(code == 200){
                    this.reviews = data;
                    if(window.location.href.split('#')[1] == 'reviews'){
                        setTimeout(function(){
                            $('#reviews-tab span').click();
                        },100);
                        // $([document.documentElement, document.body]).animate({
                        //     scrollTop: $("#reviews-tab").offset().top - 100
                        // }, 2000);
                    }
                }else{
                    swal("Error!", data, "error");
                }
            },
            buyNow(){
                var params = {};
                $("#variants").find('select').each(function(){
                    params[$(this).find(":selected").data('option')] = $(this).val();
                });
                this.addItemCall(this.current_item.id, this.qty, params, this.buyNowCallback);
            },
            buyNowCallback(code, data) {
                $.LoadingOverlay("show");
                if (code == '200') {
                    window.location.href = '/cart/checkout';
                }else{
                    $.LoadingOverlay("hide");
                }
                if (code == '003') {
                    swal('Warning!', data[0], 'warning');
                }
            },
            toggle(){
                $('.dropdown button').click();
            },
        },
        created() {
            if (this.item) {
                this.current_item = this.item;
                this.getReviews(this.item.id, this.getReviewsCallback);

                if(this.item.variants && this.item.variants.length > 0){
                    for(var i in this.item.variants){
                        for(var j in this.item.variants[i]){
                            if(!this.variants[j]){
                                this.variants[j] = [];
                            }
                            var arr = this.item.variants[i][j].split(",");
                            this.variants[j] =this.variants[j].concat(arr);
                        }
                    }
                }
            }
            if (this.similar_items.length > 0) {
                this.current_similar = this.similar_items;
            }
        },
        mounted() {
            var self = this;
            $('.dropdown button').on('click', function() {
                if(!self.showShare){
                    $('.custom-dropdown-menu').first().stop(true, true).slideDown();
                    self.showShare = true
                }
                else{
                    $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                    self.showShare = false;
                }
            });
            $('.tt-tabs__btn').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('.tt-product-head__cart').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('.contact-ribbon').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('.tt-footer').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('#product-qty').on('focus', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('#menu-header').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('#menu-cart').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            $('.tt-header__btn').on('click', function() {
                $('.custom-dropdown-menu').first().stop(true, true).slideUp();
                self.showShare = false;
            });
            this.url_facebook = 'https://www.facebook.com/sharer/sharer.php?u='+location.href;
            this.url_twitter = 'http://twitter.com/share?url='+location.href;
            this.url_twitter = 'http://twitter.com/share?url='+location.href;
            this.url_linkedin = 'http://www.linkedin.com/shareArticle?mini=true&url='+location.href+'&title=How%20to%20make%20custom%20linkedin%20share%20button&summary=some%20summary%20if%20you%20want&source='+this.host+'';

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

    .similar-thumb {
        text-align: -moz-center;
        text-align: -webkit-center;
        align-content: center !important;
    }
    .tt-stars > span::before {
        display: inline;
    }

    .tt-stars > span:nth-child(2) {
        height:28px;
    }

    .write-review:hover{
        text-decoration:underline!important;
    }
    .swal-text{
        text-align: center;
    }

    .options-select{
        width: 200px;
        display: inline-block;
        border: 1px solid #13919b!important;
        min-height: 30px!important;
        padding: 5px 15px!important;
    }
    
     @media (max-width: 480px){
        .container {
            width: 550px;
            max-width: 100%;
        }
    }

    .tt-counter.tt-counter__inner {
        width: 86px;
    }

    @media (max-width: 1400px){
        .tt-counter.tt-counter__inner {
            width: 160px;
        }
    }
    

    .addtolist i:before{
        font-size:23px;
    }
    .buyNow i:before{
        font-size:23px;
    }

    .btn-facebook, .btn-facebook:hover {
      background-color: #3B5998!important;
      color: #FFF!important;
      border: none;
      /* border-radius:50%;
      padding:10px 14px; */
    }
    .btn-social-icon .btn-social-icon:hover {
      background-color: #3B5998!important;
      color: #FFF!important;
    }
    .btn-twitter, .btn-twitter:hover {
      background-color: #55ACEE!important;
      color: #FFF!important;
      border: none;
      /* border-radius:50%;
      padding:11px 13px; */
    }
    .btn-linkedin, .btn-linkedin:hover {
      background-color: #007BB6!important;
      color: #FFF!important;
      border: none;
      /* border-radius:50%;
      padding:11px 13px; */
    }
    .btn-share, .btn-share:hover {
      background-color: #3F729B!important;
      color: #FFF!important;
      border: none;
      /* border-radius:50%!important;
      padding:11px 13px; */
      outline: none!important;
    }
    .btn-share:focus, .btn-share.focus {
      box-shadow: 0 0 0 0.2rem rgba(183, 199, 224, 0.21)!important;
      outline: none!important;
    }

    .custom-dropdown-menu {
      background-color: transparent;
      border: none;
      margin-top:10px;
    }

    .toogle-background{
      position: fixed;
      top: 0px;
      left: 0px;
      opacity: 0.3;
      background-color: transparent;
      width: 100%;
      height: 100%;
      z-index: 0;
      overflow: hidden;
    
    }
</style>