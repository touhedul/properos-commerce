<?php

namespace Properos\Commerce\Controllers\OrderItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\COrderItem;
use Properos\Commerce\Classes\CItem;
use Properos\Commerce\Models\OrderItem;
use Properos\Commerce\Models\Order;
use Properos\Base\Classes\Api;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Classes\CDiscount;

class ApiOrderItemController extends Controller
{
    private $cOrderItem;
    private $cItem;
    private $orderItem;

    public function __construct(COrderItem $cOrderItem, CItem $cItem, OrderItem $orderItem)
    {
        $this->cOrderItem = $cOrderItem;
        $this->cItem = $cItem;
        $this->orderItem = $orderItem;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation TODO

        $orderItem = $this->cOrderItem->store($request->all());
        if ($orderItem != null) {
            return Api::success('OrderItem created successfully', $orderItem->id);
        }
        return Api::error('Error creating the OrderItem', []);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id > 0) {
            if ($orderItem = $this->orderItem->find($id)) {
                $result = $this->orderItem->destroy($id);
                if ($result == true) {
                    $order = Order::where('id',$orderItem->order_id)->with('orderItems')->first();
                    if ($order) {
                        $this->cItem->increaseItemQty($orderItem->item, $orderItem->qty, true);
                        // if($order->discount_code != null){
                        //     $discount = Discount::where('code',$order->discount_code)->first();
                        //     $cDiscount = new CDiscount();
                        //     $result = $cDiscount->processDiscount($discount, $order->toArray());
                        //     $order->total_amount
                        // }else{
                            $items = $this->orderItem->where('order_id',$order->id)->get();
                            $subtotal = 0.00;
                            $tax_base = 0.00;
                            foreach($items as $item){
                                if($item->discount_percent != null){
                                    $subtotal += ($item->price - ($item->price * $item->discount_percent/100)) * $item->qty;
                                }else{
                                    $subtotal += $item->price * $item->qty;
                                }
                                if($item->taxes > 0){
                                    $tax_base += $item->price * $item->qty;
                                }
                            }
                            $order->total_tax_amount = $tax_base*$order->tax /100;
                            $order->subtotal = $subtotal;
                            $order->tax_base = $tax_base;
                            $order->total_amount = $subtotal + $order->total_tax_amount;
                            $order->discount_code = null;
                            $order->discount_amount = 0.00;
                            $order->save();
                        // }
                        
                    }
                    return Api::success('orderItem deleted successfully', []);
                }
            }
            return Api::error('001', 'Error removing the orderItem');
        }
    }
}


//PENDING SYNC ON ORDER ITEM ADDITION
