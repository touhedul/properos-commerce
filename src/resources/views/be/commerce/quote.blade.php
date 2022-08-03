<!DOCTYPE html>
<html>

<head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/images/favicon.png?v=2" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/imgs/favicon.png?v=2">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <style>
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        nav,
        section {
            display: block
        }

        audio[controls],
        canvas,
        video {
            display: inline-block
        }

        [hidden],
        audio {
            display: none
        }

        mark {
            background: #FF0;
            color: #000
        }
        select.form-control:not([size]):not([multiple]) {
            height: calc(1.4rem + 2px)!important;
        }
    </style>
    <meta charset="utf-8">
    <title>Quote</title>
    <link rel="stylesheet" href="/be/assets/vendor/bootstrap/bootstrap.css" />
    <link rel="stylesheet" href="/be/assets/css/invoice.css">
    <link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
    <style>
        .loader {
            border: 8px solid #f3f3f3;
            /* Light grey */
            border-top: 8px solid #b5425d;
            border-bottom: 8px solid #265996;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <form id="pay_form" method="POST" style="display:none;">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    </form>
    <header>
        @if(!isset($packing))
            <h1>Quote</h1>
        @else
            <h1>Packing Slip</h1>
        @endif
        <address>
                <p>{{config('invoice.title')}}</p>
				<p>{{config('invoice.address')}}<br>{{config('invoice.city')}}, {{config('invoice.state')}} {{config('invoice.zip')}}, {{config('invoice.country')}}</p>
				<p>{{config('invoice.phone')}}</p>
			</address>
        <span><img class="" alt="" src="/images/invoice_logo.png" style="width:208px;height:auto;"></span>
    </header>
    <article>
        <h1>Recipient</h1>
        <address>
                @if($order->customer_company)
                <p id="bill_company" style="margin-bottom:5px;">{{$order->customer_company}}</p>
                @endif
                <p id="bill_name" style="margin-bottom:5px;">{{$order->customer_name}}</p>
				<p id="bill_address" style="margin-bottom:5px;">{{$order->shipping_address1}} {{$order->shipping_address2}} {{$order->shipping_city}} {{$order->shipping_zip}} {{$order->shipping_state}} {{$order->shipping_country}}</p>
				<p id="bill_email" style="margin-bottom:5px;">{{$order->customer_email}}</p>
				
			</address>
        <table class="meta">
            <tbody>
                <tr>
                    <th><span>Quote #</span></th>
                    <td><span id="bill_number">{{$order->order_number}}</span></td>
                </tr>
                <tr>
                    <th><span>Date</span></th>
                    <td><span id="bill_date">{!! date("M d, Y",strtotime($order->created_at)) !!}</span></td>
                </tr>
                @if(!isset($packing))
                <tr>
                    <th><span id="bill_total">Amount Due</span></th>
                    <td><span id="prefix" contenteditable="">$</span><span>{{number_format(($order->total_amount - $order->paid_amount),2)}}</span></td>
                </tr>
                @endif
            </tbody>
        </table>
        <table class="inventory">
            <thead>
                <tr>
                    <th><span>Item</span></th>
                    <th><span>Description</span></th>
                    @if(!isset($packing))
                    <th><span>Price </span></th>
                    @endif
                    <th><span>Quantity</span></th>
                    @if(!isset($packing))
                    <th><span>Subtotal</span></th>
                    @endif
                </tr>
            </thead>
            <tbody id="invoice_body">
                @foreach($order->orderItems as $order_item)
                <tr>
                    <td><span>{{ $order_item->sku }}</span></td>
                    <td><span>{{ $order_item->name }}</span></td>
                    @if(!isset($packing))
                        @if($order_item->discount_percent > 0)
                            <td><span data-prefix="">$</span><span>{{number_format(($order_item->price - ($order_item->price*$order_item->discount_percent/100)),2)}}</span></td>
                        @else
                            <td><span data-prefix="">$</span><span>{{number_format($order_item->price,2)}}</span></td>
                        @endif
                    @endif
                    <td><span>{{($order_item->qty % 1 != 0) ? $order_item->qty : round($order_item->qty,2) }}</span></td>
                    @if(!isset($packing))
                    <td><span data-prefix="">$</span><span>{{number_format((($order_item->price - ($order_item->price*$order_item->discount_percent/100)) * $order_item->qty),2)}}</span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @if(!isset($packing))
        <table class="balance">
            <tbody>
                <tr>
                    <th><span contenteditable="">Items Subtotal</span></th>
                    <td><span data-prefix="">$ <span contenteditable="">{{ number_format($order->subtotal,2) }}</span></span></td>
                </tr>
                @if($order->discount_amount > 0)
                    <tr>
                        <th><span contenteditable="">Discount({{$order->discount_code}})</span></th>
                    <td><span data-prefix="">$ <span contenteditable="">{{ number_format($order->discount_amount,2) }}</span></span></td>
                    </tr>
                @endif
                <tr>
                    <th><span contenteditable="">Shipping & Handling</span></th>
                    <td><span data-prefix="">$ <span contenteditable="">{{ number_format($order->shipping_cost_estimated,2) }}</span></span></td>
                </tr>
                <tr>
                    <th><span contenteditable="">Total Before Tax</span></th>
                    <td><span data-prefix="">$ <span contenteditable="">{{ number_format($order->subtotal-$order->discount_amount+$order->shipping_cost_estimated,2) }}</span></span></td>
                </tr>
                <tr>
                    <th><span contenteditable="">Taxes</span></th>
                    <td><span data-prefix="">$ <span contenteditable="">{{ number_format($order->total_tax_amount,2) }}</span></span></td>
                </tr>
                
                <tr>
                    <th><span contenteditable="">Order Total</span></th>
                    <td><span data-prefix="">$</span><span contenteditable="">{{ number_format($order->total_amount,2) }}</span></td>
                </tr>
                <?php /*
                <tr>
                    <th><span contenteditable="">Amount Paid</span></th>
                    <td><span data-prefix="">$</span><span contenteditable="">{{ number_format($order->paid_amount,2) }}</span></td>
                </tr>
                <tr>
                    <th><span contenteditable="">Balance Due</span></th>
                    <td><span data-prefix="">$</span><span> {{number_format(($order->total_amount - $order->paid_amount),2)}} </span></td>
                </tr>
                */?>
            </tbody>
        </table>
        @endif
    </article>
    <aside>
        @if (($order->partial == "1") && ( $order->paid_amount
        < $order->total_amount) && (!isset($packing)))
            <div id="partial_div" class="noprint" style="text-align:center;padding:20px;">
                Payment Amount:
                <input type="type" name="pay_amount" id="pay_amount" value="{{($order->total_amount) - $order->paid_amount}}"
                    class="form-control" style="border:1px solid #999;padding:3px;border-radius:3px;position:relative;top:-3px; margin-top: 10px">
            </div>
            @endif
            <div class="noprint" style="text-align:center;padding:20px;">
                @if (($order->total_amount) - $order->paid_amount > 0 && (!isset($packing)))
                    <a id="btn_pay" class="button blue" onClick="pay();" data-token="{{ $order->id }}" style="cursor:pointer;">Pay with Paypal</a>
                    <a id="btn_card" class="button blue" data-toggle="modal" data-target="#CardPaymentModal" data-token="{{ $order->order_number }}" style="cursor:pointer;">Pay with Card</a>
                @endif
                    <a id="btn_print" class="button blue" onClick="window.print();" style="cursor:pointer;">Print</a>
                    {{-- <a id="btn_email" class="button blue" onClick="send_invoice();" data-token="{{ $order->order_number }}">Email</a> --}}
            </div>
            <h1><span contenteditable="" style="padding-bottom: 10px">Additional Notes</span></h1>
            <div style="margin-top: 10px">
                <p>@if ($order->discount_amount > 0) <strong>Discount code:</strong> {{$order->discount_code}}
                </p>
                <p>@elseif ($order->notes == "") The quote is valid for 30 days. @else {{$order->notes}} @endif
                </p>
            </div>

    </aside>

    <!-- Modal -->
    <div class="modal fade" id="CardPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="panel panel-default credit-card-box">
                        <div class="panel-body">
                            <form role="form" id="payment_form" method="POST" action="javascript:void(0);">
                                    <div class="row">
                                        <div class="col-xs-5 col-md-5">
                                            <label>ACCEPTED CREDIT CARDS</label>
                                        </div>
                                        <div class="col-xs-5 col-md-5 ">
                                            <img class="img-responsive pull-right" src="/images/accepted-cards.png" style="width: 100%">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px">
                                        <div class="col-xs-5 col-md-5 ">
                                            {{-- <div class="form-group"> --}}
                                                <label for="card_cvc">FIRSTNAME</label>
                                                <input type="text" class="form-control" id="card_firstname" name="card_firstname" placeholder="Firstname on card" autocomplete="card_firstname"
                                                    required />
                                            {{-- </div> --}}
                                        </div>
                                        <div class="col-xs-5 col-md-5">
                                            {{-- <div class="form-group"> --}}
                                                <label for="card_lastname"><span class="hidden-xs">LASTNAME</label>
                                                <input type="text" class="form-control" id="card_lastname" name="card_lastname" placeholder="Lastname on card" autocomplete="card_lastname"
                                                    required />
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5 col-md-5">
                                            <div class="form-group">
                                                <label for="card_number">CARD NUMBER</label>
                                                <input type="tel" class="form-control" id="card_number" name="card_number" placeholder="Valid Card Number" autocomplete="cc-number"
                                                    required autofocus />
                                                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-5 col-md-5 ">
                                            <div class="form-group">
                                                <label for="card_cvc">CV CODE</label>
                                                <input 
                                                    type="tel" 
                                                    class="form-control"
                                                    id="card_cvc"
                                                    name="card_cvc"
                                                    placeholder="CVC"
                                                    autocomplete="cc-csc"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-5 col-md-5">
                                            <div class="form-group">
                                                <label for="card_exp_month"><span class="hidden-xs">EXPIRE MONTH</label>
                                                <select type="tel" class="form-control" id="card_exp_month" name="card_exp_month" placeholder="MM" autocomplete="cc-exp"
                                                    required />
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-5 col-md-5 ">
                                            <div class="form-group">
                                                <label for="card_exp_year">EXPIRE YEAR</label>
                                                <select type="tel" class="form-control" id="card_exp_year" name="card_exp_year" placeholder="YYYY" autocomplete="cc-csc"
                                                    required />
                                                <option value="18">2018</option>
                                                <option value="19">2019</option>
                                                <option value="20">2020</option>
                                                <option value="21">2021</option>
                                                <option value="22">2022</option>
                                                <option value="23">2023</option>
                                                <option value="24">2024</option>
                                                <option value="25">2025</option>
                                                <option value="26">2026</option>
                                                <option value="27">2027</option>
                                                <select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-xs-11 col-md-11">
                                        <div class="form-group">
                                            <label for="billing_address"><span class="hidden-xs">Billing Address</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="billing_address"
                                                name="billing_address"
                                                placeholder="Billing Address"
                                                autocomplete="billing_address"
                                                required 
                                            />
                                        </div> 
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-xs-5 col-md-5">
                                        <div class="form-group">
                                            <label for="card_zip"><span class="hidden-xs">ZIP CODE</label>
                                            <input 
                                                type="tel" 
                                                class="form-control" 
                                                id="card_zip"
                                                name="card_zip"
                                                placeholder="00000"
                                                autocomplete="card_zip"
                                                required 
                                            />
                                        </div> 
                                    </div>
                                    <div class="col-xs-5 col-md-5 ">
                                        <div class="form-group">
                                            <label for="card_exp_year">ACCOUNT TYPE</label>
                                            <select type="tel" class="form-control" id="card_exp_year" name="card_exp_year" placeholder="YYYY" autocomplete="cc-csc"
                                                required />
                                            <option value="individual">Personal</option>
                                            <option value="business">Business</option>
                                            <select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5 col-md-5"><button class="subscribe btn btn-success btn-lg btn-block" type="button" onClick="charge_card();" style="width: 90%">Submit Payment</button></div>
                                    <div class="col-xs-5 col-md-5 col-sm-offset-1"><button class="btn btn-info btn-lg btn-block" type="button" data-dismiss="modal" style="width: 90%">Cancel</button></div>
                                </div>
                                <div class="row" style="display:none;">
                                    <div class="col-xs-12">
                                        <p class="payment-errors"></p>
                                    </div>
                                </div>
						</form>
					</div>
				</div>            
				<!-- CREDIT CARD FORM ENDS HERE -->
		  </div>
		</div>
	  </div>
	</div>
<div id="spinner" style="z-index:999999;position: fixed; width: 100vw; height: 100vh; left: 0; top: 0;background-color: rgba(0, 0, 0, .8);display:none;">
    <div class="loader" style="margin:auto;width: 100px; height: 100px;margin-top:150px;"></div>
</div>

		
<script src="/be/vendor/jquery/jquery.min.js"></script>
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
<script src="/be/assets/js/invoice.js"></script>		
<script src="/be/assets/vendor/bootstrap/bootstrap.js"></script>
@if(strtolower(env('CARD_PROCESSOR')) == 'authorize')
    @if(strtolower(env('AUTHORIZE_ENV')) == 'production')
        <script type="text/javascript"  src="https://js.authorize.net/v1/Accept.js" charset="utf-8"></script>
    @else
        <script type="text/javascript"  src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
    @endif
@elseif(strtolower(env('CARD_PROCESSOR')) == 'stripe')
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endif
<script>

let client_key = {!! json_encode($client_key) !!};
let api_key = {!! json_encode($api_key) !!};
let card_processor = {!! json_encode($card_processor) !!};
let payment = payments.driver(card_processor);

var environment = {
		"api_url" : "http://api.properos.com"
	};

	function getJson (object){
	   if(typeof object == "object"){
		   return object;
	   }
	   try {
		   return JSON.parse(object);
	   } catch (e) {
		   return false;
	   }
	}
	
	function pay() {
        var process_status = {"status":1}; 
		var invoice = {};
		if (process_status.status == 1) {
                $("#spinner").css("display","block");
                let data = {
                    _token : $('#_token').val(),
                    type : 'paypal',
                    order_id : $('#btn_pay').data('token'),
              }
              if ($("#partial_div").length > 0) {
                     data.partial_amount = $('#pay_amount').val();
                }
               var trysending = $.ajax({
			   url: '/api/guest/orders/process-invoice-payment',
			   type: "POST",
			   data: data,
			   cache: false
				})
			   .done(function(response) {
						$("#spinner").css("display","none");
						var myres = getJson(response); 
						if (myres != false) {
							if (myres.status == "1") {
                                var form = $('<form />', {
                                action: response.data['info']['url'],
                                method: 'POST',
                                style: 'display: none;'
                                });
                                form.append(response.data['info']['pform']);
                                form.appendTo('body').submit();
							} else {
                                var errors = '';
                                for (var i in response.errors) {
                                    errors += response.errors[i] + '.';
                                }
                                swal('Error!', errors, 'error');
                                }
						} else {
							swal("Hey!","There was a connection error, please try again or contact us.", "error");
						}

				})
				.fail(function( response ) {
					$("#spinner").css("display","none");
					swal("Hey!","There was a connection error, please try again or contact us.", "error");
				});
		  
		}
		 else {
			swal("Hey!",process_status.errors[0], "error")	
		}
	}
	
	function charge_card() {
       
		var process_status = {"status":1}; 
		var invoice = {};
		// if (process_status.status == 1) {
		   //if (/^[1-9]{1}[0-9]{9}$/.test($("#billing_phone").val()) == false) {
		   //   process_status = {"status":0,"errors":["Invalid phone number"]};
		   //} else if (/\S+@\S+\.\S+/.test($("#email").val()) == false) {
		   //   process_status = {"status":0,"errors":["Invalid email format"]};
		   //} else 
        if ($("#card_firstname").val().length < 1) {
            process_status = {"status":0,"errors":["Please write your firstname"]};
        } else if ($("#card_cvc").val().length < 2) {
            process_status = {"status":0,"errors":["Invalid Card Code"]};
        } else if (/^[0-9]{13,19}$/.test($('#card_number').val()) == false) {
            process_status = {"status":0,"errors":["Invalid card number"]};
        } else if (['01','02','03','04','05','06','07','08','09','10','11','12'].indexOf($('#card_exp_month').val()) == -1){
            process_status = {"status":0,"errors":["Invalid month number"]};
        } else if (($('#exp_year').val() < 2017) || ($('#card_exp_year').val() > 2028)){
            process_status = {"status":0,"errors":["Invalid year number"]};
        } else if (/^[0-9]{5}$/.test($('#card_zip').val()) == false) {
            process_status = {"status":0,"errors":["Incorrect zip code"]};
        }
        
        if (process_status.status == 1) {
            if (card_processor.toLowerCase() == 'stripe') {
                Stripe.setPublishableKey(client_key);
            }
            invoice = {
                //"phone"  : $("#billing_phone").val(),
                //"email"  : $("#email").val(),
                billing_firstname  : $("#card_firstname").val(),
                billing_lastname  : $("#card_lastname").val(),
                // billing_address  : $("#billing_address").val(),
                card_number  : $("#card_number").val(),
                exp_month  : $("#card_exp_month").val(),
                exp_year  : $("#card_exp_year").val(),
                cvv  : $("#card_cvc").val(),
                billing_zip  : $("#card_zip").val(),
                clientKey : client_key,
                apiLoginID : api_key
                
            };
            
            $("#spinner").css("display","block");
            payment.card.createCardToken(invoice, function(response){
                if(response.status > 0){
                    let data = {
                        billing_firstname: invoice.billing_firstname,
                        billing_lastname: invoice.billing_lastname,
                        exp_month : invoice.exp_month,
                        billing_zip : invoice.billing_zip,
                        // billing_address : invoice.billing_address,
                        _token : $('#_token').val(),
                        exp_year : invoice.exp_year,
                        customer_type : 'individual',
                        token : response.data.token,
                        type : 'card',
                        order_id : $('#btn_pay').data('token'),
                        card_processor : card_processor.toLowerCase()
                    }
                    if ($("#partial_div").length > 0) {
                        data.partial_amount = $('#pay_amount').val();
                    }
                    if (typeof response.data.description != 'undefined') {
                        data.description = response.data.description;
                    }
                    var trysending = $.ajax({
                        url: '/api/guest/orders/process-invoice-payment',
                        type: "POST",
                        data: data,
                        cache: false
                    })
                    .done(function( response ) {
                        $("#spinner").css("display","none");
                        var myres = getJson(response); 
                        if (myres != false) {
                            console.log(myres);
                            if (myres.status == "1") {
                            swal({
                                title: "Success",
                                text: myres.message,
                                icon: "success",
                                }).then((confirm) => {
                                    $('#CardPaymentModal').modal('hide');
                                    if (confirm) {
                                        location.reload();
                                    }
                                });
                            } else {
                                $("#spinner").css("display","none");
                                var errors = '';
                                for (var i in response.errors) {
                                    errors += response.errors[i] + '.';
                                }
                                swal('Error!', errors, 'error');
                                }
                        } else {
                            swal("Hey!","There was a connection error, please try again or contact us.", "error");
                        }
                    })
                    .fail(function( response ) {
                        $("#spinner").css("display","none");
                        swal("Hey!","There was a connection error, please try again or contact us.", "error");
                    });
                }else{
                    $("#spinner").css("display","none");
                    var errors = '';
                    for (var i in response.errors) {
                        errors += response.errors[i].text + '.';
                    }
                    swal('Error!', errors, 'error');
                }
            });
                /* invoice['invoice_token'] = $('#btn_pay').data('token');	 */
		}else {
			swal("Hey!",process_status.errors[0], "error")	
		}
    }
    
	function send_invoice() {
		var invoice = {};
		invoice['_token'] = $('#_token').val();
		invoice['invoice_token'] = $('#btn_email').data('token');
		invoice['email'] = $('#bill_email').text();
		var trysending = $.ajax({
		   url: '/invoice/send',
		   type: "POST",
		   data: invoice,
		   cache: false
		   })
		   .done(function( response ) {
				//myobj = JSON.parse(response);	
				//if (myobj['status']==1)	{
				$("#btn_email").css("display", "none");
				//}
		});
		
	}
	
</script>	
</body></html>