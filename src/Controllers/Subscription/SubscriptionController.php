<?php

namespace Properos\Commerce\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CSubscription;
use Properos\Base\Classes\Api;

class SubscriptionController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->all();
        $cSubcription = new CSubscription();
        return response()->json(Api::success('Subscription updated.', $cSubcription->update($data)));
    }
}
