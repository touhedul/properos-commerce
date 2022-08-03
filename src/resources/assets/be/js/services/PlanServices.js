export default {
    methods: {
        //ApiCalls
        searchPlans(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/plans/search',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        savePlanCall(params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/plans/store',
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        updatePlanCall(id, params, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/plans/update/'+id,
                data: params
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        removePlanCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/plans/remove/'+id,
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}