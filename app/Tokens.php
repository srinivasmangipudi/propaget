<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tokens extends Model{

protected $table = 'tokens';

protected $fillable = ['api_token', 'client', 'user_id', 'expires_on'];


public function scopeValid($expires_on)
{
//return !Carbon\Carbon::createFromTimeStamp(strtotime($this->expires_on))->isPast();
    $carbon = new Carbon;
    return !$carbon->createFromTimeStamp(strtotime($expires_on))->isPast();
}

public function user()
{
return $this->belongsTo('User','user_id');
}
}