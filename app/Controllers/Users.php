<?php namespace App\Controllers;

use App\Models\UserModel;


class Users extends BaseController{

	public function index(){
		$data=[];
		helper(['form']);

		if ($this->request->getMethod() == 'post') {
			//login validation
			$rules = [
				'email' => 'required|min_length[5]|max_length[50]|valid_email',
				'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Incorrect Email/Password'
				]
			];

			if ($this->validate($rules, $errors)) {
				$model = new UserModel();
				$user = $model->where('email', $this->request->getVar('email'))->first();

				$this->setUserSession($user);

				if($user['user_rights']==2){
					return redirect()->to('profile');
				}

				return redirect()->to('admin');
			}
			else{
				$data['validation'] = $this->validator;
			}
		}

		echo view('login',$data);
		echo view('templates/footer');
	}


	private function setUserSession($user){
		$data = [
			'id' => $user['id'],
			'firstname' => $user['first_name'],
			'lastname' => $user['last_name'],
			'email' => $user['email'],
			'user_rights' => $user['user_rights'],
			'profile_pic' => $user['profile_picture'],
		];

		session()->set($data);
		return true;
	}



	public function profile(){
		$data = [];
		helper(['form']);
		$model = new UserModel();

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
					'id' => session()->get('id'),
					'first_name' => $this->request->getPost('firstname'),
					'last_name' => $this->request->getPost('lastname'),
					];
				if($this->request->getPost('password') != ''){
					$newData['password'] = $this->request->getPost('password');
				}

				$file=$this->request->getFile('profile_pic');
				$newData=$this->changePhoto($file, $newData);

				$model->save($newData);

				session()->setFlashdata('success', 'Successfuly Updated');
				return redirect()->to('/profile');
			}
			else{
				$data['validation'] = $this->validator;
			}
		}

		$data['user'] = $model->where('id', session()->get('id'))->first();
		$data['user']['department']=$model->getDepartmentName($data['user']['department_id']);
		if(!isset($data['user']['profile_picture'])){
			$data['user']['profile_picture']='default.jpg';
		}

		echo view('templates/header', $data);
		echo view('profile');
		echo view('templates/footer');
	}

	//add new profile pic to server and delete the old one
	private function changePhoto($file,array $newData){
		if($file->isValid() && !$file->hasMoved()){
			$newName=$file->getRandomName();
			$file->move('./uploads/images',$newName);
			unlink('./uploads/images/'.session()->get('profile_pic'));
			session()->set('profile_pic',$newName);
			$newData['profile_picture']=$newName;
		}

		return $newData;
	}


	public function logout(){
		session()->destroy();
		return redirect()->to('/');
	}

	//--------------------------------------------------------------------

}
