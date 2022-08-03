export default {
    methods: {
        updateSettings(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/store/settings',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        enableShippingMethodCall(id, callBackHandler) {
            axios({
                method: 'put',
                url: '/api/admin/settings/enable-shipping-method/' + id,
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
        disableShippingMethodCall(id, callBackHandler) {
            axios({
                method: 'put',
                url: '/api/admin/settings/disable-shipping-method/' + id,
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
        removeShippingMethodCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/admin/settings/remove-shipping-method/' + id,
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
        addShippingMethodCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/settings/add-shipping-method',
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
        createTaxCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/taxes/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        updateTaxCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/taxes/update',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        getTaxesCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/taxes/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        removeTaxCall(id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/admin/taxes/'+id+'/delete',
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        getStoreLocationCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/locations/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        createLocationCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/store/locations/store',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        updateLocationCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/store/locations/update',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        removeLocationCall(id, callBackHandler) {
            axios({
                method: 'get',
                url: '/api/store/locations/'+id+'/delete',
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        addPackageCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/packages/store',
                data: data
            }).then(response => {
                return callBackHandler(response);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        updatePackageCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/packages/update',
                data: data
            }).then(response => {
                return callBackHandler(response);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        removePackageCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/admin/packages/'+id+'/delete',
            }).then(response => {
                return callBackHandler(response);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        searchPackagesCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/packages/search',
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
        removeOptionCall(id, callBackHandler) {
            axios({
                method: 'delete',
                url: '/api/admin/options/'+id+'/delete',
            }).then(response => {
                return callBackHandler(response.data);
            })
            .catch(error => {
                return callBackHandler(error);
            });
        },
        searchOptionsCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/options/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        getHomePageSettingsCall(callBackHandler){
            axios({
                method: 'get',
                url: '/api/settings/homepage',
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler(error);
            });
        }
    }
}