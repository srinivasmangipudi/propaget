<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 12/3/15
 * Time: 10:30 AM
 */
use App\Requirement;


class RequirementTest extends TestCase {

    /** Insert new Requirement with correct Data **/
    public function testRequirementSave() {
        $req = new \App\Requirement();

        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = 'Mulund';
       $req->area = 'Area';
       $req->price = '2343545';
       $req->title = 'asdasfasf';

        if (!$req->save()) {
            $errors = $req->getErrors()->all();
           // echo 'Insert new rquirement failed' . print_r($errors);
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }

    /** Insert new Requirement with incorrect Data **/
    public function testRequirementIncorrectDataSave() {
        $req = new \App\Requirement();

        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = 'Mulund';
       $req->area = 'Area';
       $req->price = '';
        $req->title = 'asdasfasf';


        if (!$req->save()) {
            $errors = $req->getErrors()->all();
           // echo 'Insert incorrect req data passed' . print_r($errors);
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    /** Update Requirement with correct data **/
    public function testRequirementUpdateSave() {

        $req = Requirement::find(1);
        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = 'THANE';
        $req->area = 'Area';
        $req->price = '324324';
        $req->title = 'asdasfasf';


        if(!$req->save()) {
            $errors = $req->getErrors()->all();
           // echo 'Update rquirement test failed' . print_r($errors);
             $this->assertTrue(false);
        }else {
            $this->assertTrue(true);
        }
    }

    /** Update Requirement with incorrect data **/
    public function testRequirementUpdateIncorrectData() {

        $req = Requirement::find(1);
        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = 'WRONG';
        $req->area = 'Area';
        $req->price = 100;
        $req->title = 'asdasfasf';


        if(!$req->save()) {
            $errors = $req->getErrors()->all();
            echo 'Update incorrect req data passed' . print_r($errors);
             $this->assertTrue(true);
        }else {
            echo "CHECK WORKLING =========== ";
            $this->assertTrue(false);
        }
    }
}