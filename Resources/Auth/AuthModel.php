<?php

namespace Resources\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

class AuthModel extends \Illuminate\Database\Eloquent\Model
{
  public $table = "users";
  protected $fillable = ['email', "phone_number", "password", "first_name", "last_name", "gender", "date_of_birth", "role", "is_active", "confirmation_code"];
  protected $hidden = array("password", "updated_at", "created_at");
  private $keys = array(0 => 'i', 1 => 'C', 2 => 'e', 3 => 'H', 4 => 'Y', 5 => 'J', 6 => 'm', 7 => '@', 8 => 's', 9 => 'p');

  public function findByEmail($_email)
  {
    $res = AuthModel::where("users.email", "=", $_email)->first();
    return $res;
  }

  public function findByID($_id)
  {
    $res = AuthModel::where("users.id", "=", $_id)->first();

    return $res;
  }

  public function insert($_input)
  {
    $user = Capsule::table('users')->insertGetId($_input);
    if($user){
      $res = Capsule::table('customers')->insert(array(
        "user_id" => $user
      ));
      return $res;
    }
    else{
      return "DB Failure";
    }
    
  }

  public function upd($_input)
  {
    return AuthModel::where("users.id", "=", $_input['id'])->update($_input);
  }

  public function updatePassword($_input){
    return AuthModel::where("users.id", "=", $_input['id'])
                    ->update(array('password' => $_input['new_password']));
  }

  public function cipher($id){
    $_id = $id ;
    $word = "kiupa" ;
    $coded = "" ;
    while($_id > 0){
      $digit = $_id % 10 ;
      $coded = $coded . $keys[$digit] ; 
      $_id /= 10 ;
    }
    $word = $word + strrev($coded);
    return $word;
  }

  public function decipher($word){
    $id = 0 ;
    $decoded = "" ;
    for($i = 5; $i < strlen($word); $i++){
        $idx = array_search($word[$i], $this->keys);
        $decoded = $decoded . $idx;
    }
    $id = (int)$decoded;
    return $id;
  }

  public function insertStaff($_input){
    $decoded_rid = $this->decipher($_input['restaurant_id']);
    $res = Capsule::table('restaurants')->select('restaurants.*')
          ->where('restaurants.id', '=', $decoded_rid)
          ->get();

    if($res){

      $data = array(
        "email"                 => $_input["email"],
        "phone_number"          => $_input["phone_number"],
        "password"              => $_input["password"],
        "first_name"            => $_input["first_name"],
        "last_name"             => $_input["last_name"],
        "gender"                => $_input["gender"],
        "date_of_birth"         => $_input["date_of_birth"],
        "role"                  => $_input["role"]
      );
      $user = Capsule::table('users')->insertGetId($data);
      if($user){
        $res = Capsule::table('staff')->insert(array(
          "user_id" => $user, 
          "restaurant_id" => $decoded_rid
        ));
        return $res;
      }
      else{
        return "DB Failure";
      }
      
    }
    //$user = insert($_input);
    
  }
}