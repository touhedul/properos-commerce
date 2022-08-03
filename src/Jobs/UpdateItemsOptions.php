<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Properos\Commerce\Models\Item;

class UpdateItemsOptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $option;
    public function __construct($option)
    {
        $this->option = $option;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = 0;
        $model = new Item();
        $to= $model::whereNotNull('options')->max('id');

        $model = $model::on();

        $total = $to;
        $take = 10000;

        $pages = ceil($total / $take);
        
        for ($i = 0; $i < $pages; $i++) {
            $skip = $from + ($take * $i);

            if (($take + $skip) > $to) {
                $take = $to - $skip;
            }

            $items = $model->whereNotNull('options')->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get();

            if(count($items) > 0){
                foreach($items as $item){
                    $current_options = [];
                    $current_variants = [];
                    foreach($item->options as $opt){
                        if($opt['label'] != $this->option){
                            $current_options[] = $opt;
                        }
                    }
                    foreach($item->variants as $v){
                        if(!isset($v[$this->option])){
                            $current_variants[] = $opt;
                        }
                    }
                    $item->options = (count($current_options) > 0) ? $current_options : null;
                    $item->variants = (count($current_variants) > 0) ? $current_variants : null;
                    $item->save();
                }
            }
        }
    }
}
