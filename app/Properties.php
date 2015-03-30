<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 16/3/15
 * Time: 2:41 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;


class Properties extends BaseModel {

    protected $table = 'properties';

    protected $fillable = ['agent_id','client_id','location', 'area', 'price','type', 'title', 'description', 'client_email', 'address'];

    protected $rules = [
        'location' => 'required|min:5',
        'area' => 'required',
        'price' => 'required|numeric',
        'title' => 'required',
        'client_id' => 'required|numeric',
        'agent_id' => 'required|numeric',
    ];

    public function save(array $options = array())
    {
        //print_r($options);
        $saved = parent::save($options);

        //\Event::fire(new EventRequirementAdded($options['user'], $options['requirement']));

        return $saved;
    }

}
