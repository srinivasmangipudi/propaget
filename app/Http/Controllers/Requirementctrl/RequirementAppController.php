<?php namespace App\Http\Controllers\Requirementctrl;
/**
 * Created by PhpStorm.
 * User: Urmila
 * Date: 16/3/15
 * Time: 4:40 PM
 */

use App\Http\Controllers\Controller;


class RequirementAppController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('requirements/index');
    }

    public function listing()
    {
        return view('requirements/list');
    }

    public function add()
    {
        return view('requirements/add');
    }
}
