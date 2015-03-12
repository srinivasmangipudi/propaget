<?php namespace App\Handlers\Events;

use App\Events\EventRequirementAdded;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class HandleRequirementAdded {

	/**
	 * Create the event handler.
	 *
	 * @return void
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
		dd($event);
	}

}
