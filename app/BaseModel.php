<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Watson\Validating\ValidatingTrait;

class BaseModel extends Model {

    use ValidatingTrait;

    protected $throwValidationExceptions = true;

    public function save(array $options = array())
    {
        /* Code to fix bug for Save during testing */
        $config = Config::get('app.fwtestmode');

        if ($config == 'true') {
            parent::flushEventListeners();
            parent::boot();
        }

        return parent::save($options);
    }

}
