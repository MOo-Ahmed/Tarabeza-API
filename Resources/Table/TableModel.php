<?php

namespace Resources\Table;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class TableModel extends \Illuminate\Database\Eloquent\Model
{
	public $table = "tables";

        public function getAllTables($restaurant_id){
                $res = Capsule::table('tables')->select('tables.*')
                        ->where('tables.restaurant_id', '=', $restaurant_id)
                        ->get();
            
                return $res;
        }

        public function toggle($restaurant_id, $table_number, $state){
                $res = Capsule::table('tables')
                        ->where('tables.restaurant_id', '=', $restaurant_id)
                        ->where('tables.number', '=', $table_number)
                        ->update(array('is_reserved' => $state));
                
                return $res;
        }
    

}