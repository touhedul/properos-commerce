var authorize = {
    callBackAuthorize:({}),
    createCardToken(data, callBackAuthorize) {
        var authData = {};
            authData.clientKey = data.clientKey;
            authData.apiLoginID = data.apiLoginID;

        var secureData =  {}; 
            secureData.authData = authData; 

        if (data.type == 'card') {
            var cardData = {};
            cardData.cardNumber = data.cardInfo.card_number;
            cardData.month = data.cardInfo.exp_month;
            cardData.year = data.cardInfo.exp_year;
            cardData.cardCode = data.cardInfo.cvv;
            cardData.fullName = data.cardInfo.card_holder;
            secureData.cardData = cardData; 
        }else if (data.type == 'bank') {
            var bankData =  {}; 
            bankData.accountNumber = data.bankInfo.account_number
            bankData.routingNumber = data.bankInfo.routing_number
            bankData.nameOnAccount = data.bankInfo.name
            bankData.accountType = data.bankInfo.account_type
            secureData.bankData = bankData; 
        }

        this.callBackAuthorize = callBackAuthorize;
        // console.log(this.callBackAuthorize);

        Accept.dispatchData(secureData, this.responseHandler);
    },
    responseHandler(response){
        if (response.messages.resultCode === "Error") {
            authorize.callBackAuthorize({
                status: 0,
                code: response.messages.message[0].code,
                errors: response.messages.message
            });
        } else {
            authorize.callBackAuthorize({
                status: 1,
                data:{
                    token: response.opaqueData.dataValue,
                    description: response.opaqueData.dataDescriptor
                },
                message:''
            });
        }

    }

}

export default{
    authorize: authorize
}