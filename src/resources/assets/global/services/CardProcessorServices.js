export default {
    methods: {
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
        getPaymentMethod(id,callBackHandler) {
            axios({
                method: 'get',
                url: '/api/my-account/get-payment-method/'+id,
            }).then(response => {
                    return callBackHandler(response.data);
            }).catch((error) => {
                return error;
            });
        },
    }
}