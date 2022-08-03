
$(document).on('click', '.addtocart', function () {
	setCart($(this).data('item_id'), 1);
});
$(document).on('click', '.increasecart', function () {
	changeQty($(this).data('item_id'), parseInt($('#qty-input' + $(this).data('item_id')).val()) + 1);
});
$(document).on('click', '.decreasecart', function () {
	changeQty($(this).data('item_id'), parseInt($('#qty-input' + $(this).data('item_id')).val()) - 1);
});

$(document).on('focusout', '.qty-input', function () {
	changeQty($(this).data('item_id'), parseInt($(this).val()));
});

$(document).on('keydown', '.qty-input', function (evt) {
	if (evt.keyCode == '13') {
		console.log(evt);
		changeQty($(this).data('item_id'), parseInt($(this).val()));
	}
});

$(document).on('click', '.tt-header__cart-delete', function (evt) {
	removeCart($(this).data('item_id'));
});

$(document).on('click', '.removefromcart', function () {
	removeCart($(this).data('item_id'));
	$('#item_' + $(this).data('item_id')).remove();
});
$(document).on('click', '.removeallfromcart', function () {
	removeAllCart();
});
$(document).on('click', '.addtolist', function () {
	setWishlist($(this).data('item_id'));
});
$(document).on('click', '.removefromlist', function () {
	removeWishlist($(this).data('item_id'));
	// $('#item_' + $(this).data('item_id')).remove();
});
$(document).on('click', '.removeallfromlist', function () {
	removeAllWishlist();
});

