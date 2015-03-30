<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DistListMembers extends Model {

    protected $table = 'dist_list_members';

    public function loadDisMemberList($disId)
    {
        //SELECT t1 . * , t2.name FROM `dist_list_members` t1, users t2WHERE t1.userid = t2.idLIMIT 0 , 30
        $table = $this->table;
        $arrSelect = array($table.'.user_id',$table.'.dist_list_id','users.name');
        $query = DB::table($table)->where('dist_list_id','=',$disId);
        $query->select($arrSelect);
        $query->join('users', 'users.id', '=', $this->table . '.user_id', 'left');
        $data = $query->get();
        return $data;
    }
}