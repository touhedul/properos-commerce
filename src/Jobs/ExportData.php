<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Properos\Commerce\Models\Order;
use Properos\Users\Models\User;
use Properos\Commerce\Models\Subscriber;
use Properos\Base\Classes\Theme;
use Properos\Users\Models\UserActivityLog;
use Properos\Commerce\Models\OrderItem;

class ExportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $options;
    protected $end_date;
    protected $user;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $type, $options = [])
    {
        $this->options = $options;
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = 0;
        if($this->type == 'orders'){
            $model = new Order();
            $to= $model::withTrashed()->max('id');
        }else if($this->type == 'customers'){
            $model = new User();
            $to= $model::withTrashed()->max('id');
        }else if($this->type == 'subscribers'){
            $model = new Subscriber();
            $to= $model::withTrashed()->max('id');
        }else if($this->type == 'activities'){
            $model = new UserActivityLog();
            $to= $model::max('id');
        }else if($this->type == 'products_sold'){
            $model = new OrderItem();
            $to= $model::max('id');
        }
        $model = $model::on();
        $total = $to;
        $take = 10000;

        $pages = ceil($total / $take);
        
        if(!is_dir('storage/exports')){
            mkdir('storage/exports');
        }
        $rangedate = date('m/d/Y', strtotime($this->options['start_date'])).' - '.date('m/d/Y',strtotime($this->options['end_date']));
        for ($i = 0; $i < $pages; $i++) {
            $skip = $from + ($take * $i);

            if (($take + $skip) > $to) {
                $take = $to - $skip;
            }
            if($this->type == 'orders'){
                $results = $model->with('orderItems')->with('customer')->where('status','paid')->whereBetween('created_at', [$this->options['start_date'], $this->options['end_date']])->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->orderBy('created_at', 'desc')->get()->toArray();
            }else if($this->type == 'customers'){
                $results = $model->with('orders')->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();
                foreach($results as $key => $user){
                    if(isset($user['orders']) && count($user['orders']) > 0){
                        $res = [];
                        foreach($user['orders'] as $order){
                            if($order['status'] == 'paid' && $order['deleted_at'] == null){
                                $res[] = $order;
                            }
                        }
                        $results[$key]['orders'] = $res;
                    }
                }
                
            }else if($this->type == 'subscribers'){
                $results = $model->withTrashed()->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();
            }else if($this->type == 'activities'){
                if(count($this->options) > 0){
                    if(isset($this->options['where']) && count($this->options['where']) > 0){
                        if (!is_array($this->options['where'][0])) {
                            if (count($this->options['where']) > 2) {
                                $model->where($this->options['where'][0], $this->options['where'][1], $this->options['where'][2]);
                            } else {
                                $model->where($this->options['where'][0], $this->options['where'][1]);
                            }
                        } else {
                            foreach ($this->options['where'] as $where) {
                                if (!is_array($where[0])) {
                                    if (count($where) > 2) {
                                        $model->where($where[0], $where[1], $where[2]);
                                    } else {
                                        $model->where($where[0], $where[1]);
                                    }
                                }
                            }
                        }
                    }
                }
                $results = $model->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();
            }else if($this->type == 'products_sold'){
                $results = $model->selectRaw('item_id, sku, name , SUM(qty) as total')->whereBetween('created_at', [$this->options['start_date'], $this->options['end_date']])->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])
                                ->groupBy(['item_id','sku','name'])->orderBy('name', 'ASC')->get()->toArray();
            }
           
            if(count($results) >0){
                $host = env('APP_CDN').'/exports/';
                $urls = [];
                $name = ucfirst($this->type).'_Report';
                $filename = ucfirst($this->type).'_Report_'. date('Ymdhis');

                $this->arrayToCSV(public_path() . '/storage/exports/' . $filename . '_Part_' . $pages . '.csv', $this->type, $results, $rangedate);
                $urls[] = $host . $filename . '_Part_' . $pages . '.csv';
            }
        
            $user = $this->user;
            \Mail::send('themes.'.Theme::get().'.emails.export', ['urls' => $urls, 'user' => $this->user['firstname'], 'filename' => $filename], function($message) use($user, $name) {
                $message->from(env('MAIL_FROM_ADDRESS','support@properos.com'));
                $message->to($user->email);
                $message->subject($name);
            });
        }
    }

    private function arrayToCSV($filename, $type, $result, $rangedate = '') {

        $fp = fopen($filename, 'w');
        if ($type == 'orders') {
            fputcsv($fp, ['Order Number', 'Customer Name','Email', 'Origin', 'Amount', 'Status' ,'Order Placed At']);
            foreach($result as $r){
                $row[] = $r['order_number'];
                $name = ($r['customer_name'] == null) ? $r['customer']['name'] .' '. $r['customer']['lastname'] : $r['customer_name'];
                $email = ($r['customer_email'] == null) ? $r['customer']['email'] : $r['customer_email'];
                $row[] = $name;
                $row[] = $email;
                $row[] = $r['origin'];
                $row[] = $r['paid_amount'];
                $row[] = $r['status'];
                $row[] = $r['created_at'];

                fputcsv($fp, $row);
                $row = [];
            }
        } else if ($type == 'customers') {
            fputcsv($fp, ['Full Name', 'Phone', 'Email', 'Orders', 'Paid']);
            foreach($result as $r){
                $row[] = $r['firstname'].' '.$r['lastname'];
                $row[] = $r['phone'];
                $row[] = $r['email'];
                $row[] = count($r['orders']);

                if(count($r['orders']) > 0){
                    $paid = 0.00;
                    foreach($r['orders'] as $order){
                        $paid = $paid + $order['paid_amount'];
                    }
                }else{
                    $paid = 0.00;
                }
                $row[] =$paid;

                fputcsv($fp, $row);
                $row = [];
            }
        } else if ($type == 'subscribers') {
            fputcsv($fp, ['Email', 'Join At', 'Leave At']);
            foreach($result as $r){
                $row[] = $r['email'];
                $row[] = $r['created_at'];
                $row[] = ($r['deleted_at'] != null) ? $r['deleted_at'] : '-';

                fputcsv($fp, $row);
                $row = [];
            }
        } else if ($type == 'activities') {
            fputcsv($fp, ['User', 'Type', 'Description', 'Created at']);
            foreach($result as $r){
                $row[] = $r['name'];
                $row[] = $r['activity_type'];
                $row[] = $r['description'];
                $row[] = $r['created_at'];

                fputcsv($fp, $row);
                $row = [];
            }
        } else if ($type == 'products_sold') {
            fputcsv($fp, ['Range date: '.$rangedate]);
            fputcsv($fp, ['Product', 'SKU', 'Quantity']);
            foreach($result as $r){
                $row[] = $r['name'];
                $row[] = $r['sku'];
                $row[] = $r['total'];

                fputcsv($fp, $row);
                $row = [];
            }
        }
            
        
        fclose($fp);
    }
}
