<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdamWathan\BootForms\Facades\BootForm;

use App\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller {

	public function getAddRequirementPage() {
        return view('requirements.add-requirement');
    }

    public function postSaveRequirement(Request $request) {
        $postData = $request->input();
//        dd($postData);

        $req = new Requirement();
        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = $postData['location'];
        $req->area = $postData['area'];
        $req->save();

        return redirect()->route('addRequirement');
    }

}
