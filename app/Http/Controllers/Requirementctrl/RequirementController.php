<?php namespace App\Http\Controllers\Requirementctrl;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Requirement;
use App\User;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Auth;

class RequirementController extends Controller {

    var $OAuthFailCode = '403';
    var $internalServerErrorCode = '502';
    var $validationFailCode = '422';
    var $successCode = '201';
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
        try{
            //$userId = Auth::user()->id;
            $requirementData = Request::all();
            //Log::info('this store'. print_r($requirementData, true));

            $user = User::find(1);
            $req = new Requirement();
            $req->agentId = 2;
            $req->clientId = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->clientEmail = $requirementData['clientEmail'];
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
                $data = $req;
                $message = 'Requirement added successfully';
                return Response::json(array('message' => $message ,'data'=>$data), $this->successCode);

            }
            else
            {
                $data = $errors;
                $message = 'Requirement not added.';
                return Response::json(array('message' => $message ,'data'=>$data), $this->validationFailCode);
            }
        }
        catch(Exception $e)
        {
            $data = '';
            $message = 'Requirement not Added.';
            return Response::json(array('message' => $message ,'data'=>$data), $this->internalServerErrorCode);
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $response = Requirement::where('agentId','=','2')->where('id','=',$id)->get();
        return $response;
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
        try{
            $user = User::find(1);
            $requirementData = Request::all();
            $req =  Requirement::find($id);
            $req->agentId = 2;
            $req->clientId = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->clientEmail = $requirementData['clientEmail'];
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
                $data = $req;
                $message = 'Requirement updated successfully';
                return Response::json(array('message' => $message ,'data'=>$data), $this->successCode);

            }
            else
            {
                $data = $errors;
                $message = 'Requirement not updated.';
                return Response::json(array('message' => $message ,'data'=>$data), $this->validationFailCode);
            }
        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Requirement not updated.';
            return Response::json(array('message' => $message ,'data'=>$data), $this->internalServerErrorCode);
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
        try
        {
            //Log::error('i m in delete'.$id);
            Requirement::destroy($id);
            $data = $id;
            $message = 'Requirement Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), $this->successCode);


        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Requirement not Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), $this->internalServerErrorCode);
        }

	}
}
