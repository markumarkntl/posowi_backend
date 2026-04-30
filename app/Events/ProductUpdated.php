<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Product $product,
        public string  $action  // 'created' | 'updated' | 'deleted'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('products'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'product' => $this->product,
            'action'  => $this->action,
        ];
    }
}