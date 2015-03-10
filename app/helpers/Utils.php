<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 10/3/15
 * Time: 2:57 PM
 */

class Utils {

    /**
     * Code dump function
     * @param $var
     * @param int $exit
     */
    public static function dsm($var, $exit = 0) {
        print '<pre>';
        print_r($var);
        print '</pre>';
        if ($exit ===  1)
            exit;
    }

    /**
     * This function will set the message in session so that when the page renders,
     * we can display a message on top of the page.
     * @param $message
     * @param string $flag
     */
    public static function setMessage($message, $flag = 'info') {
        $tempMessage = '';
        if (Session::get('message'))
            $tempMessage = Session::get('message');
        if ($tempMessage == "")
            $tempMessage = $message;
        else
            $tempMessage = $tempMessage . '<br />' . $message;
        Session::flash('message', $tempMessage);
        Session::flash('message-flag', $flag);
    }
}