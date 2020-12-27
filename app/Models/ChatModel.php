<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\UserModel;
use App\Models\MessageModel;

class ChatModel extends Model{
    protected $table = 'recent_chats';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user1', 'user2', 'notify', 'last_message_time'];



    function getAllChats($id){
        $recentChats =$this->where('user1', $id)
                            ->orWhere('user2', $id)
                            ->orderBy('last_message_time', 'asc')
                            ->findAll();

        return $this->getChatData($recentChats, $id);
    }


    function getNewChats($id){

        $newChats= $this->where('notify', $id)->findAll();

        return $this->getChatData($newChats, $id);
    }


    function markAsNotified($id){
        $this->where('notify', $id)
            ->set(['notify' => 0])
            ->update();
    }


    function getChatData($chats, $id){
        if(!empty($chats)){
            $data=[];
            $userModel= new UserModel();
            $messageModel= new MessageModel();
            foreach($chats as $key => $value){

                //get second user info
                if($value['user1']!= $id){
                    $userId= $value['user1'];
                }
                else{
                    $userId= $value['user2'];
                }

                $user=$userModel->where('id',$userId)->first();

                //save values of a chat so we can display them
                $data[$key]['id'] = $value['id'];
                $data[$key]['firstname'] = $user['first_name'];
                $data[$key]['lastname'] = $user['last_name'];
                $data[$key]['time']= date("d M, H:i", strtotime($value['last_message_time']));

                if(isset($user['profile_picture'])){
                    $data[$key]['picture'] = $user['profile_picture'];
                }
                else{
                    $data[$key]['picture'] = 'default.jpg';
                }

                //save last chat message
                $lastMessage = $messageModel->where('chat_id', $value['id'])
                                            ->orderBy('time', 'desc')
                                            ->first();

                
                if(empty($lastMessage)){
                    $data[$key]['message']= "No messages";
                }
                else if(strlen($lastMessage['message']) > 100){
                    $data[$key]['message']= substr($lastMessage['message'], 0, 100);
                }
                else{
                    $data[$key]['message']= $lastMessage['message'];
                }
            }
            return json_encode($data);
        }
        else{
            return "no data";
        }
    }

}