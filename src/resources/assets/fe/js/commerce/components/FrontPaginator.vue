<template>
    <div class="tt-pagination center">
        <div v-on:click.prevent="changePage(pagination.current_page - 1)" class="tt-btn colorize-btn5 tt-btn__state--active pagination-button"
            :class="{'disable-button' : pagination.current_page <= 1}">
            <span style="font-size: 10px !important; color: #fff" class="icon-left-open-1" :class="{'disable-arrow'  : pagination.current_page <= 1}"></span>
        </div>
        <div class="tt-pagination__numbs">
            <div v-for="(page, index) in pagesNumber" :key="index" v-on:click.prevent="changePage(page)" :class="{'tt-btn colorize-btn5 tt-btn__state--active pagination-button': page == pagination.current_page}">
                <span style="top: 50%; left: 50%; cursor: pointer; font-size: 10px">{{ page }}</span>
            </div>
        </div>
        <div v-on:click.prevent="changePage(pagination.current_page + 1)" class="tt-btn colorize-btn5 tt-btn__state--active pagination-button"
            :class="{'disable-button': pagination.current_page >= pagination.last_page}">
            <span style="font-size: 10px !important" class="icon-right-open-1" :class="{'disable-arrow' : pagination.current_page >= pagination.last_page}"></span>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            pagination: {
                type: Object,
                required: true
            },
            offset: {
                type: Number,
                default: 5
            }
        },
        computed: {
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;

            }
        },
        methods: {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        mounted() {
            /* console.log(this.pagesNumber); */
        }
    }
</script>
<style>
    .pagination-button {
        width: 28px;
        height: 28px;
        text-align: center;
        cursor: pointer;
        margin-right: 2px;
        font-size: 10px;
        font-weight: bold;
    }

    .disable-button {
        pointer-events: none;
        border-color: rgb(196, 197, 197) !important;
        color: rgb(80, 80, 80) !important;
        font-weight: bold;
        background-color: rgb(229, 230, 231) !important;
    }

    .disable-arrow {
        color: gray !important;
    }

    .center {
        margin: auto;
        width: 60%;
        padding: 10px;
    }

    @media (min-width: 576px) {
        .center {
            margin: auto;
            width: 35%;
            padding: 10px;
        }
    }

    @media (min-width: 768px) {
        .center {
            margin: auto;
            width: 25%;
            padding: 10px;
        }
    }

    @media (min-width: 992px) {
        .center {
            margin: auto;
            width: 25%;
            padding: 10px;
        }
    }

    @media (min-width: 1200px) {
        .center {
            margin: auto;
            width: 30%;
            padding: 10px;
        }
    }
</style>