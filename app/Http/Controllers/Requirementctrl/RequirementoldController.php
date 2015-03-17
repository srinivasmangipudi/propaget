<?php namespace App\Http\Controllers\Requirementctrl;

use App\Events\EventRequirementAdded;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdamWathan\BootForms\Facades\BootForm;

use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class RequirementoldController extends Controller {

    public function getListRequirementPage()
    {
        //get all Requirement of this user
        Log::error('i m in list of old fun');
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
        $req->save(['user' => $user, 'requirement' => $req]);

        if (isset($req->id) && $req->id > 0)
        {
            return redirect()->route('viewRequirement');
        }
        else
        {
            $errors = $req->getErrors()->all();
            print_r($errors);
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }
    }

}
