<?php namespace App\Commands;

use App\Commands\Command;

use App\DistList;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class SaveDistributionList extends Command implements SelfHandling, ShouldBeQueued {

    use InteractsWithQueue, SerializesModels;

    protected $name;
    protected $members;
    protected $createdBy;

    /**
     * Create a new command instance.
     *
     */
    public function __construct($name, $members, $createdBy)
    {
        $this->name = $name;
        $this->members = json_decode($members);
        $this->createdBy = $createdBy;
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
        $distList->saveEntireDistributionList(array(
            'name' => $this->name,
            'createdBy' => $this->createdBy
        ), $this->members);
    }

}
