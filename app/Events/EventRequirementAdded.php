<?php namespace App\Events;

use App\Events\Event;

use App\Requirement;
use Illuminate\Queue\SerializesModels;

class EventRequirementAdded extends Event {

    use SerializesModels;

    public $user;

    public $requirement;

    /**3
     * Create a new event instance.
     *
     * @param User $user
     * @param Requirement $requirement
     */
    public function __construct($user, $requirement)
    {
        $this->user = $user;
        $this->requirement = $requirement;
    }

}
