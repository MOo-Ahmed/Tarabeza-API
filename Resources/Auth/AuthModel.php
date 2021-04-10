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

  public function findByEmail($_email)
  {
    $res = AuthModel::where("users.email", "=", $_email)->first();

    return $res;
  }

  public function insert($_input)
  {
    return AuthModel::create($_input);
  }
}