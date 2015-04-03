<?php namespace App;

/**
 * Created by PhpStorm.
 * User: urmila
 * Date: 3/4/15
 * Time: 2:09 PM
 */
use App\Events\EventRequirementAdded;
use Illuminate\Support\Facades\Log;

class UserProfile extends BaseModel {

    protected $table = 'users_profile';

    protected $fillable = ['user_id','uid','user_type','data'];

    protected $rules = [];


}