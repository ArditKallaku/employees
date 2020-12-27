<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\DepartmentModel;
use App\Models\ChatModel;
use App\Models\MessageModel;

class UserModel extends Model{
  protected $table = 'users';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'first_name', 'last_name', 'email', 'password', 'user_rights', 'department_id', 'profile_picture'];
  protected $beforeInsert = ['beforeInsert'];
  protected $beforeUpdate = ['beforeUpdate'];




  protected function beforeInsert(array $data){
    $data = $this->passwordHash($data);
    date_default_timezone_set('Europe/Tirane');
    $data['data']['created_at'] = date('Y-m-d H:i:s');
    return $data;
  }

  protected function beforeUpdate(array $data){
    $data = $this->passwordHash($data);
    date_default_timezone_set('Europe/Tirane');
    $data['data']['updated_at'] = date('Y-m-d H:i:s');
    return $data;
  }

  protected function passwordHash(array $data){
    if(isset($data['data']['password']))
      $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

    return $data;
  }


  public function getDepartmentName($id){
    $model=new DepartmentModel();
    $dept=$model->where('id', $id)->first();
    if(!isset($dept['description'])){
      return 'None';
    }

    return $dept['description'];
  }

  public function deleteUserChats($id){
    $chatModel= new ChatModel();
    $messageModel= new MessageModel();
    $chats= $chatModel->where('user1', $id)
                      ->orWhere('user2', $id)
                      ->findAll();
    if(!empty($chats)){
      foreach($chats as $chat){
        $messageModel->where('chat_id', $chat['id'])->delete();
        $chatModel->delete($chat['id']);
      }
    }
  }

}