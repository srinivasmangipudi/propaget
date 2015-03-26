<?php namespace App;

use App\Events\EventRequirementAdded;
use Illuminate\Support\Facades\Log;

class Requirement extends BaseModel {

    protected $table = 'requirements';

    protected $fillable = ['agentId','clientId','clientEmail','title','description','location', 'area', 'range', 'price', 'priceRange','type'];

    protected $rules = [
        'location' => 'required|min:5',
        'area' => 'required',
        'price' => 'required|numeric',
        'title' => 'required',
        'clientId' => 'required|numeric',
        'agentId' => 'required|numeric',
    ];

    protected $validationMessages = [
        'required' => 'A :attribute is required',
        'location.min' => 'A Location should be more then 5 characters'
    ];

    public function save(array $options = array())
    {

        //return parent::save();
        $saved = parent::save();

        //\Event::fire(new EventRequirementAdded($options['user'], $options['requirement']));

        return $saved;
    }
}