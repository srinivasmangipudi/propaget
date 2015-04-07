<?php namespace App;

use App\Events\EventRequirementAdded;
use Illuminate\Support\Facades\Log;

class Requirement extends BaseModel {

    protected $table = 'requirements';

    protected $fillable = ['agent_id','client_id','client_email','title','description','location', 'area', 'range', 'price', 'price_range','type'];

    protected $rules = [
        'location' => 'required|min:5',
        'area' => 'required',
        'price' => 'required|numeric',
        'title' => 'required',
        'client_id' => 'required|numeric',
        'agent_id' => 'required|numeric',
    ];

    protected $validationMessages = [
        'required' => 'A :attribute is required',
        'location.min' => 'A Location should be more then 5 characters'
    ];

    public function save(array $options = array())
    {
        $saved = parent::save();

        watchdog_message('New requirement was added.', 'normal', ['requirement' => $options['requirement']]);

        \Event::fire(new EventRequirementAdded($options['user'], $options['requirement']));

        return $saved;
    }
}