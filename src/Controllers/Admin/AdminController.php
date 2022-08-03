<?php

namespace Properos\Commerce\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Properos\Users\Models\User;
use Properos\Base\Classes\Theme; 
use Carbon\Carbon;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Models\OrderItem;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Category;
use Properos\Commerce\Models\PaymentMethod;
use Properos\Commerce\Models\ShippingMethod;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Models\File;
use Properos\Commerce\Classes\CFile;
use Properos\Commerce\Models\Plan;
use Properos\Commerce\Models\StoreLocation;
use Properos\Commerce\Models\Tax;
use Properos\Commerce\Models\Payment;
use Properos\Commerce\Models\Subscription;
use App\Models\Lead;
use App\Models\Event;
use Properos\Commerce\Models\Collection;
use Properos\Commerce\Models\Setting;

class AdminController extends Controller {

    private $item;
    private $category;
    private $order;
    private $paymentMethod;
    private $cFile;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Category $category,
        Item $item,
        Order $order,
        User $user,
        CFile $cFile
    ) {
        $this->middleware(['role:admin|sales']);

        $this->item = $item;
        $this->category = $category;
        $this->order = $order;
        $this->user = $user;
        $this->cFile = $cFile;
    }

	public function index()
    {
        $totalProfit = 0;
        $recent_orders_result = [];
        $recent_buyers = [];

        $totalItems = count($this->item->all());
        $totalCustomers = count($this->user->role('customer')->get());
        $recent_orders_db = $this->order->with('customer')->with('orderItems.item.category')->where('status','<>', 'cart')
            /* ->where('created_at', '>', Carbon::now()->subDays(3)) */
            ->orderBy('created_at', 'desc')
            ->paginate(4);
        $paid_orders = $this->order->where([['status', 'paid']])->get();

        if (count($paid_orders) > 0) {
            foreach ($paid_orders as $key => $order) {
                $totalProfit += $order->paid_amount;
            }
        }
        if (count($recent_orders_db->items()) > 0) {
            foreach ($recent_orders_db->items() as $key => $recent_order) {
                if ($recent_order->status == 'paid') {
                    if ($recent_order->user_id > 0) {
                        $buyer = $this->user->find($recent_order->user_id);
                    } else {
                        $buyer = $recent_order->customer_name;
                    }
                    if ($buyer) {
                        $recent_buyers[$key]['user'] = $buyer;
                        $recent_buyers[$key]['order'] = $recent_order;
                    }
                }
            }
        }

        $monthly_sales = array();

        $monthly_orders = $this->order::with('orderItems.item')
            ->whereBetween('created_at', [Carbon::parse('first day of January'), Carbon::now()])
            ->where('status', '=', 'paid')
            ->get();

        if (count($monthly_orders) > 0) {
            $monthly_sales['annual'] = 0;
            foreach ($monthly_orders as $key => $monthly_order) {
                $dt = Carbon::parse($monthly_order->created_at);
                foreach ($monthly_order->orderItems as $key => $order_item) {
                    $sale_data = new \stdClass();
                    $sale_data->month = $dt->month;
                    $sale_data->year = $dt->year;
                    if ($sale_data->month >= 1 && $sale_data->month <= 9) {
                        $sale_data->month = '0' . $dt->month;
                    }
                    if(isset($order_item->item)){
                        $sale_data->short_name = $order_item->item->short_name;
                        $montly_sales[$sale_data->month][$order_item->item->short_name][] = $sale_data;
                    }else{
                        $sale_data->short_name = '';
                        $montly_sales[$sale_data->month][$order_item->id][] = $sale_data;
                    }
                    $sale_data->qty = $order_item->qty;
                    $sale_data->price = $order_item->price;
                    $monthly_sales['year'] = $sale_data->year;
                }
                if(isset($monthly_sales[$sale_data->month]['total'])){
                    $monthly_sales[$sale_data->month]['total'] = $monthly_sales[$sale_data->month]['total'] + $monthly_order->paid_amount;
                }else{
                    $monthly_sales[$sale_data->month]['total'] = $monthly_order->paid_amount;
                }
                // $monthly_sales[$sale_data->month]['total'] = $monthly_sales[$sale_data->month]['total'] + $monthly_order->total_tax_amount;
            }
            $monthly_sales['annual'] = 0;
            foreach($monthly_sales as $v){
                if(isset($v['total'])){
                    $monthly_sales['annual'] = $monthly_sales['annual'] + $v['total'];
                }
            }
        }

        $active_categories = $this->category->where('active', 1)->get();

        $popular_products = OrderItem::select(\DB::raw('sum(qty) as count, item_id, name'))->whereHas('order', function($query){
            $query->where('status','=','paid')
                ->where('created_at','>=',date("Y-m-d H:i:s", strtotime( date('Y-m-d H:i:s')." -6 months")));
        })->with('item')->groupBy('item_id', 'name')->orderByRaw('count DESC')->limit(10)->get();
        
        $new_orders = $this->order->where('shipping_status', 'pending')->where('status','<>', 'cart')->count();

        // dd($new_orders);
        return view('be.index')->with([
            'totalItems' => $totalItems > 0 ? $totalItems : 0,
            'totalCustomers' => $totalCustomers > 0 ? $totalCustomers : 0,
            'recent_orders' => count($recent_orders_db->items() > 0) ? $recent_orders_db->toJson() : null,
            'totalProfit' => $totalProfit > 0 ? $totalProfit : 0.00,
            'recent_buyers' => count($recent_buyers) > 0 ? $recent_buyers : [],
            'monthly_sales' => count($monthly_sales) > 0 ? $monthly_sales : [],
            'monthly_orders' => count($monthly_orders),
            'active_categories' => count($active_categories),
            'popular_products' => count($popular_products) > 0 ? $popular_products : [],
            'new_orders' =>$new_orders, 
    
        ]);
        
	}
	
	public function restore()
    {
        $order = Order::withTrashed()->find(1);
        $order->restore();
    }

    //Items
    public function items()
    {
        $items = $this->item->with('marketplaceItems')->with(['files' => function ($q) {
            $q->where('files.type', '=', 'image')->where('files.owner_type', '=', 'item');
        }])->paginate(10)->toJson();
        return view('be.commerce.items')->with(['items' => $items]);
    }

    public function showItem($id)
    {

    }

    public function createItem()
    {
        $categories = $this->category->get();
        $active_marketplaces = [];
        if (config('app.active_amazon') == true) {
            $active_marketplaces[] = 'amazon';
        }
        if (config('app.active_ebay') == true) {
            $active_marketplaces[] = 'ebay';
        }
        $collections = Collection::get();
        return view('be.commerce.add-item')->with(['categories' => $categories,'collections' => $collections, 'active_marketplaces' => $active_marketplaces])
                                                            ;
    }

    public function editItem($id)
    {
        if ($id > 0) {
            $item = $this->item->with(['files' => function ($q) {
                return $q->where('type', 'image')->where('files.owner_type', '=', 'item');
            },'collections'])->with('marketplaceItems')->find($id);
            if ($item) {
                $active_marketplaces = [];
                if (config('app.active_amazon') == true) {
                    $active_marketplaces[] = 'amazon';
                }
                if (config('app.active_ebay') == true) {
                    $active_marketplaces[] = 'ebay';
                }
                $categories = $this->category->get();
                $collections = Collection::get();
                return view('be.commerce.add-item')->with(['categories' => $categories,'collections' => $collections, 'editable_item' => $item, 'active_marketplaces' => $active_marketplaces])
                                                                            ;
            }
        }
        return view('be.commerce.add-item');
    }

    //Reports
    public function reports()
    {
        return view('be.reports.index');
    }

    public function reportSales()
    {
        return view('be.reports.sales');
    }

    public function reportCustomers()
    {
        return view('be.reports.customers');
    }
    //newsletter subscribers
    public function reportSubscribers()
    {
        return view('be.reports.subscribers');
    }
    //activities log
    public function reportActivitiesLog()
    {
        return view('be.reports.activities-log');
    }
    //products sold
    public function reportProductsSold()
    {
        return view('be.reports.products-sold');
    }

    //Categories
    public function categories()
    {
        $categories = $this->category->paginate(10)->toJson();
        return view('be.commerce.categories')->with(['categories' => $categories]);
    }

    public function createCategory()
    {
        return view('be.commerce.add-category');
    }

    public function editCategory($id)
    {
        if ($id > 0) {
            $category = $this->category->with(['files' => function ($q) {
                $q->where('files.type', '=', 'image')->where('files.owner_type', '=', 'category');
            }])->find($id);
            if ($category) {
                return view('be.commerce.add-category')->with(['editable_category' => $category]);
            }
        }
        return view('be.commerce.add-category');
    }

    //Orders
    public function orders(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        // $orders = $this->order->with('customer')->with('paymentMethod')->with('orderItems')->orderBy('created_at', 'desc')->paginate(10)->toJson();
        $payment_methods = $this->paymentMethod->all();
        // $result['orders'] = isset($orders) ? $orders : [];
        $result['payment_methods'] = count($payment_methods) > 0 ? $payment_methods : [];
        
        return view('be.commerce.orders')->with(['result' => $result]);
    }

    public function createOrder(PaymentMethod $paymentMethod, ShippingMethod $shippingMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->shippingMethod = $shippingMethod;

        $payment_methods = $this->paymentMethod->all();
        $shipping_methods = $this->shippingMethod->all();
        $products = $this->item->with(['files' => function ($q) {
            return $q->where('type', 'image')->where('files.owner_type', '=', 'item');
        }])->get();
        $categories = $this->category->get();
        // $customers = User::role('customer')->get();
        
        return view('be.commerce.add-order')
            ->with([
                'payment_methods' => $payment_methods,
                'shipping_methods' => $shipping_methods,
                'items' => $products,
                'categories' => $categories,
                'stores' => StoreLocation::first(),
                'integration' => config('shipping')
            ]);
    }
    public function editOrder($id, PaymentMethod $paymentMethod, ShippingMethod $shippingMethod)
    {
        if ($id > 0) {
            $editable_order = $this->order->with(['orderItems.item.files' => function ($q) {
                return $q->where('type', '=', 'image')->where('files.owner_type', '=', 'item');
            }])->with('shippingMethod')->with('customer.addresses')->find($id);
            if ($editable_order) {
                $this->paymentMethod = $paymentMethod;
                $this->shippingMethod = $shippingMethod; 

                $payment_methods = $this->paymentMethod->all();
                $shipping_methods = $this->shippingMethod->all();
                
                // $customers = User::get();
                $categories = $this->category->get();
                $products = $this->item->with(['files' => function ($q) {
                    return $q->where('type', '=', 'image')->where('files.owner_type', '=', 'item');
                }])->get();
                
                $discount = Discount::where('code',$editable_order['discount_code'])->first();
                
                return view('be.commerce.add-order')->with([
                    'editable_order' => $editable_order,
                    'payment_methods' => $payment_methods,
                    'shipping_methods' => $shipping_methods,
                    'items' => $products,
                    'discount' => $discount,
                    'categories' => $categories,
                    'stores' => ($editable_order['store_id']>0) ? StoreLocation::where('id',$editable_order['store_id'])->first() : StoreLocation::first(),
                    'integration' => config('shipping')
                ]);
            }
        }
        return view('be.commerce.add-order')->with(['integration' => config('shipping')]);
    }

    public function printOrderLabel($order_id)
    {
        if ($order_id > 0) {
            $result = File::where('owner_id', $order_id)->where('owner_type', 'order')->get();
            if (count($result) > 0) {
                return view('be.commerce.order-print-label')->with('labels', $result);
            }
        }
        abort('404');
    }

    //Discounts
    public function discounts()
    {
        $discounts = Discount::paginate(10)->toJson();; 
        return view('be.commerce.discounts')->with(['discounts' => $discounts]);
    }

    public function createDiscount()
    {
        return view('be.commerce.add-discount');
    }

    public function editDiscount($id)
    {
        if ($id > 0) {
            $editable_discount = Discount::with('applicables.applicable')->with('customers.customers')->with('locations')->find($id);
            if ($editable_discount) {
                return view('be.commerce.add-discount')->with(['editable_discount'=> $editable_discount]);
            }
        }
        return view('be.commerce.add-discount');
     }

     //Settings
    public function settings()
    {
        return view('be.settings.settings');
    }

    //Plans
    public function plans()
    {
        if (Auth::check()) {
            return view('be.commerce.plans');
        }
    }

    public function createPlan()
    {
        return view('be.commerce.add-plan');
    }

    public function editPlan($id)
    {
        if ($id > 0) {
            $plan = Plan::where('id',$id)->with('plan_items')->with('discount')->first();
            if ($plan) {
                return view('be.commerce.add-plan')->with(['editable_plan' => $plan]);
            }
        }
        return view('404');
    }

    //Subscriptions
    public function subscriptions()
    {
        if (Auth::check()) {
            return view('be.commerce.subscriptions');
        }
    }

    public function createSubscription()
    {
        $settings = Setting::where('key', 'invoicing')->first();
        return view('be.commerce.add-subscription')->with(['settings' => (isset($settings)) ? json_decode($settings->data) : null]);
    }

    public function editSubscription($id)
    {
        if ($id > 0) {
            $subscription = Subscription::where('id',$id)->with('subscription_items.item','user.customerProfile.paymentProfiles')->first();
            if ($subscription) {
                $settings = Setting::where('key', 'invoicing')->first();
                return view('be.commerce.add-subscription')->with([
                    'editable_subscription' => $subscription,
                    'settings' => (isset($settings)) ? json_decode($settings->data) : null
                ]);
            }
        }
        return view('404');
    }

    public function paymentHistory($id){
        if($id > 0){
            $payments = Payment::where([['payable_id',$id],['payable_type','orders']])->orderBy('created_at','DESC')->paginate(15);
            return view('be.commerce.payment-history')->with(['payments'=>$payments, 'order_id' => $id]);
        }
        return view('404');
    }

    public function configurePayment($user_id, $order_id)
    {
        $card_processor = env('CARD_PROCESSOR');
        if (strtolower(env('CARD_PROCESSOR')) == 'authorize') {
            $client_key = env('AUTHORIZE_PUBLIC_KEY');
            $api_id = env('AUTHORIZE_API_ID');
        } else if (strtolower(env('CARD_PROCESSOR')) == 'stripe') {
            $client_key = env('STRIPE_PUBLIC_KEY');
        }
        $order = Order::where('id',$order_id)->first();
        if ($user_id > 0) {
            $user = User::where('id',$user_id)->with('customerProfile.paymentProfiles')->first();
            // $subscription = Subscription::with('customerProfile')->find($id);
            if ($user) {
                return view('be.commerce.configure-payment')->with([
                    'user' => $user,
                    'card_processor' => $card_processor,
                    'client_key' => $client_key,
                    'order_id' => $order_id,
                    'api_id' => strtolower(env('CARD_PROCESSOR')) == 'authorize' ? $api_id : '',
                    'total_amount' => (isset($order)) ? ($order->total_amount - $order->paid_amount) : 0.00
                ]);
            }
        }
        return view('be.commerce.configure-payment')->with([
            'user' => [],
            'card_processor' => $card_processor,
            'client_key' => $client_key,
            'order_id' => $order_id,
            'api_id' => strtolower(env('CARD_PROCESSOR')) == 'authorize' ? $api_id : '',
            'total_amount' => (isset($order)) ? ($order->total_amount - $order->paid_amount) : 0.00
        ]);
        return view('404');
    }
}