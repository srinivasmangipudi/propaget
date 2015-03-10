<?php

class BaseController extends Controller {

    /**
     * This layout file will be available for all controllers
     * @var string
     */
    protected $layout = 'layouts.html';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

}
