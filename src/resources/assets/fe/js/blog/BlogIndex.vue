<template>
    <div class="tt-layout tt-sticky-block__parent ">
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
                <div class="tt-page__name">
                    <h4 style="font-size: 40px">Properos's blog</h4>
                </div>
                <div v-if="current_posts.length > 0">
                    <div v-for="(post, index) in current_posts" class="row" style="padding: 40px" :key="index">
                        <div class="col-md-4 offset-md-2">
                            <a v-if="post.header_media_type == 'picture'" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" style="width: 100%; height: auto">
                                <img :src="'/storage/' + post.header_media_path" alt="Image name">
                            </a>
                            <div v-else-if="post.header_media_type == 'video'" class="embed-responsive embed-responsive-item embed-responsive-16by9">
                                <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path"></youtube>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" class="tt-post-grid__title">{{post.title}}
                            </a>
                            <div>
                                <span>Properos - </span> {{moment(post.created_at).format('LLLL')}}</div>
                                <!-- <span>{{post.author.name}} - </span> {{moment(post.created_at).format('LLLL')}}</div> -->
                            <p>
                                {{post.summary}}
                            </p>
                            <a v-if="post.comments.length > 0" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" class="tt-post-grid__comments" style="float: left">
                                <i class="icon-comment-empty"></i>
                                <span>{{post.comments.length}}</span>
                            </a>
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" class="tt-post-grid__comments" style="float: right">
                                <span>Read more</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div style="text-align: center">
                        <h4>There aren't posts published yet :(</h4>
                        <!-- <a href="/admin/add-blog-post">Be the first</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mixins: [],
        props: ['posts'],
        data() {
            return {
                moment:moment,
                current_posts: []
            }
        },
        methods: {

        },
        created() {
            if (this.posts) {
                this.current_posts = this.posts;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-post-grid__title {
        font-size: 24px;
    }

    .embed-responsive {
        position: relative !important;
    }

    hr {
        color: #123455;
    }

     @media (min-width: 480px){
        .container {
            width: 750px;
            max-width: 100%;
        }
    }
    @media (min-width: 768px){
        .container {
            width: 992px;
            max-width: 100%;
        }
    }
</style>