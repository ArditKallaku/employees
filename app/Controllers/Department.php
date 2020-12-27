<?php namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\UserModel;

class Department extends BaseController{

    public function update(){
		$data = [];
		helper(['form']);

		$idRules=[
			'id' => [
				'rules' => 'required|is_not_unique[departments.id]',
				'label' => 'department id'
			]	
		];

		if($this->validate($idRules)){
			$model=new DepartmentModel();
			$id=$this->request->getVar('id');

			if($this->request->getMethod() == 'post'){
				$rules=[
					'name' => [
						'rules' => 'required|min_length[2]|max_length[50]',
						'label' => 'department name'
					],
					'parent' => [
						'rules' => 'required|differs[id]|validParent[parent]',
						'label' => 'parent department',
						'errors' => [
							'validParent' => 'Invalid parent value'
						]
					],
				];

				if($this->validate($rules)) {
				
					$updatedDepartment=[
						'description' => $this->request->getPost('name'),
						'parent_dept' => $this->request->getPost('parent'),
					];

					$model->update($id,$updatedDepartment);
					session()->setFlashdata('success', 'Successfuly Updated');
				}
				else{
					$data['validation']= $this->validator;
				}
			}

			$data['department'] = $model->where('id',$id)->first();
			$data['all_dep'] = $model->findAll();

			echo view('templates/header', $data);
			echo view('admin/department');
			echo view('templates/footer');
		}
		else{
			echo "Wrong link";
		}
    }


    public function new(){
        $data=[];

		$model=new DepartmentModel();

		if ($this->request->getMethod() == 'post') {
			$rules=[
				'dep_name' => [
					'rules' => 'required|min_length[2]|max_length[50]',
					'label' => 'department name'
				],
				'parent' => [
					'rules' => 'required|validParent[parent]',
					'label' => 'parent department',
					'errors' => [
						'validParent' => 'Invalid parent value'
					]
				],
			];

			if($this->validate($rules)) {
				$newDep=[
					'description' => $this->request->getPost('dep_name'),
					'parent_dept' => $this->request->getPost('parent'),
				];
				$model->insert($newDep);
				return redirect()->to('/admin');
			}
			else{
				$data['validation'] = $this->validator;
			}
		}

		$data['all_dep'] = $model->findAll();
		echo view('templates/header', $data);
		echo view('admin/newDepartment');
		echo view('templates/footer');
	}
	
	//checks if a department has subdepartments
	public function hasChilds(){
		if ($this->request->getMethod() == 'post') {
			$rules=[
				'id' => [
					'rules' => 'required|is_not_unique[departments.id]',
					'label' => 'department id'
				],
			];

			if($this->validate($rules)) {
				$model=new DepartmentModel();
				$dep=$model->where('parent_dept', $this->request->getPost('id'))->first();
				if($dep){
				echo "yes";
				}
			}
		}
	}

	public function delete(){
		if ($this->request->getMethod() == 'post') {
			$rules=[
				'id' => [
					'rules' => 'required|is_not_unique[departments.id]',
					'label' => 'department id'
				],
			];
			if($this->validate($rules)) {

				$id=$this->request->getPost('id');

				$db = \Config\Database::connect();
				$builder = $db->table('departments');

				$arr = [];
				$arr = $this->getAllChilds($builder,$id,$arr);

				$dModel=new DepartmentModel();
				$userModel= new UserModel();
				foreach($arr as $el){
					$userModel->where('department_id', $el)->delete();
					$dModel->delete($el);
				}
				echo "success";
			}
			else{
				echo "Couldn't delete department";
			}
		}
	}

	//recursive method to get all childs of a department
	private function getAllChilds($builder, $id, $arr){
		$builder->select('id')->where('parent_dept', $id);
		$query = $builder->get();
		foreach($query->getResultArray() as $row){
			$arr = $this->getAllChilds($builder, $row['id'], $arr);
		}
		$arr[] = $id;
		return $arr;
	}

}