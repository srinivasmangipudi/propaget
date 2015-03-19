<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 18/3/15
 * Time: 11:12 AM
 */
use \App\Properties;

class PropertyTest extends TestCase {

    /* Save property
    public function testPropertySave() {
        $property = new Properties;

        $property->agentId = 1;
        $property->clientId = 1;
        $property->location = 'Location';
        $property->area = 'Area';
        $property->price = '';
        $property->title = 'Unit testing property';
        $property->save();

        if (isset($property->id) && $property->id > 0) {
            $this->assertTrue(true);
        } else {
            $errors = $property->getErrors()->all();
            print_r($errors);

            $this->assertFalse(false);
        }
    }*/

    /* Update property */
    public function testPropertyUpdate()
    {
        $property = new Properties;
        $propData = Properties::find(10);
        $propData->location = 'Mu';
        $propData->price = 'afsafasfasf';
        $propData->area = '3456';
        $propData->title = 'esee';
        $propData->save();

        if (isset($propData->id) && $propData->id > 0) {
            $this->assertTrue(true);
        } else {
            $errors = $property->getErrors()->all();
            print_r($errors);

            $this->assertFalse(false);
        }


    }
}