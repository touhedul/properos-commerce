<?php

namespace Properos\Commerce\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Properos\Commerce\Models\Item;

class ProductQtyChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /* public  $quantity; */
    public  $item;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item)
    {
       /*  $this->quantity = $quantity; */
        $this->item = $item;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
