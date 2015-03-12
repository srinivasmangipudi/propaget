<?php namespace App\Http\Controllers;

use App\Events\EventRequirementAdded;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdamWathan\BootForms\Facades\BootForm;

use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class RequirementController extends Controller {

    public function getListRequirementPage()
    {
        //get all Requirement of this user
        $user = Auth::user();
        $allRequirements = Requirement::where('agentId','=',$user->id)->get();
        return view('requirements.view-requirement',compact('allRequirements'));
    }

	public function getAddRequirementPage()
    {
        return view('requirements.add-requirement');
    }

    public function postSaveRequirement(Request $request)
    {
        $postData = $request->input();
        $user = Auth::user();

        $req = new Requirement();
        $req->agentId = $user->id;
        $req->clientId = 1;
        $req->location = $postData['location'];
        $req->area = $postData['area'];
        $req->range = $postData['range'];
        $req->price = $postData['price'];
        $req->priceRange = $postData['priceRange'];
        $req->type = $postData['type'];
        
        if ( ! $req->save(['user' => $user, 'requirement' => $req]))
        {
            $errors = $req->getErrors()->all();
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        return redirect()->route('viewRequirement');
    }

}
