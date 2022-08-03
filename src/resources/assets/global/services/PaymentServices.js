export default {
    methods: {
        addPaymentCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/add-payment-method',
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
        removePaymentProfileCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/remove-payment-profile',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch(error => {
                return callBackHandler(error);
            });
        },
        setPaidWithCashCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/paid-with-cash',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch(error => {
                return callBackHandler(error);
            });
        },
        payWithProfileCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/pay-with-profile',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        setAsDefaultCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/set-default-payment_method',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch(error => {
                return callBackHandler(error);
            });
        },
        refundAmountCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/refund-payment',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch(error => {
                return callBackHandler(error);
            });
        },
        voidAmountCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/void-payment',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch(error => {
                return callBackHandler(error);
            });
        },
        getPayments(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/payment/search',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}