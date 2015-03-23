<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 18/3/15
 * Time: 11:12 AM
 */
use \App\Properties;

class PropertyTest extends TestCase {

    /** Insert new Property with correct Data **/
    public function testPropertySave() {
        $property = new Properties;

        $property->agentId = 1;
        $property->clientId = 1;
        $property->location = 'Location';
        $property->area = 'Area';
        $property->price = '3435';
        $property->title = 'Unit testing property';

        if (!$property->save()) {
            $errors = $property->getErrors()->all();
            echo 'Insert new property failed' . print_r($errors);
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }

    /** Insert new Property with incorrect Data **/
    public function testPropertyIncorrectDataSave() {
        $property = new Properties;

        $property->agentId = 1;
        $property->clientId = 1;
        $property->location = 'Location';
        $property->area = 'Area';
        $property->price = '';
        $property->title = 'Unit testing property';

        if (!$property->save()) {
            $errors = $property->getErrors()->all();
            //echo 'Insert incorrect property data passed' . print_r($errors);
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    /** Update property with correct data **/
    public function testPropertyUpdate()
    {
        $property = new Properties;
        $propData = Properties::find(10);
        $property->agentId = 1;
        $property->clientId = 1;
        $propData->location = 'Mulund';
        $propData->price = '3435345';
        $propData->area = '3456';
        $propData->title = 'esee';

        if(!$propData->save()) {
            $errors = $propData->getErrors()->all();
            echo 'Update property test failed' . print_r($errors);
            $this->assertTrue(false);
        }else {
            $this->assertTrue(true);
        }

    }

    /** Update property with incorrect data **/
    public function testPropertyUpdateIncorrectData()
    {
        $property = new Properties;
        $propData = Properties::find(10);
        $property->agentId = 1;
        $property->clientId = 1;
        $propData->location = 'Mu';
        $propData->price = 'sadasdasd';
        $propData->area = '3456';
        $propData->title = 'esee';

        if(!$propData->save()) {
            $errors = $propData->getErrors()->all();
            //echo 'Update incorrect req data passed' . print_r($errors);
            $this->assertTrue(true);
        }else {
            $this->assertTrue(false);
        }

    }
}