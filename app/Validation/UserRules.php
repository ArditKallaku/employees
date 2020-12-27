<?php
namespace App\Validation;
use App\Models\UserModel;
use App\Models\DepartmentModel;

class UserRules{
  //checks if user credentials are correct
  public function validateUser(string $str, string $fields, array $data){
    $model = new UserModel();
    $user = $model->where('email', $data['email'])->first();

    if(!$user)
      return false;

    return password_verify($data['password'], $user['password']);
  }

  //checks if parent department is valid
  public function validParent(string $str, string $fields, array $data){
    if($data['parent'] == 0){
      return true;
    }
    $model=new DepartmentModel();
    $dep=$model->where('id', $data['parent'])->first();
    if($dep){
      return true;
    }

    return false;
  }

}