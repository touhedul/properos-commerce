<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Item;
use Illuminate\Support\Carbon;
use Properos\Base\Classes\Api;
use Properos\Commerce\Helpers\BarCodeGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Properos\Commerce\Models\File as MFile;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\OrderItem;
use Properos\Commerce\Models\MarketplaceItem;
use Properos\Commerce\Classes\CFile;
use Properos\Commerce\Classes\Integrations\Common\ECommerceClass;
use Carbon\Carbon as CCarbon;
use Properos\Commerce\Events\ProductQtyChanged;
use Properos\Base\Classes\Base;
use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Helper;
use Properos\Commerce\Models\Setting;
use Properos\Commerce\Models\ItemCollection;

class CItem extends Base
{
    private $item;
    private $file;
    private $order;
    private $orderItem;
    private $ecommerce;

    function __construct(MFile $file, CFile $cFile, Order $order, OrderItem $orderItem)
    {
        parent::__construct(Item::class, 'Item');
        $this->item = $this->model;
        $this->file = $file;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->cFile = $cFile;

        $data = config('amazon.store.hiketron');
        $this->ecommerce = new ECommerceClass('amazon', $data);

    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

    public function store(array $data)
    {
        if ($data['id'] > 0) {
            $item = $this->item->find($id); 
        }
        if (count($data) > 0) {
            $flag = false;
            if(isset($data['sku']) && $data['sku'] != ''){
                $items = Item::where('sku', strtoupper($data['sku']))->count('id');
                if($items > 0){
                    throw new ApiException('Sku has already been taken','001',$data);
                }else{
                    $this->item->sku = strtoupper($data['sku']);
                }
            }else{
                $flag = true;
            }

            $this->item->name = $data['name'];
            $sort_name = $this->createShortName($data['name']);
            $current_item = $this->item->where('short_name', $sort_name)->first();
            if ($current_item) {
                $sort_name = $sort_name . '_' . Str::random(3);
            }
            $this->item->short_name = $sort_name;
            $this->item->description = $data['description'];
            $this->item->price = $data['price'];
            $this->item->type = $data['type'];
            if (isset($data['active'])) {
                $this->item->active = $data['active'];
            } else {
                $this->item->active = 1;
            }
            if (array_key_exists('upc', $data)) {
                $this->item->upc = $data['upc'];
            }
            
            if (array_key_exists('publish', $data)) {
                $date = new CCarbon($data['publish']);
                $this->item->publish_date = $date->toDateTimeString();
            } else {
                $now = Carbon::now();
                $this->item->publish_date = $now->toDateTimeString();
            }
            if (array_key_exists('msrp', $data)) {
                $this->item->msrp = $data['msrp'];
            }
            if (array_key_exists('cost', $data)) {
                $this->item->cost = $data['cost'];
            }
            if (array_key_exists('category_id', $data)) {
                $this->item->category_id = $data['category_id'];
            }
            if (array_key_exists('discount_percent', $data)) {
                $this->item->discount_percent = $data['discount_percent'];
            }
            if (array_key_exists('total_qty', $data)) {
                $this->item->total_qty = $data['total_qty'];
            }
            if (array_key_exists('um', $data)) {
                $this->item->um = $data['um'];
            }

            if (array_key_exists('weight', $data)) {
                $this->item->weight = $data['weight'];
                $this->item->weight_unit = $data['weight_unit'];
            }
            if (array_key_exists('height', $data)) {
                $this->item->height = $data['height'];
                $this->item->height_unit = $data['height_unit'];
            }
            if (array_key_exists('width', $data)) {
                $this->item->width = $data['width'];
                $this->item->width_unit = $data['width_unit'];
            }
            if (array_key_exists('length', $data)) {
                $this->item->length = $data['length'];
                $this->item->length_unit = $data['length_unit'];
            }
            // if (array_key_exists('units_in_box', $data)) {
            //     $this->item->units_in_box = $data['units_in_box'];
            //     $this->item->box_unit = $data['box_unit'];
            // }

            if (array_key_exists('package_type', $data)) {
                $this->item->package_type = $data['package_type'];
            }

            if (array_key_exists('options', $data)) {
                $this->item->options = $data['options'];
            }
            if (array_key_exists('variants', $data)) {
                $this->item->variants = $data['variants'];
            }

            if (array_key_exists('taxable', $data)) {
                if ($data['taxable'] == true) {
                    if (array_key_exists('tax_percent', $data)) {
                        $this->item->taxable = 1;
                        $this->item->tax_percent = $data['tax_percent'];
                    }
                }
            } else {
                $this->item->taxable = 0;
                $this->item->tax_percent = null;
            }
            if ($this->item->save()) {
                if(isset($data['collections'])){
                    $insert = [];
                    foreach($data['collections'] as $collect){
                        $item_collection = ItemCollection::where('collection_id',$collect['id'])->orderBy('sort_order','DESC')->first();
                        $insert[] = [
                            'item_id' => $this->item->id,
                            'collection_id' => $collect['id'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'sort_order' => ($item_collection) ? $item_collection->sort_order+1 : 1
                        ];
                    }
                    ItemCollection::insert($insert);
                }
                if($flag){
                    $inventory = Setting::where('key', 'inventory')->first();
                    if ($inventory) {
                        $setting = json_decode($inventory->data, true);
                        if (isset($setting['sku']) && $setting['sku']['sku_number_type'] == 'consecutive') {
                            $this->item->sku = $setting['sku']['prefix_sku'] . ($this->item->id) . $setting['sku']['suffix_sku'];
                        } else {
                            $this->item->sku = Helper::base2base(1000 * $this->item->id, 10, 36) . strtoupper(Str::random(4));
                        }
                    } else {
                        $this->item->sku = Helper::base2base(1000 * $this->item->id, 10, 36) . strtoupper(Str::random(4));
                    }
                }
                $this->item->save();
                $this->generateUPCBarCode($this->item->upc, $this->item->id);
            }
        }

        return $this->item;
    }

    public function update(array $data)
    {
        if (count($data) > 0) {
            if ($data['id'] > 0) {
                $item = $this->item->with('marketplaceItems')->find($data['id']);
                if ($item) {
                    if(isset($data['sku'])){
                        $items = Item::where([['sku', strtoupper($data['sku'])],['id','<>',$data['id']]])->count('id');
                        if($items > 0){
                            throw new ApiException('Sku has already been taken','001',$data);
                        }else{
                            $item->sku = strtoupper($data['sku']);
                        }
                    }else{
                        $inventory = Setting::where('key', 'inventory')->first();
                        if ($inventory) {
                            $setting = json_decode($inventory->data, true);
                            if (isset($setting['sku']) && $setting['sku']['sku_number_type'] == 'consecutive') {
                                $item->sku = $setting['sku']['prefix_sku'] . ($item->id) . $setting['sku']['suffix_sku'];
                            } else {
                                $item->sku = Helper::base2base(1000 * $item->id, 10, 36) . strtoupper(Str::random(4));
                            }
                        } else {
                            $item->sku = Helper::base2base(1000 * $item->id, 10, 36) . strtoupper(Str::random(4));
                        }
                    }
                    $current_qty = $item->total_qty;
                    $item->name = $data['name'];
                    $sort_name = $this->createShortName($data['name']);
                    $current_item = $this->item->where('short_name', $sort_name)->first();
                    if ($current_item) {
                        $sort_name = $sort_name . '_' . Str::random(3);
                    }
                    $item->description = $data['description'];
                    $item->price = $data['price'];
                    $item->type = $data['type'];
                    if (isset($data['active'])) {
                        $item->active = $data['active'];
                    } else {
                        $item->active = 1;
                    }
                    if (array_key_exists('upc', $data)) {
                        $item->upc = $data['upc'];
                    }
                    if (array_key_exists('publish', $data)) {
                        $date = new CCarbon($data['publish']);
                        $item->publish_date = $date->toDateTimeString();
                    } else {
                        $now = Carbon::now();
                        $item->publish_date = $now->toDateTimeString();
                    }
                    if (array_key_exists('weight', $data)) {
                        $item->weight = $data['weight'];
                        $item->weight_unit = $data['weight_unit'];
                    }
                    if (array_key_exists('category_id', $data)) {
                        $item->category_id = $data['category_id'];
                    }
                    if (array_key_exists('discount_percent', $data)) {
                        $item->discount_percent = $data['discount_percent'];
                    }
                    if (array_key_exists('total_qty', $data)) {
                        $item->total_qty = $data['total_qty'];
                    }
                    if (array_key_exists('um', $data)) {
                        $item->um = $data['um'];
                    }

                    if (array_key_exists('weight', $data)) {
                        $item->weight = $data['weight'];
                        $item->weight_unit = $data['weight_unit'];
                    }
                    if (array_key_exists('height', $data)) {
                        $item->height = $data['height'];
                        $item->height_unit = $data['height_unit'];
                    }
                    if (array_key_exists('width', $data)) {
                        $item->width = $data['width'];
                        $item->width_unit = $data['width_unit'];
                    }
                    if (array_key_exists('length', $data)) {
                        $item->length = $data['length'];
                        $item->length_unit = $data['length_unit'];
                    }
                    // if (array_key_exists('units_in_box', $data)) {
                    //     $item->units_in_box = $data['units_in_box'];
                    //     $item->box_unit = $data['box_unit'];
                    // }

                    if (array_key_exists('package_type', $data)) {
                        $item->package_type = $data['package_type'];
                    }

                    if (array_key_exists('options', $data)) {
                        $item->options = $data['options'];
                    }
                    if (array_key_exists('variants', $data)) {
                        $item->variants = $data['variants'];
                    }

                    if (array_key_exists('cost', $data)) {
                        $item->cost = $data['cost'];
                    }

                    if (array_key_exists('taxable', $data)) {
                        if ($data['taxable'] == true) {
                            if (array_key_exists('tax_percent', $data)) {
                                $item->taxable = 1;
                                $item->tax_percent = $data['tax_percent'];
                            }
                        } else {
                            $item->taxable = 0;
                            $item->tax_percent = null;
                        }
                    }
                    // dd($data['collections']);
                    if(isset($data['collections'])){
                        ItemCollection::where('item_id',$item->id)->delete();
                        $insert = [];
                        foreach($data['collections'] as $collect){
                            $item_collection = ItemCollection::where('collection_id',$collect['id'])->orderBy('sort_order','DESC')->first();
                            $insert[] = [
                                'item_id' => $item->id,
                                'collection_id' => $collect['id'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'sort_order' => ($item_collection) ? $item_collection->sort_order+1 : 1
                            ];
                        }
                        ItemCollection::insert($insert);
                    }
                    if ($item->save()) {
                        if ($item->total_qty != $current_qty) {
                            event(new ProductQtyChanged($item));
                        }
                    }
                }
            }
        }
        return $item;
    }

    public function destroy($id)
    {
        $item = $this->item->find($id);
        if ($item) {
            $files = $item->files->all();
            if (count($files) > 0) {
                foreach ($item->files as $key => $file) {
                    $this->cFile->deleteFile($file->id);
                }
            }
            return $item->delete();
        }
    }

    public function generateUPCBarCode($upc, $item_id)
    {
        if ($upc && $item_id) {
            $generator = new BarCodeGenerator();
            $symbology = 'upc-a';
            $options = [
                'f' => 'png',
            ];
            $image = $generator->render_image($symbology, $upc, $options);
            $stream_image = Image::make($image)->encode('jpg', 95)->stream();
            $barcode = 'pictures/item/barcodes/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($barcode, $stream_image);
            imagedestroy($image);

            if ($barcode) {
                $data['path'] = $barcode;
                $data['owner_type'] = 'item';
                $data['owner_id'] = $item_id;
                $data['type'] = 'barcode';

                $file = $this->file->store($data);

                if ($file != null) {
                    return $file;
                } else {
                    return false;
                }
            }

        } else {
            return null;
        }
    }

    function randomNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 8);
        }
        return $result;
    }

    function getPriceWithTax($price, $tax_percent)
    {
        if ($price > 0 && $tax_percent > 0) {
            $final_price = $price + ($price * ($tax_percent / 100));
            return round($final_price, 2);
        }
    }

    function marketplaceLink(array $data)
    {
        $result = [];
        if (isset($this->ecommerce->ecommerce)) {
            if (array_key_exists('marketplace_id', $data)) {
                if ($data['marketplace'] == 'amazon') {
                    $options['IdType'] = 'ASIN';
                    $options['IdList'] = ['Id' => $data['marketplace_id']];
                    $itemInAmazon = $this->ecommerce->getProductList($options);
                    if (count($itemInAmazon) > 0) {
                        if (array_key_exists('0', $itemInAmazon)) {
                            if (count($itemInAmazon[0]->getProduct() > 0)) {
                                $item = $this->item->find($data['item_id']);
                                if ($item) {
                                    $marketItem = new MarketplaceItem();
                                    $marketItem->name = $data['marketplace'];
                                    if ($data['marketplace'] == 'amazon') {
                                        $marketItem->marketplace_id = $data['marketplace_id'];
                                    }
                                    $currentBindings = MarketplaceItem::all();
                                    if (count($currentBindings) > 0) {
                                        foreach ($currentBindings as $key => $item) {
                                            if ($item->marketplace_id == $data['marketplace_id'] && $item->name == $data['marketplace']) {
                                                $existingItem = $item;
                                            }
                                        }
                                    }
                                    if (isset($existingItem)) {
                                        $result['status'] = 2;
                                        $result['code'] = 002;
                                        if ($data['marketplace'] == 'amazon') {
                                            $result['message'] = 'There is another item liked with this ASIN in marketplace ' . ucfirst($data['marketplace']) . '. Linked Item: ' . $existingItem->item->name;
                                        } else {
                                            $result['message'] = 'There is another item liked with this identifier in marketplace ' . ucfirst($data['marketplace']) . '. Linked item: ' . $existingItem->item->name;
                                        }
                                    } else {
                                        if ($item->marketplaceItems()->save($marketItem)) {
                                            $result['status'] = 1;
                                            $result['data'] = $marketItem;
                                            $result['message'] = 'Item synced successfully';
                                        } else {
                                            $result['status'] = 2;
                                            $result['code'] = 002;
                                            $result['message'] = 'Error syncing the item';
                                        }
                                    }
                                } else {
                                    $result['status'] = 2;
                                    $result['code'] = 002;
                                    $result['message'] = 'Item not found';
                                }
                            }
                        } else {
                            $result['status'] = 2;
                            $result['code'] = 002;
                            $result['message'] = "The provided ASIN is incorrect. Check if this item have been registered in Amazon.";
                        }
                    }
                }
            }
        }

        return $result;
    }

    function marketplaceRemove($id)
    {
        $result = [];
        $marketplaceItem = MarketplaceItem::find($id);
        if ($marketplaceItem) {
            if ($marketplaceItem->delete()) {
                $result['status'] = 1;
                $result['data'] = $marketplaceItem;
                $result['message'] = 'Item sync removed successfullt';
            } else {
                $result['status'] = 2;
                $result['code'] = 002;
                $result['message'] = 'Error removing ttem sync';
            }
        } else {
            $result['status'] = 2;
            $result['code'] = 002;
            $result['message'] = 'Item not found';
        }
        return $result;
    }

    function syncItemsWithMarketplace($marketplace)
    {
        if (isset($this->ecommerce->ecommerce)) {
            switch ($marketplace) {
                case 'amazon':
                    $currentOrders = $this->ecommerce->getOrders($options = []);
                    if (count($currentOrders) > 0) {
                        foreach ($currentOrders as $key => $order) {
                            $current_order = $this->order->with('orderItems')->where('order_number', $order->number)->first();
                            if (!$current_order) {
                                $new_order = new Order();
                                $new_order->order_number = $order->number;
                                $new_order->total_amount = $order->total;
                                $new_order->origin = 'amazon';
                                $new_order->customer_name = $order->name;
                                $new_order->customer_email = $order->email;
                                $new_order->shipping_address1 = $order->shipping_address;
                                $new_order->shipping_tracking = $order->tracking_number;
                                $new_order->status = $order->status;
                                $new_order->marketplace_created_at = $order->ordered;
                                $new_order->save();
                                $new_order_id = $new_order->id;
                                if ($new_order_id > 0) {
                                    if (count($order->items) > 0) {
                                        $total_tax = 0;
                                        $total_shipping_price = 0;
                                        foreach ($order->items as $key => $order_item) {
                                            $new_order_item = new OrderItem();
                                            $new_order_item->order_id = $new_order_id;
                                            $new_order_item->item_id = 0;
                                            $new_order_item->sku = $order_item['SellerSKU'];
                                            $new_order_item->marketplace_id = $order_item['ASIN'];
                                            $new_order_item->marketplace_item_id = $order_item['OrderItemId'];
                                            $new_order_item->name = $order_item['Title'];
                                            $new_order_item->qty = intval($order_item['QuantityOrdered']);
                                            $new_order_item->price = floatval($order_item['ItemPrice']['Amount']);
                                            $total_tax += floatval($order_item['ItemTax']['Amount']);
                                            $total_shipping_price += floatval($order_item['ShippingPrice']['Amount']);
                                            $new_order->orderItems()->save($new_order_item);
                                            $syncedItems = MarketplaceItem::with('item')->where('marketplace_id', $order_item['ASIN'])->get();
                                            if (!$syncedItems->isEmpty()) {
                                                if ($syncedItems->count() === 1) {
                                                    $syncedItem = $syncedItems->first(function ($value, $key) {
                                                        return $value;
                                                    });
                                                    $item = $syncedItem->item;
                                                    if ($item) {
                                                    //Remove the external condition later
                                                        if (intval($order_item['QuantityOrdered']) > 0) {
                                                            if ($item->total_qty >= intval($order_item['QuantityOrdered'])) {
                                                                $item->total_qty -= intval($order_item['QuantityOrdered']);
                                                                if ($item->total_qty < 0) {
                                                                    $item->total_qty = 0;
                                                                }
                                                                $item->save();
                                                            }
                                                        } else {
                                                            $item->total_qty--;
                                                            $item->save();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $new_order->total_tax_amount = $total_tax;
                                        $new_order->shipping_cost = $total_shipping_price;
                                        $new_order->save();
                                    }
                                }
                            } else {
                                $current_order->status = $order->status;
                                $current_order->save();
                                switch ($order->status) {
                                    case 'Pending':
                                   # code...
                                        break;
                                    case 'Unshipped':
                                   # code...
                                        break;
                                    case 'PartiallyShipped':
                                   # code...
                                        break;
                                    case 'Canceled':
                                        foreach ($current_order->orderItems as $key => $current_order_item) {
                                            $syncedItems = MarketplaceItem::with('item')->where('marketplace_id', $current_order_item->marketplace_id)->get();
                                            if (!$syncedItems->isEmpty()) {
                                                if ($syncedItems->count() === 1) {
                                                    $syncedItem = $syncedItems->first(function ($value, $key) {
                                                        return $value;
                                                    });
                                                    $item = $syncedItem->item;
                                                    if ($item) {
                                                    //Remove the external condition later
                                                        if (intval($current_order_item->qty) > 0) {
                                                            $item->total_qty += intval($current_order_item->qty);
                                                        } else {
                                                            $item->total_qty++;
                                                        }
                                                        $item->save();
                                                    }
                                                }
                                            }
                                        }
                                        break;
                                    case 'Unfulfillable':
                                   # code...
                                        break;
                                    case 'Shipped':
                                   # code...
                                        break;

                                    default:
                                   # code...
                                        break;
                                }
                            }
                        }
                    }
                // return $currentOrders;
                    break;
                default:
                    break;
            }
        }

    }

    static function getOrdersItems($id)
    {
        if (isset($this->ecommerce->ecommerce)) {
            return $currentItems = $this->ecommerce->getOrderItems($id);
        }
    }

    function updateMarketplaceQty($marketplaceItem, $quantity)
    {
        if (isset($this->ecommerce->ecommerce)) {
            switch ($marketplaceItem->name) {
                case 'amazon':
                    if (isset($marketplaceItem->item->sku)) {
                        $options['sku'] = $marketplaceItem->item->sku;
                        $options['qty'] = $quantity;
                        $updateQtyFeed = $this->ecommerce->updateProductQtyFeed($options);
                    }
                    break;

                default:

                    break;
            }
        }
    }

    public function increaseItemQty($item, $quantity, $sync = true)
    {
        $item->total_qty += $quantity;
        $item->save();
        if ($sync == true) {
            event(new ProductQtyChanged($item));
        }
    }

    public function decreaseItemQty($item, $quantity, $sync = true)
    {
        if ($item->total_qty >= $quantity) {
            $item->total_qty -= $quantity;
        }
        $item->save();
        if ($sync == true) {
            event(new ProductQtyChanged($item));
        }
    }

    public function createShortName($name)
    {
        if (strlen($name) > 0) {
            return str_replace(" ", "_", trim(strtolower($name)));
        }
    }
}
