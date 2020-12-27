<?php namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserModel;
use App\Models\MessageModel;


class Chat extends BaseController{
    public function index(){
        $data=[];
        echo view('templates/header', $data);
		echo view('messages');
		echo view('templates/footer');
    }

    //sending a new message
    public function new(){
        if ($this->request->getMethod() == 'post') {
            $userModel= new UserModel();
            $sender= $userModel->where('id', session()->get('id'))->first();

            $rules=[
                'email' => 'required|valid_email|is_not_unique[users.email]|validReceiver[email]'
            ];

            if($this->validate($rules)){
                $email= $this->request->getPost('email');

                $receiver= $userModel->where('email', $email)->first();

                $senderId= session()->get('id');

                //check if chat already exists
                $created= $this->isCreated($senderId, $receiver['id']);
                if($created){
                    $chatId= $created;
                }
                else{ //if not, create and return id
                    date_default_timezone_set('Europe/Tirane');
                    $currentTime = date('Y-m-d H:i:s');

                    $data=[
                        'user1' => $senderId,
                        'user2' => $receiver['id'],
                        'notify' => $receiver['id'],
                        'last_message_time' => $currentTime
                    ];

                    $chatModel= new ChatModel();
                    $chatModel->insert($data);

                    //return new created chat ID
                    $chat=$chatModel->where('user1', $senderId)
                                    ->where('user2', $receiver['id'])
                                    ->first();

                    $chatId= $chat['id'];
                }

                echo $chatId;
            }
            else{
                echo "incorrect email";
            }
        }
    }

    //retrieve all recentChats
    public function recents(){
        if ($this->request->getMethod() == 'post') {
            $id= session()->get('id');
            $model= new ChatModel();

            echo $model->getAllChats($id);

            $model->markAsNotified($id);
        }
    }

    //retrieve all messages of a chat
    public function messages(){
        if ($this->request->getMethod() == 'post') {
            $rules=[
                'id' => 'required|is_not_unique[recent_chats.id]|hasAccessToChat[id]'
            ];

            if($this->validate($rules)){
                $chatId= $this->request->getPost('id');

                $model= new MessageModel();

                echo $model->getAllChatMessages($chatId);

                $model->markMessagesAsRead($chatId);
            }
        }
    }

    //send a message to a chat
    public function send(){
        if ($this->request->getMethod() == 'post') {
            $rules=[
                'id' => 'required|is_not_unique[recent_chats.id]|hasAccessToChat[id]',
                'message' => 'required|min_length[1]'
            ];

            if($this->validate($rules)){
                $chatId=$this->request->getPost('id');
                date_default_timezone_set('Europe/Tirane');
                $currentTime = date('Y-m-d H:i:s');
                $data=[
                    'chat_id' => $chatId,
                    'sent_by' => session()->get('id'),
                    'message' => $this->request->getPost('message'),
                    'time' => $currentTime,
                    'opened' => '0'
                ];

                //save message
                $model= new MessageModel();
                $model->save($data);

                //update chat details
                $model= new ChatModel();
                $chat=$model->where('id', $chatId)->first();
                $user= session()->get('id');
                if($chat['user1'] != $user){
                    $otherUser= $chat['user1'];
                }
                else{
                    $otherUser= $chat['user2'];
                }

                $newChatData=[
                    'last_message_time' => $currentTime,
                    'notify' => $otherUser
                ];
                $model->update($chatId, $newChatData);

                //return message time
                echo date("d M, H:i", strtotime($currentTime));
            }
            else{
                echo "error";
            }
        }
        else{
            echo "error";
        }
    }

    //retrieve new chats
    public function newUpdates(){
        if($this->request->getMethod() == 'post') {
            $id= session()->get('id');

            $model= new ChatModel();
            echo $model->getNewChats($id);
            $model->markAsNotified($id);
        }
    }

    //retrieve all unread messages of a chat
    public function received(){
        if($this->request->getMethod() == 'post') {
            $rules=[
                'id' => 'required|is_not_unique[recent_chats.id]|hasAccessToChat[id]',
            ];

            if($this->validate($rules)){
                $chatId= $this->request->getPost('id');

                $model= new MessageModel();
                echo $model->getChatUnreadMessages($chatId);
                $model->markMessagesAsRead($chatId);
            }
            else{
                echo "no data";
            }
        }
    }


    //check if user2 has created chat
    private function isCreated($user1, $user2){
        $model= new ChatModel();
        $builder= $model->builder();
        $result=$builder->groupStart()
                            ->where('user1', $user1)
                            ->where('user2', $user2)
                        ->groupEnd()
                        ->orGroupStart()
                            ->where('user1', $user2)
                            ->where('user2', $user1)
                        ->groupEnd()
                        ->first();

        if(!empty($result)){
            return $result['id'];
        }
        return false;
    }
}