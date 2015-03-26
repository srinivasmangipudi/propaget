<?php namespace App\Handlers\Events;

use App\Events\EventRequirementAdded;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class HandleRequirementAdded {

    private $user;
    private $requirement;

    /**
     * Create the event handler.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventRequirementAdded  $event
     * @return void
     */
    public function handle(EventRequirementAdded $event)
    {
        $this->user = $event->user;
        $this->requirement = $event->requirement;

        $this->sendEmail();
        $this->sendMobileNotification();
    }

    /**
     * This function will send an email to the respective user about a new Requirement being added.
     */
    private function sendEmail()
    {
        \Log::info('An email will be send to all users about a new Requirement');
    }

    /**
     * This function will send a mobile notification to all users about a new Requirement being added.
     */
    private function sendMobileNotification()
    {
        \Log::info('A GCM notification will be send to all users about a new Requirement');
    }

}
