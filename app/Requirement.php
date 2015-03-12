<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class Requirement extends BaseModel {

    protected $table = 'requirements';

    protected $rules = [
        'area' => 'required'
    ];

    protected $messages = [
    ];

}