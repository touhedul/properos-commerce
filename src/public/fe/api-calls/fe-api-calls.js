function paintCart() {
	var trysending = $.ajax({
		url: '/api/cart/get',
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				$('.cart_count').text(myres.data.count);
				var mystr = "";
				var cnt = 0;
				var total = 0;
				if (myres.data.count > 0) {
					mystr = '<ul class="colorize-bd" stype="padding-bottom:0px;">';
					$.each(myres.data.cart, function (key, value) {
						if (value.discount_percent) {
							total += (value.price - ((value.price * value.discount_percent) / 100)) * value.qty;
						} else {
							total += value.price * value.qty;
						}
						/* total += value.price * value.qty; */
						mystr += '<li><div><a href="/items/' + value.sku + '"><img id="thumb' + key + '" alt="image" class="colorize-theme2-bd colorize-theme-bd-h"></a></div>';
						mystr += '<div><p><a href="/items/' + value.sku + '">' + value.name + '</a>';
						if(value.options && !Array.isArray(value.options)){
							mystr += '<br><span style="text-transform:capitalize;font-size:12px;">(';
							var count = 0;
							for(var i in value.options){
								if(count > 0){
									mystr += ', ';
								}
								mystr += i+':'+value.options[i];
								count++;
							}
							mystr += ')</span>';
						}
						mystr += '</p>';
						mystr += '<span class="tt-header__cart-price"><span class="tt-header__cart-price-count">' + value.qty + '</span>';
						if (value.discount_percent) {
							mystr += '<span>x</span><span class="tt-header__cart-price-val tt-price">$' + parseFloat((value.price - ((value.price * value.discount_percent) / 100))).toFixed(2) + '</span></span>';
						} else {
							mystr += '<span>x</span><span class="tt-header__cart-price-val tt-price">$' + value.price + '</span></span>';
						}
						/* mystr += '<span>x</span><span class="tt-header__cart-price-val tt-price">$' + value.price + '</span></span>'; */
						mystr += '<div class="tt-counter" data-min="0" data-max="99">';
						mystr += '<form action="#"><input id="qty-input' + value.id + '" class="form-control qty-input" data-item_id="' + value.id + '" type="text" value="' + value.qty + '"></form>';
						mystr += '<div class="tt-counter__control">';
						mystr += '<span class="icon-up-circle increasecart" data-direction="next" data-item_id="' + value.id + '"></span>';
						mystr += '<span class="icon-down-circle decreasecart" data-item_id="' + value.id + '" data-direction="prev"></span></div></div></div>';
						mystr += '<div><a href="#" class="tt-header__cart-edit"></a>';
						mystr += '<a href="#" class="tt-header__cart-delete"  data-item_id="' + value.id + '" title="remove"><i class="icon-trash"></i></a></div>';
						mystr += '</div></li><hr>';
					});
					mystr += '</ul>';
					mystr += '<div class="tt-header__cart-footer"><span class="tt-header__cart-subtotal colorize-head-c">';
					mystr += 'Subtotal: <span class="colorize-theme-c" style="color: dimgray !important">$' + total.toFixed(2) + '</span></span><div class="row"><div class="col-lg-12">';
					mystr += '<a href="/cart" class="tt-header__cart-viewcart btn"><i class="icon-shop24"></i> View Cart</a></div>';
					/* mystr += '<div class="col-lg-6"><a href="/cart/checkout" class="tt-header__cart-checkout btn colorize-btn2"><i class="icon-check"></i> Checkout</a></div>'; */
					mystr += '</div></div>';
				}
				if (myres.data.count == 0) {
					mystr += '<div class="tt-header__cart-footer"><span class="tt-header__cart-subtotal colorize-head-c text-center">Your cart is empty </span><div class="row">';
					mystr += '<div class="col-lg-12"><a href="/items" class="tt-header__cart-viewcart btn"><i class="icon-shop24"></i> Continue Shopping</a></div>';
					mystr += '</div></div>';
				}
				$('#header_cart').html(mystr);

			} else {
				$('.cart_count').text(0);
			}

			$.each(myres.data.cart, function (key, value) {
				if(value.thumb_path != null){
					$('#thumb' + key).attr('src', '/storage/' + value.thumb_path);
				}else{
					$('#thumb' + key).attr('src', '/images/placeholder/item-placeholder.jpg');
				}
				
			});
		});
}
function getCart() {
	var trysending = $.ajax({
		url: '/api/cart/get',
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				$('.cart_count').text(myres.data.count);
			} else {
				$('.cart_count').text(0);
			}
		});
}
function setCart(item_id, qty) {
	var trysending = $.ajax({
		url: '/api/cart/set/' + item_id + '/' + qty,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				paintCart();
				highlightCart();
			} else {

			}
		});
}
function changeQty(item_id, qty) {
	var trysending = $.ajax({
		url: '/api/cart/changeQty/' + item_id + '/' + qty,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				paintCart();
			} else{
				$('.increasecart').css('pointer-events', 'none');
				paintCart();
			}
		});
}
function decreaseCart(item_id, qty) {
	var trysending = $.ajax({
		url: '/api/cart/decrease/' + item_id,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				paintCart();
			} else {
				//
			}
		});
}
function removeCart(item_id) {
	var trysending = $.ajax({
		url: '/api/cart/remove/' + item_id,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				paintCart();
			} else {
				//
			}
		});
}
function removeAllCart() {
	var trysending = $.ajax({
		url: '/api/cart/removeall',
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				paintCart();
			} else {
				//
			}
		});
}
function getWishlist() {
	var trysending = $.ajax({
		url: '/api/wishlist/get',
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				$('#wishlist_count').text(myres.data.count);
			} else {
				$('#wishlist_count').text(0);
			}
		});
}
function setWishlist(item_id) {
	var trysending = $.ajax({
		url: '/api/wishlist/set/' + item_id,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				getWishlist();
				highlightWishList();
			} else {
				//
			}
		});
}
function removeWishlist(item_id) {
	var trysending = $.ajax({
		url: '/api/wishlist/remove/' + item_id,
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				getWishlist();
				if(myres.data.wishlist.length == 0){
					window.location.href = '/wishlist';
				}else{
					$('#item_' + item_id).remove();
				}
			} else {
				//
			}
		});
}
function removeAllWishlist() {
	var trysending = $.ajax({
		url: '/api/wishlist/removeall',
		type: "GET",
		cache: false
	})
		.done(function (response) {
			var myres = JSON.parse(response);
			if (myres.status == "1") {
				getWishlist();
				window.location.href = '/wishlist';
			} else {
				//
			}
		});
}

function highlightCart() {
	var a;
	a = document.getElementById("cart_count");
	a.style.setProperty('color', '#fff', 'important');
	a.style.backgroundColor = "#13919b";
	setTimeout(function () {
		a.style.color = "#13919b";
		a.style.backgroundColor = "#fff";
	}, 1000);
}

function highlightWishList() {
	var a;
	a = document.getElementById("wishlist_count");
	a.style.setProperty('color', '#fff', 'important');
	a.style.backgroundColor = "#13919b";
	setTimeout(function () {
		a.style.color = "#13919b";
		a.style.backgroundColor = "#fff";
	}, 1000);
}

paintCart();
getWishlist();