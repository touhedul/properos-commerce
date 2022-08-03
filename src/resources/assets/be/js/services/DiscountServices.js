export default {
    methods: {
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
        },
        storeDiscount(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/discounts/store',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        updateDiscount(id, params, callBackHandler) {
            axios({
                method: 'put',
                url: '/api/discounts/'+id+'/update',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}