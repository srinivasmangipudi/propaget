<?php namespace App\Http\Controllers;

use App\Events\EventRequirementAdded;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdamWathan\BootForms\Facades\BootForm;

use App\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class RequirementController extends Controller {

	public function getAddRequirementPage() {
        return view('requirements.add-requirement');
    }

    public function postSaveRequirement(Request $request) {
        $postData = $request->input();

        $req = new Requirement();
        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = $postData['location'];
        $req->area = $postData['area'];
//        $req->save();

        \Event::fire(new EventRequirementAdded(1, $req));

        return redirect()->route('addRequirement');
    }

}
