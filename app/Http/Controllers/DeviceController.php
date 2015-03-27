<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 14/3/15
 * Time: 4:17 PM
 */

class DeviceController extends Controller {

    /**
     * Handle the device registration which happens
     * when the application is installed.
     * @param Request $request
     */
    function registerDevice(Request $request) {
        $postData = $request->input();
        $user_id = $request['user_id'];

        $device = new Device;
        $device->device_id = $postData['deviceId'];
        $device->registraion_id = $postData['registrationId'];
        $device->user_id = $user_id;
        $device->save();
    }

}