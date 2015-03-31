<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DistListMembers extends Model {

    protected $table = 'dist_list_members';

    public function loadDisMemberList($disId)
    {
        $table = $this->table;
        $arrSelect = array($table.'.user_id',$table.'.dist_list_id','users.name');
        $query = DB::table($table)->where('dist_list_id','=',$disId);
        $query->select($arrSelect);
        $query->join('users', 'users.id', '=', $this->table . '.user_id', 'left');
        $data = $query->get();
        return $data;
    }
}