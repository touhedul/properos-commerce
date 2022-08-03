var stripe = {
    callBackStripe:({}),
    // data:{},
    createCardToken(data, callBackStripe) {
        this.callBackStripe = callBackStripe;
        // this.data = data;
        if (data.type == 'card') {
            Stripe.card.createToken({
                number: data.cardInfo.card_number,
                cvc: data.cardInfo.cvv,
                exp_month: data.cardInfo.exp_month,
                exp_year: data.cardInfo.exp_year,
                address_zip: data.cardInfo.billing_zip,
                address_line1: data.cardInfo.billing_address, 
                address_city: data.cardInfo.billing_city, 
                address_state: data.cardInfo.billing_state, 
            }, this.stripeResponseHandler);
        }

    },
    stripeResponseHandler(status, response){
        if (status != 200) {
            stripe.callBackStripe({
                        status: 0,
                        code: '006',
                        errors: response.error.message
                    });
        } else {
            stripe.callBackStripe({
                status: 1,
                data:{
                    token: response.id,
                    // description: stripe.data.description
                },
                message:''
            });
        }

    }

}

export default{
    stripe: stripe
}