<?php namespace App\Commands;

use App\Commands\Command;

use App\DistList;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SaveDistributionList extends Command implements SelfHandling, ShouldBeQueued {

    use InteractsWithQueue, SerializesModels;

    protected $distListId;
    protected $members;

    /**
     * Create a new command instance.
     *
     */
    public function __construct($members, $distListId)
    {
        $this->members = $members;
        $this->distListId = $distListId;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        // handle the saving of the distribution list and all it's members
        $distList = new DistList;
        $distList->runQueueToSaveDistList($this->members, $this->distListId);
    }

}
