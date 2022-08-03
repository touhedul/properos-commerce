@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<style>
	@media only screen and (min-width: 768px){
		.tt-wishlist__product_image {
			width: 130px;
			min-width: 130px;
			margin-right: 40px;
		}
	}
	@media (min-width: 1400px){
		.container {
			width: 1070px;
			max-width: 100%;
		}
	}

	@media (min-width: 480px){
		.container {
			width: 550px;
			max-width: 100%;
		}
	}
</style>
@endsection
 
@section('content')
<div class="tt-layout tt-sticky-block__parent " style="min-height:50vh;">
	<div class="tt-layout__content">
		<div class="container">
			<div class="tt-wishlist">
				@if($content["wishlist"]["count"]==0)
				<div class="tt-empty">
					<i class="tt-empty__icon"><img src="/images/empty-wishlist.svg" alt="Image name"></i>
					<div class="tt-page__name text-center">
						<h1>WishList is Empty</h1>
						<p>No products were added to the wishlist</p>
					</div>
					<div class="tt-empty__btn">
						<a href="/items" class="btn">Continue Shopping</a>
					</div>
				</div>
				@else
				<div class="text-center">
					<h3>Wishlist</h3>
				</div>
				@foreach($content["wishlist"]["wishlist"] as $key => $wishlist_item)
				<div class="tt-wishlist__product" id="item_{{$key}}"  style="border-bottom: 1px solid #ececec;padding-bottom: 15px;">
					<div class="row">
						<div class="col-xl-5 col-lg-6 col-md-12">
							<div class="tt-wishlist__product_image">
								@if(array_key_exists('thumb_path', $wishlist_item) && $wishlist_item["thumb_path"] != null)
								<a href="/items/{{$wishlist_item["sku"]}}"><img src="/storage/{{$wishlist_item["thumb_path"]}}" alt="Image name"></a>
								@else
								<a href="/items/{{$wishlist_item["sku"]}}"><img src="/images/item-placeholder.jpg" alt="Image name"></a>
								@endif
							</div>
							<div class="tt-wishlist__product_info">
								<a href="/items/{{$wishlist_item["sku"]}}">
									<p>{{$wishlist_item["name"]}}</p>
								</a>
							</div>
						</div>
						<div class="col-xl-5 col-lg-3 col-sm-6">
							<div class="tt-wishlist__product_price">
								<div class="tt-price tt-price--sale">
									@if($wishlist_item["discount_percent"] && $wishlist_item["discount_percent"] > 0)
										<span style="color:#000;">$ {{number_format(($wishlist_item["price"] - $wishlist_item["price"]*$wishlist_item['discount_percent']/100),2)}}</span><span style="color:#fc2a2e;">$ {{$wishlist_item["price"]}}</span>
									@else
									<span style="color:#000;">$ {{$wishlist_item["price"]}}</span>@if($wishlist_item["price"]
									<$wishlist_item[ "msrp"])<span>$ {{$wishlist_item["msrp"]}}</span>@endif
									@endif
								</div>
								@if($wishlist_item["discount_percent"] && $wishlist_item["discount_percent"] > 0)
								<p><span class="tt-label__sale">{{$wishlist_item["discount_percent"]}}% off</span></p>
								@endif
							</div>
							<div class="tt-wishlist__product_status"><span class="colorize-success-c">In Stock</span> 
								@if(false)
								<div class="tt-wishlist__product_status"><span class="colorize-error-c">Out of Stock</span> @endif
								</div>
							</div>
							<div class="col-xl-2 col-lg-3 col-sm-6">
								<div class="tt-wishlist__product_to-cart addtocart" data-item_id="{{$key}}">
									<a href="#" class="tt-btn tt-btn--cart colorize-btn6">
											<i class="icon-shop24"></i>
											<span>Add to Cart</span>
										</a>
								</div>
								<div class="tt-wishlist__product_del removefromlist" data-item_id="{{$key}}" style="cursor:pointer;margin-left:8px;"><i class="icon-trash"></i></div>
							</div>
						</div>
					</div>
					@endforeach @endif
				</div>
			</div>
		</div>
	</div>
@endsection
 
@section('footer_specific')
@endsection
 
@section('local_script')
@endsection