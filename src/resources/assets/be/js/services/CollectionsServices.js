export default {
    methods: {
        searchCollections(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/collections/search',
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
        createCollectionCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/collections/create',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        updateCollectionCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/collections/update',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        removeCollectionCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/collections/delete/'+id,
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}