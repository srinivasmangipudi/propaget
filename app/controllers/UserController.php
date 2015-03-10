<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 10/3/15
 * Time: 3:31 PM
 */

class UserController extends BaseController {

    protected $layout = 'layouts.html';

    public function getLoginPage() {
        $this->layout->pageTitle = 'Login to Propagate';
        $this->layout->content = View::make('user.login');
    }
}