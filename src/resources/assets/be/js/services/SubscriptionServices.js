export default {
    methods: {
        //ApiCalls
        searchSubscriptions(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/subscriptions/search',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        saveSubscriptionCall(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/subscriptions/save',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        updateSubscriptionCall(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/subscriptions/update',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        removeSubscriptionCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/subscriptions/remove/'+id,
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}