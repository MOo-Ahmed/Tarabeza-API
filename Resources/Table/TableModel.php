<?php

namespace Resources\Table;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class TableModel extends \Illuminate\Database\Eloquent\Model
{
        public $table = "tables";

        public function getAll($restaurant_id){
                $res = Capsule::table('tables')->select('tables.*')
                        ->where('tables.restaurant_id', '=', $restaurant_id)
                        ->get();
            
                return $res;
        }

        public function getAvailable($input){
                $query = "SELECT * FROM tables WHERE restaurant_id = " . $input['restaurant_id'] . " AND id NOT IN 
                ( SELECT DISTINCT table_id FROM nofipayn_restaurant_reservations_system.reservations 
                WHERE DATE_ADD(requested_at, INTERVAL 1 HOUR) > '" . $input['time'] . "' 
                AND ABS(TIMESTAMPDIFF(HOUR,requested_at,'" . $input['time'] . "')) < 1 );" ;
                
                $res = Capsule::select($query);
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