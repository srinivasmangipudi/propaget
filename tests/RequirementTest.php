<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 12/3/15
 * Time: 10:30 AM
 */

class RequirementTest extends TestCase {

    public function testSomethingIsTrue() {
        $this->assertTrue(true);
    }

    public function testRequirementSave() {
        $req = new \App\Requirement();

        $req->agentId = 1;
        $req->clientId = 1;
        $req->location = 'Location';
       $req->area = 'Area';

        if ($req->save()) {
            $this->assertTrue(true);
        } else {
            $errors = $req->getErrors()->all();
            print_r($errors);

            $this->assertFalse(false);
        }
    }
}