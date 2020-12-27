<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DepartmentModel;

class Employee extends BaseController{
    
    public function update(){
        $data = [];
		helper(['form']);
		$model = new UserModel();

        $idRules=[
			'id' => [
				'rules' => 'required|is_not_unique[users.id]',
				'label' => 'department id'
			]	
        ];
        
        if($this->validate($idRules)){
            $data['user'] = $model->where('id', $this->request->getVar('id'))->first();

            if ($this->request->getMethod() == 'post') {
                //update profile validation
                $rules = [
                    'firstname' => [
                        'rules' => 'required|min_length[3]|max_length[20]|alpha',
                        'label' => 'First name'
                    ],
                    'lastname' => [
                        'rules' => 'required|min_length[3]|max_length[20]|alpha',
                        'label' => 'Last name'
                    ],
                    'email' => [
                        'rules' => 'required|min_length[5]|max_length[50]|valid_email|is_unique[users.email, email,'.$data['user']['email'].']',
                        'label' => 'email'
                    ],
                    'department' => [
                        'rules' => 'required|is_not_unique[departments.id]',
                        'label' => 'department'
                    ]
                ];
                
                //validate new password if set
                if($this->request->getPost('password') != ''){
                    $rules['password'] = 'required|min_length[8]|max_length[255]';
                    $rules['password_confirm'] = [
                        'rules' => 'matches[password]',
                        'label' => 'Confirm password'
                    ];
                }

                //validate new profile pic if set
                if (!empty($_FILES['profile_pic']['name'])) {
                    $rules['profile_pic']= [
                        'rules' => 'uploaded[profile_pic]|max_size[profile_pic,1024]|ext_in[profile_pic,jpg,png]',
                        'label' => 'Profile picture'
                    ];
                }

                if ($this->validate($rules)) {
                    $newData = [
                        'id' => $this->request->getVar('id'),
                        'first_name' => $this->request->getPost('firstname'),
                        'last_name' => $this->request->getPost('lastname'),
                        'email' => $this->request->getPost('email'),
                        'department_id' => $this->request->getPost('department')
                        ];
                    if($this->request->getPost('password') != ''){
                        $newData['password'] = $this->request->getPost('password');
                    }

                    $id=$this->request->getVar('id');

                    $file=$this->request->getFile('profile_pic');
                    $newData=$this->changePhoto($file, $newData, $id);

                    $model->save($newData);

                    session()->setFlashdata('success', 'Successfuly Updated');
                    return redirect()->to('/employee/update?id='.$id);
                }
                else{
                    $data['validation'] = $this->validator;
                }
            }

            $dModel= new DepartmentModel();
            $data['all_dep'] = $dModel->findAll();
            
            if(!isset($data['user']['profile_picture'])){
                $data['user']['profile_picture']='default.jpg';
            }

            echo view('templates/header', $data);
            echo view('admin/employee');
            echo view('templates/footer');
        }
        else{
            echo "Wrong link";
        }

    }
    

    public function new(){
        $data= [];
        $data['user']=[
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'department_id' => ''
        ];
        helper(['form']);
        $model=new UserModel();
        
        if($this->request->getMethod() == 'post'){
            $rules = [
                'firstname' => [
                    'rules' => 'required|min_length[3]|max_length[20]|alpha',
                    'label' => 'First name'
                ],
                'lastname' => [
                    'rules' => 'required|min_length[3]|max_length[20]|alpha',
                    'label' => 'Last name'
                ],
                'email' => [
                    'rules' => 'required|min_length[5]|max_length[50]|valid_email',
                    'label' => 'email'
                ],
                'department' => [
                    'rules' => 'required|is_not_unique[departments.id]',
                    'label' => 'department'
                ],
                'password' => 'required|min_length[8]|max_length[255]',
                'password_confirm' => [
                    'rules' => 'matches[password]',
                    'label' => 'Confirm Password'
                ]
            ];

            //validate new profile pic if set
            if (!empty($_FILES['profile_pic']['name'])) {
                $rules['profile_pic']= [
                    'rules' => 'uploaded[profile_pic]|max_size[profile_pic,1024]|ext_in[profile_pic,jpg,png]',
                    'label' => 'Profile picture'
                ];
            }

            if ($this->validate($rules)) {
                $newData = [
                    'id' => $this->request->getPost('id'),
                    'first_name' => $this->request->getPost('firstname'),
                    'last_name' => $this->request->getPost('lastname'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'department_id' => $this->request->getPost('department'),
                    'user_rights' => '2' //employee rights
                ];

                if (!empty($_FILES['profile_pic']['name'])) {
                    $file=$this->request->getFile('profile_pic');
                    $newData=$this->changePhoto($file, $newData, '-999');
                }
                

                $model->save($newData);

                session()->setFlashdata('success', 'Successfuly Updated');
                return redirect()->to('/admin');
            }
            else{
                $data['validation'] = $this->validator;
            }
        }

        $dModel=new DepartmentModel();
        $data['all_dep'] = $dModel->findAll();
		echo view('templates/header', $data);
        echo view('admin/newEmployee');
        echo view('templates/footer');
    }

    public function delete(){
        if ($this->request->getMethod() == 'post') {
			$rules=[
				'id' => [
					'rules' => 'required|is_not_unique[users.id]',
					'label' => 'user'
				],
			];
			if($this->validate($rules)) {

				$id=$this->request->getPost('id');
                $model= new UserModel();
                $user =$model->where('id', $id)->first();
                //delete profile picture from server if employee has one
                if(isset($user['profile_picture'])){
                    unlink('./uploads/images/'.$user['profile_picture']);
                }
                //delete all chats
                $model->deleteUserChats($id);
                //delete user
                $model->delete($id);
				echo "success";
			}
			else{
				echo "Couldn't delete department";
			}
		}
    }


    //add new profile pic to server and delete the old one
	private function changePhoto($file,array $newData, $id){
		if($file->isValid() && !$file->hasMoved()){
			$newName=$file->getRandomName();
            $file->move('./uploads/images',$newName);
            
            $model= new UserModel();
            $user= $model->where('id', $id)->first();
            if(isset($user['profile_picture'])){
                unlink('./uploads/images/'.$user['profile_picture']);
            }

			$newData['profile_picture']=$newName;
		}

		return $newData;
    }
}