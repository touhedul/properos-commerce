export default {
    methods: {
        //ApiCalls
        addItemCall(item_id, qty, data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/cart/set/' + item_id + '/' + qty,
                data:data
            }).then(response => {
                if (response.data.status > 0) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler(response.data.code, response.data.errors);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        changeQtyCall(item_id, qty, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/cart/changeQty/' + item_id + '/' + qty,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler(response.data.code, response.data.data, response.data.errors);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        removeItemCall(item_id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/cart/remove/' + item_id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, item_id);
                } else {
                    return callBackHandler('001', response.data.errors, item_id);
                }
            }).catch(error => {
                return callBackHandler('002', error, item_id);
            });
        },

        cancelOrderCall(order_id, email, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/guest/orders/cancel/' + order_id + '/' + email
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        filterByCategoryCall(min_price, max_price, categories_id, current_page, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/items/filter/category/' + categories_id + '/' + min_price + '/' + max_price + `?page=` + current_page
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },
        filterByCollectionCategoryCall(collection_id, min_price, max_price, categories_id, current_page, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/items/filter/collection/category/'+ collection_id + '/' + categories_id + '/' + min_price + '/' + max_price + `?page=` + current_page
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        filterByPriceRangeCall(min_price, max_price, categories_id, current_page, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/items/filter/price-range/' + min_price + '/' + max_price + '/' + categories_id + `?page=` + current_page
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        getCurrentWishListCall(callBackHandler) {
            axios({
                method: 'get',
                url: '/api/wishlist/get'
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },

        placeOrders(order, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/guest/orders/place-order',
                data: order
            }).then(response => {
                return callBackHandler(response.data);
            }).catch(error => {
                swal("Error!", error, "error");
            });
        },
        getUpsShippingRatesCall(address, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/cart/shipping-rates/ups',
                data: address
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data, 0);
                } else {
                    return callBackHandler(response.data.code, response.data.data, response.data.errors);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        },
        searchItemsCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/items/filter/search-items',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch(error => {
                return callBackHandler(error);
            });
        }
    }
}