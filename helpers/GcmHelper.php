<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 13/3/15
 * Time: 12:42 PM
 */

class GcmHelper {

    private $google_api_key;

    public function __construct()
    {
        $this->google_api_key = env('GCM_KEY');
    }

    /**
     * @param $registatoinIds
     * @param $message
     * @return mixed
     */
    public function sendNotification(array $registatoinIds, array $message)
    {

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoinIds,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . $this->google_api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        watchdog_message('GCM notification sent.', 'normal', ['regIds' => $registatoinIds, 'message' => $message]);

        echo $result;
    }
}