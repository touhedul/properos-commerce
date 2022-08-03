export default {
    methods: {
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
        getShippingInfoCall(data, carrier, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/orders/shipping-info/' + carrier,
                data: data
            }).then(response => {
                
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    
                    return callBackHandler(response.data.code, response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        printLabelCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/orders/print-label',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    return callBackHandler(response.data.code, response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        sendInvoiceEmailCall(id, email, subject, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/orders/send-invoice-email/' + id,
                data:{
                    email:email,
                    subject: subject
                }
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    return callBackHandler(response.data.code, response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        sendQuoteEmailCall(id, email, subject, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/orders/send-quote-email/' + id,
                data:{
                    email:email,
                    subject:subject
                }
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    return callBackHandler(response.data.code, response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
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
        searchOrders(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/orders/search',
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
        getTax(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/get-tax',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}