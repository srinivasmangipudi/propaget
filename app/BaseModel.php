<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Watson\Validating\ValidatingTrait;

class BaseModel extends Model {

    use ValidatingTrait;

}
