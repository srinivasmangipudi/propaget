<?php namespace App\Http\Controllers\Requirementctrl;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Requirement;
//use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PhpSpec\Exception\Exception;
use Illuminate\Support\Facades\Request;
use Auth;

class RequirementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //$allRequirement = Requirement::all();
        //$userId = Auth::user()->id;
        $userId = 2;
        $allRequirement = Requirement::where('agentId','=',$userId)->get();
        return $allRequirement;
	}

    /*public function getAllRequirement()
    {
        //Log::error('i m in all fun');
        $allRequirement = Requirement::where('agentId','=','2')->get();
//        $allRequirement = Requirement::where('agentId','=',$agentId)->get();
        return $allRequirement;
    }*/

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //$userId = Auth::user()->id;
        $userId = 2;
        $requirementData = Request::all();
        //Log::error('i m in store');
        Log::info('this store'. print_r($requirementData, true));

        //$requirement = Requirement::create($requirementData);
        $user = User::find(1);
        $req = new Requirement();
        $req->agentId = 2;
        $req->clientId = 1;
        $req->location = $requirementData['location'];
        $req->area = $requirementData['area'];
        $req->range = $requirementData['range'];
        $req->price = $requirementData['price'];
        $req->priceRange = $requirementData['priceRange'];
        $req->type = $requirementData['type'];
        $req->save(['user' => $user, 'requirement' => $req]);
        return $req;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return $id;
        /*try{
            $response = Requirement::where('agentId','=','2')->where('id','=',$id)->get();
            $statusCode = 200;
        }
        catch(Exception $e)
        {
            $response = [
                "error" => "Error while showing Requirement"
            ];
            $statusCode = 404;
        }

        return Response::json($response, $statusCode);*/
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $requirement = Requirement::find($id);
        return $requirement;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

        /*$requirementData = Request::all();
        unset($requirementData['updated_at']);
        unset($requirementData['created_at']);
        print_r($requirementData);
        Requirement::where('id', $id)->update($requirementData);*/

        Log::error('-------i m in update function------------');
        $user = User::find(1);
        $requirementData = Request::all();
        $req =  Requirement::find($id);
        $req->agentId = 2;
        $req->clientId = 1;
        $req->location = $requirementData['location'];
        $req->area = $requirementData['area'];
        $req->range = $requirementData['range'];
        $req->price = $requirementData['price'];
        $req->priceRange = $requirementData['priceRange'];
        $req->type = $requirementData['type'];
        $req->save(['user' => $user, 'requirement' => $req]);
        $errors = $req->getErrors()->all();

        if (empty($errors))
        {
            echo "Update successfully";
        }
        else
        {
            echo "Not update successfully";
        }

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //Log::error('i m in delete'.$id);
		Requirement::destroy($id);
        return "Delete Requirement";
	}
}
