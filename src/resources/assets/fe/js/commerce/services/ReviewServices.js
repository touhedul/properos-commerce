export default {
    methods: {
        addReview(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/reviews/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        getReviews(item_id,callBackHandler) {
            axios({
                method: 'get',
                url: '/api/reviews/get/'+item_id
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data.data);
                } else {
                    return callBackHandler('001', response.data.error);
                }
            }).catch(error => {
                return callBackHandler('002', error);
            });
        }
    }
}