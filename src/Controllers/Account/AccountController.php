<?php

namespace Properos\Commerce\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Classes\Store\CAccount;
use Illuminate\Support\Facades\Auth;
use Properos\Commerce\Models\Account;
use Properos\Users\Models\User;
use Properos\Base\Classes\Theme;

class AccountController extends Controller
{
    private $user; 
    
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    | 
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        if ($user = Auth::check()) {
            $user = Auth::user();
            $account = $this->user->with(['orders' => function ($q) {
                return $q->where('type', 'image');
            }])->with('orders.shippingMethod')->with('profile')->with('addresses')->with('customerProfile.paymentProfiles')->find($user->id);
           
            $orders = $account->orders()->with(['orderItems.item.files' => function ($q) {
                return $q->where('type', 'image')->where('owner_type', 'item');
            }])->where('status', '!=', 'pay__error')->where('status', '!=', 'cart')->orderBy('created_at','desc')->paginate(3);
            
           
            return view('themes.'.Theme::get().'.my_account')->with([
                'account' => $account,
                'orders' => $orders,
                'theme' => Theme::get()
            ]);
        }
    }
}
