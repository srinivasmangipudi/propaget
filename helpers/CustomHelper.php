<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 31/3/15
 * Time: 11:48 AM
 */

use App\Watchdog;
use Illuminate\Support\Facades\Auth;

if ( !function_exists('watchdog_message') )
{
    /**
     * @param $message
     * @param string $type
     * @param array $data
     */
    function watchdog_message($message, $type = 'normal', $data = [])
    {

        $watchdog = new Watchdog;
        $watchdog->user_id = Auth::user()->id;
        $watchdog->message = $message;
        $watchdog->type = $type;
        $watchdog->data = serialize($data);
        $watchdog->save();

    }
}