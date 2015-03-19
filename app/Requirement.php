<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\EventRequirementAdded;
use Illuminate\Support\Facades\Log;

class Requirement extends BaseModel {

    protected $table = 'requirements';

    protected $fillable = ['agentId','clientId','clientEmail','title','description','location', 'area', 'range', 'price', 'priceRange','type'];

    protected $rules = [
        'location' => 'required|min:5',
        'area' => 'required',
        'price' => 'required|numeric'
    ];

    protected $validationMessages = [
        'required' => 'A :attribute is required',
        'location.min' => 'A Location should be longer. Min 3 characters'
    ];

    public function save(array $options = array())
    {
        Log::error('I AM IN SAVE');
        parent::save($options);

        //\Event::fire(new EventRequirementAdded($options['user'], $options['requirement']));
    }
}