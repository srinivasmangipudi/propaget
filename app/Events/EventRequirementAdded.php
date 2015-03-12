<?php namespace App\Events;

use App\Events\Event;

use App\Requirement;
use Illuminate\Queue\SerializesModels;

class EventRequirementAdded extends Event {

	use SerializesModels;

    public $userId;

    public $requirement;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($userId, Requirement $requirement)
	{
		\Log::info("Requirement was created");
        $this->userId = $userId;
        $this->requirement = $requirement;
	}

}
