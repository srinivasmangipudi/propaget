<?php namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 14/3/15
 * Time: 4:17 PM
 */

class DeviceController extends Controller {
    
    public function __construct()
    {
        $this->middleware('oauth');
    }

    /**
     * Handle the device registration which happens
     * when the application is installed.
     * @param Request $request
     */
    function registerDevice(Request $request) {
        $postData = $request->input();

        $user_id = $request['user_id'];

        $device = Device::where('device_id', $postData['deviceId'])->first();
        
        if ($device) {

            // device already exist so update
            $device->registraion_id = $postData['registrationId'];
            $device->user_id = $user_id;
            $device->save();

        } else {

            // new device
            $device = new Device;
            $device->device_id = $postData['deviceId'];
            $device->registraion_id = $postData['registrationId'];
            $device->user_id = $user_id;
            $device->save();

        }

        /*$gcm = new GcmHelper;
        $gcm->sendNotification(
            array($device->registraionId),
            array('title' => 'Device registered', 'message' => 'Congratulations, your devie has been registered with us.')
        );*/
    }

}