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
                            <div>Hiketron’s Shipping & Returns Policy</div>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Free Shipping Promo-code: </b><span style="cursor: pointer">HikeFree</span><br> All orders placed on this site
                                are subject to product availability and will be shipped according to Hiketron shipping policies.
                                Notification of lost items must be received within 15 days from receipt of the shipping confirmation
                                e-mail. Please notify Hiketron immediately if you received products damaged during shipment.
                                Never throw away packing materials and boxes until instructed to do so by Hiketron customer
                                service.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                In-stock items are normally shipped within 24 to 36 hours after the receipt of your order unless you opted for other shipping
                                arrangements. During the Holidays, we do try to ship within 24 hours of receipt of your order,
                                but delays during the Holidays should be expected. Please note that we do not ship on Saturday
                                or Sunday. You will be notified by phone or e-mail within 24 hours of receipt of your order
                                if there will be a delay. All products out of stock will be mentioned in our website. However,
                                if for some reason, you order an item that is temporarily sold out at the time you placed
                                your order, we will place your order on hold and ship it complete once this item is back
                                in stock. Please make sure you provide us with a daytime phone number and accurate e-mail
                                address, so we can update your order progress.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Free Shipping Calculations</b><br> Free shipping is calculated based on the value of the
                                purchased products in your shopping cart. Taxes, Gift Certificates, and other charges do
                                not apply to the calculation of free shipping.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Free Shipping Offers / Contiguous 48 United States</b><br> In the contiguous 48 United
                                States, Best Rate Shipping is FREE on all orders above $79.00+, a promotional code is required
                                at checkout. The Promo Code can be found at the top of nearly every page or you can find
                                it here. Free Shipping Promo Code: <span>HikeFree</span> do not include the quotes in the
                                promo code. Shipping costs DO NOT APPLY toward free shipping promotions, only product costs
                                are calculated. You should receive your order within 6 to 14 business days (Mon-Friday) from
                                the date that we ship. We reserve the right to choose the best method for Best Rate shipping
                                (USPS, UPS, UPS Surepost, Fedex, etc). <span style="color: red"> Office Box, APO, FPO, and DPO Addresses DO NOT Qualify for
                                free shipping offers.</span>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Summer Shipping Schedule</b><br> Many of the products sold by Hiketron are temperature
                                sensitive and can be damaged during the extreme summer heat. In order to ensure your order
                                is free of damage, temperature sensitive products will only ship Monday, Tuesday, and Wednesday.
                                This is required to ensure orders are not sitting in non-climate control facilities during
                                shipping. All other products that are not affected by temperature will ship Monday-Friday
                                as normal.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Upgraded Shipping Charges</b><br> If you decide to pay for upgraded shipping to speed
                                the delivery of your purchase, please remember that the items must be in stock. If for some
                                reason the products you ordered are not in stock, you will be notified. You may then choose
                                to change the products to something that is available or wait for the products to be delivered.
                                We will refund the difference between the base shipping rate of $9.95 and the rate you paid
                                for the shipping upgrade on your order if it is delayed. If your order qualified for free
                                shipping and you paid for the upgrade; the complete shipping cost will be reimbursed. You
                                will only be charged for the upgrade if the order can be shipped out within 48 hours of the
                                order being placed.
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
                                <b>Address Changes in Transit (UPS only)</b><br>Customers requiring or requesting a change
                                in the shipping address after the purchase has been shipped and is still on route will be
                                charged a fee of $15.00. We can only provide this service if the package was shipped via
                                UPS, this fee is a UPS charge that is passed on to the customer for the service. Be sure
                                to check the shipping address provided on your order confirmation at the time the order is
                                placed.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Incorrect Address Issues</b><br>Customers need to check the shipping address on their
                                order to ensure the address is error free. Be sure to include any unique suite or apartment
                                numbers; otherwise, the order may be returned to us due to insufficient address. The orders
                                that are returned will be charged additional shipping for subsequent delivery. Free shipping
                                will not be applied twice for orders that meet those conditions prior to the return. Customers
                                will be contacted and made aware of any delivery issues. If the order is not reshipped at
                                the expense of the customers, a 15% restocking fee will be applied, and any original shipping
                                cost will also be charged. Any remaining balance will be credited back to the customer's
                                credit card.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>UPS Ground Transit Shipping Times
                                    </b><br>
                                    <img src="/images/outboundMap.png" style="widows: 100%; height: auto">
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