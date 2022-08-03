<template>
    <div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-page__breadcrumbs">
                    <ul class="tt-breadcrumbs">
                        <li>
                            <a href="index.html">
                                <i class="icon-home"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/blog">Blog</a>
                        </li>
                    </ul>
                </div>
                <div class="tt-page__cont-small">
                    <div class="tt-post-head col-12">
                        <!-- <a class="tt-post-head__category" href="index.html?page=listing-with-custom-html-block.html">Headphones</a> -->
                        <div class="tt-post-head__title" style="padding-bottom: 30px">{{post.title}}</div>
                        <div v-if="post.header_media_type == 'picture'">
                            <img :src="'/storage/' + post.header_media_path">
                        </div>
                        <div v-else-if="post.header_media_type == 'video'" class="embed-responsive embed-responsive-item embed-responsive-16by9">
                            <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path"></youtube>
                        </div>
                        <div class="tt-post-head__info" style="font-size: 18px">
                            <span>Hiketron - </span> {{moment(post.created_at).format('LLLL')}}</div>
                            <!--<span>{{post.author.name}} - </span> {{moment(post.created_at).format('LLLL')}}</div>-->
                    </div>
                    <div>
                        <div class="col-12" v-html="post.content"></div>
                        <!-- <div class="tt-post-text__list tt-list">
                            <div class="tt-list__title">Duis aute irure dolor in reprehenderit in voluptate velit esse.
                            </div>
                            <ul>
                                <li>Omnis iste natus error sit voluptatem</li>
                                <li>Accusantium doloremque</li>
                                <li>Laudantium, totam rem aperiam,</li>
                                <li>Eaque ipsa quae ab illo inventore veritatis</li>
                            </ul>
                        </div>
                        <p>Voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet conse ctetur
                            adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            <span class="ttg-text--highlight"> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                pariatur. Voluptate velit esse cillum dolore eu fugiat </span>nulla pariatur. Lorem ipsum
                            dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
                            magna aliqua.</p> -->
                    </div>
                    <!-- <div class="tt-post-text">
                        <div class="tt-post-text__footer">
                            <div class="tt-post-text__tags">
                                <i class="icon-tag-1"></i>
                                <a href="index.html?page=listing-with-custom-html-block.html">Audio,</a>
                                <a href="index.html?page=listing-with-custom-html-block.html">Headphones</a>
                            </div>
                            <div class="tt-social-icons tt-social-icons--style-04">
                                <a href="#" class="tt-btn">
                                    <i class="icon-facebook"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-twitter"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-gplus"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-instagram-1"></i>
                                </a>
                                <a href="#" class="tt-btn">
                                    <i class="icon-youtube-play"></i>
                                </a>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="tt-post-user">
                        <a href="#" class="tt-post-user__image ttg-image-scale">
                            <img alt="Image name" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                srcset="images/blog/single/blog-single-user.jpg" style="display: inline;">
                        </a>
                        <div class="tt-post-user__text">
                            <div class="tt-post-user__name">Robert Trump</div>
                            <p>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididun</p>
                        </div>
                    </div> -->
                </div>
                <!-- <div class="tt-post-nav__wrap">
                    <div class="tt-page__cont-small">
                        <div class="tt-post-nav">
                            <a href="index.html?page=blog-standart-post.html" class="tt-post-nav__prev">
                                <i class="icon-left-open-big"></i>
                                <div>
                                    <span>Prew post</span>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremqu.
                                    </p>
                                </div>
                            </a>
                            <a href="index.html?page=blog-standart-post.html" class="tt-post-nav__next">
                                <div>
                                    <span>Next post</span>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremqu.
                                    </p>
                                </div>
                                <i class="icon-right-open-big"></i>
                            </a>
                        </div>
                    </div>
                </div> -->

                <div class="tt-page__cont-small col-12">
                    <hr>
                    <div v-if="current_post.comments != null && current_post.comments.length > 0" class="tt-comments tt-post__comments">
                        <div class="tt-comments__title">{{current_post.comments.length}}
                            <span v-if="current_post.comments.length == 1">Comment</span>
                            <span v-if="current_post.comments.length > 1">Comments</span>
                        </div>
                        <!--comment-->
                        <div v-for="(comment, index) in current_post.comments" class="tt-comments__section" :key="index">
                            <template>
                                <!--<a class="tt-comments__image" href="#">
                                <img alt="Image name" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" srcset="images/comments/comments-01.jpg"
                                    style="display: inline;">
                            </a> -->
                                <div style="width: 100%">
                                    <div class="tt-comments__info">
                                        <span>{{comment.user.name}}</span> on {{moment(post.created_at).format("dddd, MMMM Do YYYY")}}</div>
                                    <p style="font-size: 14px">{{comment.comment}}</p>
                                    <!-- <a href="#" class="tt-comments__reply">Reply</a> -->
                                    <!-- <div class="tt-comments__section">
                                        <a class="tt-comments__image" href="#">
                                            <img alt="Image name" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" srcset="images/comments/comments-01.jpg"
                                                style="display: inline;">
                                        </a>
                                        <div>
                                            <div class="tt-comments__info">
                                                <span>Robert</span> on December 28, 2017</div>
                                            <p>Omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam
                                                rem aperiam, eaque ipsa quae ab.</p>
                                            <a href="#" class="tt-comments__reply">Reply</a>
                                        </div>
                                    </div> -->
                                    <div v-if="current_user != null && comment.user.id == current_user.id">
                                        <a @click="removeComment(comment.id)" style="float: right; color: red; cursor: pointer;">remove</a>
                                    </div>
                                </div>

                            </template>
                        </div>
                        <!--comment-->
                    </div>
                </div>
                <div class="tt-page__cont-small col-12">
                    <div class="tt-form tt-post__review">
                        <div class="tt-form__title">Leave a Comment</div>
                        <!-- <p class="ttg__required">Your email address will not be published.</p> -->
                        <div class="tt-form__form">
                            <!--                   <label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span class="ttg__required">Name:</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Enter your name" required="required">
                                    </div>
                                </div>
                            </label>
                            <label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span class="ttg__required">E-mail:</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="John.smith@example.com" required="required">
                                    </div>
                                </div>
                            </label>
                            <label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Website:</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Your website">
                                    </div>
                                </div>
                            </label> -->
                            <label>
                                <div class="row">
                                    <div class="col-2">
                                        <span class="ttg__required">Comment:</span>
                                    </div>
                                    <div class="col-10">
                                        <textarea v-model="comment.content" :placeholder="placeholder" class="colorize-theme6-bg" v-bind:class="{'input-error-select' : error.content}">Wtite your comments here</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span v-if="error.content" class="message-error">{{error_messages.content}}</span>
                                    </div>
                                </div>
                            </label>
                            <div class="row" style="margin-top: 0px !important">
                                <div class="col-10 offset-2" style="text-align: right">
                                    <button @click="addComment()" class="btn btn--xs-flw">
                                        <span v-if="current_user!=null">Submit</span>
                                        <span v-else>Login</span>
                                    </button>
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
    import BlogServices from '../../services/BlogServices';
    export default {
        mixins: [BlogServices],
        props: ['post', 'current_user'],
        data() {
            return {
                moment:moment,
                current_post: '',
                comment: {
                    post_id: '',
                    content: ''
                },
                error: {
                    content: false
                },
                error_messages: {
                    name: '',
                    content: ''
                },
                placeholder:'Leave a comment'
            }
        },
        methods: {
            addComment() {
                if(this.current_user == null){
                    window.location = '/login';
                }else{
                    if (this.comment.content != '') {
                    // $.LoadingOverlay("show");
                    this.addCommentCall(this.comment, this.addCommentCallback);
                    } else {
                        this.error.content = true;
                        this.error_messages.content = 'Comment is required';
                    }
                }
                
            },
            addCommentCallback(code, data) {
                // $.LoadingOverlay("hide");
                if (code == '200') {
                    window.location.reload();
                }
            },
            removeComment(id) {
                // $.LoadingOverlay("show");
                this.removeCommentCall(id, this.removeCommentCallback);
            },
            removeCommentCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    if (this.current_post.comments.length > 0) {
                        for (var i in this.current_post.comments) {
                            if (this.current_post.comments[i].id == data) {
                                this.current_post.comments.splice(i, 1);
                            }
                        }
                    }
                }
            }
        },
        watch: {
            'comment.content'(val) {
                if (val != '') {
                    this.error.content = false;
                    this.error_messages.content = '';
                }
            }
        },
        created() {
            if(this.current_user == null){
                this.placeholder = 'You have to be logged';
            }
            if (this.post) {
                this.current_post = this.post;
                this.comment.post_id = this.post.id;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-post-head__title {
        font-size: 36px
    }

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px !important;
    }

    .message-error {
        color: #d61212 !important;
        float: right !important;
        padding-top: 10px !important;
        font-size: 12px !important;
    }
</style>