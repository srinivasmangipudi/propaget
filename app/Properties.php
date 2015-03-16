<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 16/3/15
 * Time: 2:41 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Properties extends Model {

    protected $table = 'properties';

    public function save(array $options = array())
    {
        parent::save($options);
    }

}
