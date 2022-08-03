export default {
    methods: {
        getUsers(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/get-users',
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
        getSubscribers(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/subscribers/search',
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
        getActivities(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/activities-log/search',
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