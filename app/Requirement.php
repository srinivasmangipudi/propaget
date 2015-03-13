<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\EventRequirementAdded;

class Requirement extends BaseModel {

    protected $table = 'requirements';

    protected $rules = [
        'area' => 'required'
    ];

    protected $messages = [
    ];

    public function save(array $options = array())
    {
        parent::save($options);

        \Event::fire(new EventRequirementAdded($options['user'], $options['requirement']));
    }
}