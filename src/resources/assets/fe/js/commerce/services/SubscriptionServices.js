export default {
    methods: {
        getPlansInfo(params,callBackHandler){
            axios({
                method: 'post',
                url: '/api/plans/search',
                data:params
            }).then(function (response) {
                callBackHandler(response.data);
            }.bind(this)).catch(function (error) {
                return callBackHandler('002', error);
            });
        },
        setSubscriptionPlanInfo(params, callBackHandler){
            axios({
                method: 'post',
                url: '/api/subscriptions/change',
                data: params
            }).then(function (response) {
                callBackHandler(response.data);
            }.bind(this)).catch(function (error) {
                return callBackHandler('002', error);
            });
        },
        getSubscription(callBackHandler){
            axios({
                method: 'get',
                url: '/api/subscriptions',
            }).then(function (response) {
                callBackHandler(response.data);
            }.bind(this)).catch(function (error) {
                return callBackHandler('002', error);
            });
        },
        checkPaymentMethod(callBackHandler){
            axios({
                method: 'get',
                url: '/api/payment/methods',
            }).then(function (response) {
                callBackHandler(response.data);
            }.bind(this)).catch(function (error) {
                return callBackHandler('002', error);
            });
        },
        addSubscriptionPlanInfo(params, callBackHandler){
            axios({
                method: 'post',
                url: '/api/subscriptions/store',
                data: params
            }).then(function (response) {
                callBackHandler(response.data);
            }.bind(this)).catch(function (error) {
                return callBackHandler('002', error);
            });
        },
    }
}