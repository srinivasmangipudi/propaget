<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DistListMembers extends Model {

    protected $table = 'dist_list_members';

    public function loadDisMemberList($disId)
    {
        //SELECT t1 . * , t2.name FROM `dist_list_members` t1, users t2WHERE t1.userid = t2.idLIMIT 0 , 30
        $table = $this->table;
        $arrSelect = array($table.'.userid',$table.'.distListId','users.name');
        $query = DB::table($table)->where('distListId','=',$disId);
        $query->select($arrSelect);
        $query->join('users', 'users.id', '=', $this->table . '.userid', 'left');
        $data = $query->get();
        return $data;
    }
}