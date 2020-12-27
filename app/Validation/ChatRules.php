<?php
namespace App\Validation;
use App\Models\UserModel;
use App\Models\ChatModel;

class ChatRules{
    //check if receiver email is not the same as sender email
    public function validReceiver(string $str, string $fields, array $data){
        $model= new UserModel();
        $user= $model->where('id', session()->get('id'))->first();
        if($data['email'] == $user['email']){
            return false;
        }

        return true;
    }

    //check if logged in user has access to a chat
    public function hasAccessToChat(string $str, string $fields, array $data){
        $userId= session()->get('id');
        $model= new ChatModel();
        $builder= $model->builder();

        $result=$builder->where('id', $data['id'])
                        ->groupStart()
                            ->where('user1', $userId)
                            ->orWhere('user2', $userId)
                        ->groupEnd()
                        ->first();
        if(empty($result)){
            return false;
        }

        return true;
    }
}