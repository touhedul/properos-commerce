<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use Properos\Commerce\Classes\CItem;

class SyncItemsAmazonOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cItem;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CItem $cItem)
    {
        $this->cItem = $cItem;
        $active_marketplaces = [];
        if (config('app.active_amazon') == true) {
            $active_marketplaces[] = 'amazon';
        }
        if (count($active_marketplaces) > 0) {
            foreach ($active_marketplaces as $key => $marketplace) {
                $this->cItem->syncItemsWithMarketplace($marketplace);
            }
        }
    }
}
