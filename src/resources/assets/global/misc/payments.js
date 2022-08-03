import authorize from './authorize'
import stripe from './stripe'

window.payments = {
    driver(driver, type = "card", paypal = false){
        var payment = {};
        if(driver.toLowerCase() == 'authorize'){
            payment[type] = authorize.authorize;
        }else if(driver.toLowerCase() == 'stripe'){
            payment[type] = stripe.stripe;
        }
        if(paypal){
            payment['paypal'] = true;
        }

        return payment;
    }

}