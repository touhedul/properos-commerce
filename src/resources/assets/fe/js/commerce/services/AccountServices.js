export default {
    methods: {
        addAddressCall(address, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/my-account/add-address',
                data: address
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        updateAddressCall(address, id, callBackHandler) {
            axios({
                method: 'put',
                url: '/api/my-account/update-address/' + id,
                data: address
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        setDefaultAddressCall(id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/my-account/set-default/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },

        removeAddressCall(id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/my-account/remove-address/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },

        changePasswordCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/my-account/change-password',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error.response.data);
            });
        },

        addPaymentMethodCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/my-account/add-payment-method',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return error;
            });
        },

        removePaymentMethodCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/my-account/remove-payment-method/' + id,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },

        updateProfile(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/my-account/update-profile',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return error;
            });
        },

        getTrackingInfoCall(carrier, tracking_number, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/orders/tracking/' + carrier + '/' + tracking_number,
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return error;
            });
        },
        getCountriesCall(callBackHandler) {
            axios({
                method: 'get',
                url: '/api/store/get-countries',
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return error;
            });
        },
        getStatesCall(callBackHandler) {
            axios({
                method: 'get',
                url: '/api/store/get-states',
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.message, response.data.data);
                } else {
                    return callBackHandler('001', response.data.message, response.data.errors);
                }
            }).catch((error) => {
                return error;
            });
        },
        validateDiscount(code, order, callBackHandler){
            axios({
                method: 'post',
                url: '/discounts/check/'+code,
                data: order
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return error;
            });
        }
    }
}