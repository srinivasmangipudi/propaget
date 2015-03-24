<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 16/3/15
 * Time: 2:37 PM
 */

 namespace App\Http\Controllers\Property;

 use App\Http\Controllers\Controller;


 class PropertyAppController extends Controller {

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
        return view('property/index');
    }

    public function listing()
    {
        return view('property/list');
    }

    public function add()
    {
        return view('property/add');
    }
    public function view()
    {
        return view('property/view');
    }
}
