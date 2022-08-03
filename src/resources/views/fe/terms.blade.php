@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<style>
    .tt-about__info div,
    .tt-about__info p {
        max-width: 820px;
    }

    #theme .tt-about__info {
        background-color: #fff;
    }

    .header-image {
        background-image: url('/images/rawpixel-585641-unsplash.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        width: 120%;
        height: 500px;
        width: 950%;
        opacity: 0.9
    }
</style>
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent tt-layout__fullwidth">
    <div class="tt-layout__content">
        <div class="container">
            {{--
            <div class="tt-page__breadcrumbs">
                <ul class="tt-breadcrumbs">
                    <li><a href="/"><i class="icon-home"></i></a></li>
                    <li><a href="/about">About us</a></li>
                </ul>
            </div> --}}
            <div class="tt-about" style="margin-bottom: 0px; margin-top: 0px;">
                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                    <div class="header-image">

                    </div>
                </div>
                <div class="row ttg-grid-padding--none">
                    <div class="col-md-12">
                        <div class="tt-about__info">
                            <div>Hiketron’s Terms and Conditions</div>

                            <p style="text-align: justify; font-size: 16px">
                                <b>Terms and Conditions of Use</b><br>Welcome to the Hiketron Website. By using the Hiketron
                                Website, YOU AGREE TO BE BOUND BY ITS CONDITIONS OF USE (explained below), PRIVACY POLICY
                                and all disclaimers and terms and conditions that appear elsewhere on the Hiketron Website.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Hiketron reserves the right to make changes to the Hiketron Website and its Conditions of Use and Privacy Policy at any time.
                                Each time you use the Hiketron Website, you should visit and review the then current Conditions
                                of Use, and Privacy Policy that apply to your transactions and use of this site. If you are
                                dissatisfied with the Hiketron Website, its content or Conditions of Use, you agree that
                                your sole and exclusive remedy is to discontinue using the Hiketron Website.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Tampering with the site, misrepresenting the identity of a user, using buying agents or conducting fraudulent activities
                                on the site are prohibited.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Disclaimers and Limitation of Liability
                                    </b><br>By using the Hiketron Website, you expressly
                                agree that the use of the Hiketron Website is at your sole risk. The Hiketron Website is
                                provided on an "AS IS" and "as available" basis.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Under no circumstances shall Hiketron or its affiliates be liable for any direct, indirect, incidental, special or consequential
                                damages that result from your use of or inability to use the Hiketron Website, including
                                but not limited to reliance by you on any information obtained from the Hiketron Website
                                that results in mistakes, omissions, interruptions, deletion or corruption of files, viruses,
                                delays in operation or transmission, or any failure of performance. The foregoing Limitation
                                of Liability shall apply in any action, whether in contract, tort or any other claim, even
                                if an authorized representative of Hiketron has been advised of or should have knowledge
                                of the possibility of such damages. User hereby acknowledges that this paragraph shall apply
                                to all content, merchandise and services available through the Hiketron Website.

                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Intended for Users Over 13
                                    </b><br>The Hiketron Website is intended for use by individuals
                                13 years of age or older. The Hiketron Website is not directed to use by children under the
                                age of 13. Users under the age of 13 should get the assistance of a parent or guardian to
                                use this site.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Refused Orders</b><br> Packages that are refused when they reach their shipping destination
                                will be charged shipping, and a restocking fee of 15%. The shipping fee will only be charged
                                if your order was shipped with FREE SHIPPING. If for some reason your order was refused,
                                but you still want the order. You will be required to repay the new shipping charge in order
                                to receive it. The second attempt at delivery must be at a different address, we will not
                                ship the order a second time to an address where it has been already refused once.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Product Display/Colors
                                    </b><br>The Hiketron Website attempts to display product
                                images shown on the site as accurately as possible. However, we cannot guarantee that the
                                color you see matches the product color, as the display of the color depends, in part, upon
                                the monitor you are using.

                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Errors on Our Site
                                    </b><br>Prices and availability of products and services
                                are subject to change without notice. Errors will be corrected where discovered, and Hiketron
                                reserves the right to revoke any stated offer and to correct any errors, inaccuracies or
                                omissions including after an order has been submitted and whether or not the order has been
                                confirmed and your credit card charged. If your credit card has already been charged for
                                the purchase and your order is cancelled, Hiketron will issue a credit to your credit card
                                account in the amount of the charge. Individual bank policies will dictate when this amount
                                is credited to your account. If you are not fully satisfied with your purchase, you may return
                                it in accordance with Hiketron’s Return Policy.<br><br>
                                <b>Sales Tax Policy
                                    </b><br>Because Hiketron is a Texas corporation, sales
                                tax will be collect for Texas Residents as required by law.

                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Paying for Your Order
                                    </b><br>You may pay for your orders with our electronic
                                Gift Certificates and with a major credit cards issued in the United States of America. Currently,
                                we accept Visa®, MasterCard®, Discover® Card, PayPal Express, Google Checkout and American
                                Express.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Generally, all credit and debit cards are charged at the time the order is placed. If for some reason we are currently out
                                of the product or the product is back ordered you will be contacted to discuss possible options.
                                If you do decide to cancel your order, we will immediately begin the process to credit back
                                your canceled purchase. Please allow 24 to 36 hours for the credit to be completed.

                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Order Acceptance/Confirmation
                                        </b><br>Your receipt of an electronic or other form
                                of order confirmation does not signify our acceptance of your order, nor does it constitute
                                confirmation of our offer to sell. The Hiketron Website reserves the right at any time after
                                receipt of your order to accept or decline your order for any reason. However, once you have
                                completed your order, our system will send an E-mail showing you a copy of the order as it
                                was received by Hiketron. The customer is responsible for making sure they receive this E-mail
                                confirmation and reviewing the order to make the order is correct. It is extremely important
                                the customers E-mail address is entered correctly. If your order is delivered and then you
                                notice that the order is not what you intended, all return and shipping fees are the responsibility
                                of the customer. We are more than happy to exchange the product as long as the Return Policy
                                is followed. However, we are not responsible for any of the shipping costs involved in returning
                                and reshipping the desired products back to the customer.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Order Cancelations
                                        </b><br>If you would like to cancel an order you
                                placed with Hiketron, you may contact us by phone or Email. Your cancelation request must
                                be submitted before your order leaves our shipping facility to avoid shipping and restocking
                                fees. Orders that are canceled after leaving our facility will be charged for shipping fees
                                we have incurred, even if you shipping was free on the order you placed. Once your order
                                leaves our facility we are charged for shipping by our shipping company. We will also charge
                                a restocking fee of 15% for all returning merchandise.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Return Policy
                                        </b><br>All sales made through the Hiketron Website
                                are subject to Hiketron’s return policy. You have 14 days from the time you receive your
                                purchase to return or exchange any merchandise when accompanied by the original receipt.
                                All returning merchandise must be in the original shipment boxes, products must be in original
                                condition and undamaged. You must call or E-mail Customer Service to obtain authorization
                                to return purchased merchandise. FREE SHIPPING ONLY APPLIES TO YOUR ORIGINAL PURCHASE. All
                                return/exchange shipping fees are the responsibility of the customer. Returns or Exchanges
                                will not be accepted on discounted or retired items. Please send your return package via
                                Fed Ex, UPS or Insured Priority Mail. The return policy does not apply to sale or discounted
                                merchandise. Call <b>+1.281.375.9175</b> or email <b>returns@hiketron.com</b>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Free Shipping/Promotional Codes
                                        </b><br>All promotional codes must be entered during
                                the checkout process at the time the order is submit for processing. You may however contact
                                customer service within 36 hours of placing your order to request the free shipping code
                                if you forgot to enter the promotional code during checkout. All other promotional codes
                                must be entered during the checkout process.<br><br>
                                <b>Shipping Policy
                                    </b><br>
                                <a href="/shipping-return-policy">See shipping policy</a>
                                <br>
                                <b>Privacy Policy
                                    </b><br>
                                <a href="/privacy">See privacy policy</a>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>You have a choice regarding the use of your information for marketing purposes.
                                            </b><br>Hiketron is an opt-out organization.
                                Once we receive your information, we may send you marketing communications that contain valuable
                                promotions and other content. You always have the ability to opt-in or opt-out of receiving
                                future marketing communications from Hiketron.<br><br> To opt-out of or opt-in to receiving
                                marketing communications from Hiketron, please take one of the following actions:
                                <ol>
                                    <li>
                                        <p style="text-align: left">
                                            1- Follow the directions on the marketing e-mail from us. All marketing communications from Hiketron will tell you how to
                                            stop receiving them in the future.
                                        </p>
                                        <p style="text-align: left">
                                            2- Call +1.281.375.9175 and provide your current contact information.
                                        </p>
                                        <p style="text-align: left">
                                            3- Send a letter with your current contact information to: Hiketron Inc. 245 Koomey Rd Brookshire, TX 77423
                                        </p>
                                    </li>
                                </ol>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                If you send an e-mail or letter request, please be sure to include your full name, address, phone number and e-mail address
                                and indicate specifically what type of marketing communication(s) (e.g., e-mail, direct mail)
                                you wish to receive or stop receiving. This will ensure we identify you correctly in our
                                systems and accurately process your request.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Hiketron will take appropriate steps to implement your request to opt-out of or opt-in to receiving marketing communications.
                                Please note that due to production, mailing and system timelines, in order to remove you
                                from our marketing lists, it may take up to:
                                <ol>
                                    <li>
                                        <p style="text-align: left">
                                            14 business days for direct mail, but Email is usually removed in 24 hours
                                        </p>
                                    </li>
                                </ol>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Also, please note that even though you may have opted out of receiving marketing communications, you may still receive business-related
                                communications such as order confirmations, product recall information, or updates or other
                                organization-related communications.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('local_script')
@endsection