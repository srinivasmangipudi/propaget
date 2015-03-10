<?php

class HomeController extends BaseController {


    public function getHomePage() {
        $this->layout->pageTitle =  "Login or register";
        $this->layout->content = View::make('home.login');
    }

}
