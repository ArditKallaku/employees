<?php namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'chat_id', 'sent_by', 'message', 'time', 'opened'];


    function getAllChatMessages($chatId){
        $userId= session()->get('id');
        $messages= $this
                            ->where('chat_id', $chatId)
                            ->orderBy('time', 'asc')
                            ->findAll();
        if(!empty($messages)){
            return $this->messageToJson($messages, $chatId, $userId);
        }
        else{
            return "no data";
        }
    }


    function getChatUnreadMessages($chatId){
        $userId= session()->get('id');
        $messages= $this->where("chat_id", $chatId)
                                ->where("sent_by !=", $userId)
                                ->where("opened", '0')
                                ->findAll();
                
        if(!empty($messages)){
            return $this->messageToJson($messages, $chatId, $userId);
        }
        else{
            return "no data";
        }
    }

    function markMessagesAsRead($chatId){
        $userId= session()->get('id');

        $this->where("chat_id", $chatId)
            ->where("sent_by !=", $userId)
            ->where("opened", 0)
            ->set(['opened' => 1])
            ->update();
    }

    protected function messageToJson($messages, $chatId, $userId){
        $data=[];

            $picture= $this->getOtherUserPicture($chatId);
                    
            foreach($messages as $key => $value){
                $data[$key]['text']= $value['message'];
                $data[$key]['time']= date("d M, H:i", strtotime($value['time']));
                $data[$key]['opened']= $value['opened'];

                if($value['sent_by'] == $userId){
                    $data[$key]['display']= 'right';
                }
                else{
                    $data[$key]['display']= 'left';
                    $data[$key]['picture']= $picture;
                }

            }

            return json_encode($data);
    }

    protected function getOtherUserPicture($id){
        $model= new ChatModel();
        $chat=$model->where('id', $id)->first();
        $loggedInUser= session()->get('id');
        if($chat['user1'] != $loggedInUser){
            $secondUser=$chat['user1'];
        }
        else{
            $secondUser=$chat['user2'];
        }
    
        $userM= new UserModel();
        $user= $userM->where('id', $secondUser)->first();
        if(!isset($user['profile_picture'])){
            return "default.jpg";
        }
        return $user['profile_picture'];
    }
}