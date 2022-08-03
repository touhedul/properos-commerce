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
                            <div>Privacy policy
                            </div>

                            <p style="text-align: justify; font-size: 16px">
                                <b>Hiketron - Privacy Statement
                                    </b><br>Hiketron is committed to protecting your personal
                                information. We want your personal information to remain private. If you have questions about
                                Hiketron privacy practices, contact us at +1.281.375.9175 or privacy@hiketron.com Hiketron
                                follows these privacy principles both online and offline in the collection, use, and security
                                of customer information.
                                <ul style="list-style: circle; color: #777777">
                                    <li>
                                        Provide you notice of our customer information practices.
                                    </li>
                                    <li>
                                        Give you choices about how your data may be used for marketing purposes.
                                    </li>
                                    <li>
                                        Provide you the opportunity to update or correct your personal information.
                                    </li>
                                    <li>
                                        Use information security safeguards.
                                    </li>
                                    <li>
                                        Commit to complying with applicable privacy requirements.
                                    </li>
                                    <li>
                                        Provide you with means to contact us about privacy-related issues.
                                    </li>
                                </ul>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                We value the relationship we have with our customers and are committed to responsible information-handling practices. We
                                take great care in safeguarding your personal information and in complying with all applicable
                                federal and state privacy laws and our own internal standards and best practices.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Hiketron PRIVACY POLICY 
                                    </b><br>The following Privacy Policy describes the information
                                and privacy practices for Hiketron.com and all other locations, occasions or events where
                                your personal information is collected by Hiketron. When you provide us your personal information,
                                you consent to the information practices described in this policy.<br> To assist you with
                                reading through our Privacy Policy, we have provided questions and answers below that you
                                may find helpful in understanding our privacy practices:<br><br>
                                <b>Privacy Policy FAQs:
                                    </b>
                                <ul style="list-style: circle; color: #777777">
                                    <li>
                                        What information does Hiketron collect from me?
                                    </li>
                                    <li>
                                        When does Hiketron collect information from me?
                                    </li>
                                    <li>
                                        How is my information used?
                                    </li>
                                    <li>
                                        What information is provided to Hiketron from others?
                                    </li>
                                    <li>
                                        Does Hiketron share my personal information with others?
                                    </li>
                                    <li>
                                        Do I have choices regarding the use of my information for marketing purposes?
                                    </li>
                                    <li>
                                        How does Hiketron secure my personal information?
                                    </li>
                                    <li>
                                        How can I update my information?
                                    </li><br>
                                    <li style="list-style: none">
                                        <b>What information does Hiketron collect from me? 
                                                </b>
                                        <br>We may collect personal information such as:<br>
                                        <ul style="list-style: none; color: #777777">
                                            <li>
                                                - Your contact information (name, mailing address, phone number, e-mail address, etc.).
                                            </li>
                                            <li>
                                                - Your billing/shipping information (credit card number, shipping address).
                                            </li>
                                            <li>
                                                - Your personal preferences (product wishlists, etc.).
                                            </li>
                                        </ul>
                                    </li><br>
                                    <li style="list-style: none">
                                        <b>When does Hiketron collect information from me?
                                                </b>
                                        <br>While shopping or taking advantage of services available from us, we may ask
                                        that you provide<br> personal information. For example, we request personal information
                                        when you:<br>
                                        <ul style="list-style: none; color: #777777">
                                            <li>
                                                - Set up an account online.
                                            </li>
                                            <li>
                                                - Purchase products or services.
                                            </li>
                                            <li>
                                                - Schedule delivery.
                                            </li>
                                            <li>
                                                - Participate in a promotion or survey.
                                            </li>
                                            <li>
                                                - Contact us with a question or concern.
                                            </li>
                                        </ul>
                                    </li><br>
                                    <li style="list-style: none">
                                        <b>How is my information used?
                                                </b>
                                        <br>We use the information you provide in order to:
                                        <br>
                                        <ul style="list-style: none; color: #777777">
                                            <li>
                                                - Fulfill requests for products, services or information.
                                            </li>
                                            <li>
                                                - Administer loyalty programs.
                                            </li>
                                            <li>
                                                - Schedule delivery.
                                            </li>
                                            <li>
                                                - Provide customer services.
                                            </li>
                                            <li>
                                                - Offer new products and services.
                                            </li>
                                        </ul>
                                    </li>
                                    </li>
                                </ul>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>What if information is provided to Hiketron from others? </b><br> If you provide us information
                                about others, or if others give us your information, we will only use that information for
                                the specific reason it was provided. Examples include providing a friend's shipping address,
                                using our Send-to-a-Friend option in our marketing e-mails or Gift Registry information.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Does Hiketron share my personal information with others? 
                                    </b><br> Hiketron does not sell or rent your personal
                                information to third parties. We may need to share your personal information with certain
                                third parties such as our service providers and other representatives acting on our behalf
                                for limited purposes. For example, we may share personal information with third parties to
                                perform services on our behalf such as:<br>
                                <ul style="list-style: none; color: #777777">
                                    <li>
                                        - Fulfilling our customers' orders.
                                    </li>
                                    <li>
                                        - Delivering packages (UPS, USPS, FEDX).
                                    </li>
                                    <li>
                                        - Maintaining our loyalty program.
                                    </li>
                                    <li>
                                        - Processing credit card payments.
                                    </li>
                                </ul>
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>Do I have a choice regarding the use of my information for marketing purposes? 
                                    </b><br>Hiketron is an opt-out organization. Once we
                                receive your information, we may send you marketing communications that contain valuable
                                promotions and other content. You always have the ability to opt-in or opt-out of receiving
                                future marketing communications from Hiketron.<br> To opt-out of or opt-in to receiving marketing
                                communications from Hiketron, please take one of the following actions:
                                <br>
                                <ul style="list-style: none; color: #777777">
                                    <li>
                                        1- Follow the directions on the marketing e-mail from us. All marketing communications<br> from Hiketron will tell you how to
                                        stop receiving them in the future.
                                    </li>
                                    <li>
                                        2- Call +1.281.375.9175 and provide your current contact information.
                                    </li>
                                    <li>
                                        3- Send a letter with your current contact information to: Hiketron Inc. 245 Koomey Rd Brookshire, TX 77423.
                                    </li>
                                </ul>
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
                                from our marketing lists, it may take up to: 14 business days for e-mails, direct mail.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Also, please note that even though you may have opted out of receiving marketing communications, you may still receive business-related
                                communications such as order confirmations, product recall information, or updates or other
                                organization-related communications.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>How does Hiketron secure my personal information? 
                                    </b><br>Whether you are shopping online or in our stores,
                                we have security measures in place and take reasonable precautions to protect against the
                                loss, misuse and unauthorized access of your personal information under our control. Hiketron
                                cannot ensure or warrant the security of any information you transmit to us by e-mail, and
                                you do so at your own risk.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                Because the security of your personal information is a high priority, we have taken numerous steps to ensure that it is processed
                                confidentially, accurately and securely. The Hiketron Web sites use encryption technology,
                                such as Secure Sockets Layer (SSL), to protect your personal information during data transport.
                                SSL encrypts ordering information such as your name, address and credit card number.
                            </p>
                            <p style="text-align: justify; font-size: 16px">
                                <b>How can I update my information? 
                                        </b><br>Hiketron wants your information and marketing
                                preferences to be accurate and complete. We provide several different methods for you to
                                update your personal information. To update your information:<br> If you’ve created an account
                                on Hiketron.com, you can update your information after logging into your account<br> <b>Call:</b>
                                +1.281.375.9175 <br><b>Send a request to:</b> <br>Attn: Customer Care <br>Hiketron Inc. <br>245 Koomey Rd <br>Brookshire,
                                TX 77423
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