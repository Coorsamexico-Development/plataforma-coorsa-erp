<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SheGraph implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public $graph = null)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        switch ($this->graph) {
            case 'graphCDUQ':
                return new Channel('graphCDUQ');
                break;
            case 'graphCDUO':
                return new Channel('graphCDUO');
                break;
            case 'graphWML':
                return new Channel('graphWML');
                break;
            case 'graphPorcter':
                return new Channel('graphPorcter');
                break;
            case 'graphCapa':
                return new Channel('graphCapa');
                break;
            case 'graphSeafty':
                return new Channel('graphSeafty');
                break;
            case 'graphInsGDL':
                return new Channel('graphInsGDL');
                break;
            case 'graphInsProcter':
                return new Channel('graphInsProcter');
                break;
            case 'graphInsCdu':
                return new Channel('graphInsCdu');
                break;
        }
    }
}