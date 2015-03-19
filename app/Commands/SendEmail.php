<?php namespace App\Commands;

use App\Commands\Command;

use App\DistList;
use App\DistListMembers;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

    /**
     * Create a new command instance.
     *
     */
    private $postData;
    private $members;

	public function __construct($postData, $members)
	{
		$this->postData = $postData;
        $this->members = $members;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
        Log::info("I was here inside sendmail");
        $postData = $this->postData;
        $members = $this->members;

        $distList = new DistList;
        $distList->saveEntireDistributionList(array(
            'name' => $postData['name'],
            'createdBy' => $postData['createdBy']
        ), $members);
	}

}
