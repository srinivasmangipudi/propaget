<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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

        $device = new Device;
        $device->deviceId = $postData['deviceId'];
        $device->registraionId = $postData['registrationId'];
        $device->save();
    }

}