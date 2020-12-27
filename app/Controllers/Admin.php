<?php namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\EmployeesModel;

class Admin extends BaseController{

	public function index(){
		$data = [];

		echo view('templates/adminHeader', $data );
		echo view('admin/dashboard');
		echo view('templates/footer');
	}


	public function getDepartmentsTree(){
		if ($this->request->getMethod() == 'post') {
			$data= [];
			$parentDept='0';
			$model= new DepartmentModel();
			//check if we have at least 1 department
			$departments= $model->first();
			if(isset($departments)){
				$data = $this->getMembersTree($parentDept);
			}
			else{
				$data[0]=["id"=>"0","name"=>"No Departments","text"=>"No departments found","nodes"=>[]];
			}

			echo json_encode(array_values($data));
		}
	}


	private function getMembersTree($parent){
		$row1 = [];
		$model=new DepartmentModel();
        $row = $model->asArray()->where('parent_dept',$parent)->findAll();

        foreach($row as $key => $value){
        	$id = $value['id'];
        	$row1[$key]['id'] = $value['id'];
        	$row1[$key]['name'] = $value['description'];
			$row1[$key]['text'] = $value['description'];
			$row1[$key]['href'] = 'javascript:showEmployees('.$value['id'].',"'.$value['description'].'")';
        	$row1[$key]['nodes'] = array_values($this->getMembersTree($value['id']));
        }
  
        return $row1;
	}

	//data to display in datatable
	public function employees(){
		$columns = array( 
			0 =>'first_name', 
			1 =>'last_name',
			2=> 'email',
			3=> 'actions',
			4=> 'id',
		);

		$depId=	$this->request->getPost('id');
		$limit = $this->request->getPost('length');
		$start = $this->request->getPost('start');
		$order = $columns[$this->request->getPost('order')[0]['column']];
		$dir = $this->request->getPost('order')[0]['dir'];

		$model= new EmployeesModel();

		$totalData = $model->allEmployeesCount($depId);
	
		$totalFiltered = $totalData; 

		if(empty($this->request->getPost('search')['value'])){            
			$employees = $model->allEmployees($depId, $limit, $start, $order, $dir);
		}
		else {
			$search = $this->request->getPost('search')['value']; 

			$employees =  $model->employeeSearch($depId, $limit, $start, $search, $order, $dir);

			$totalFiltered = $model->employeeSearchCount($depId, $search);
		}

		$data = array();
		if(!empty($employees)){
			foreach ($employees as $employee){
				$actions="<a href='/employee/update?id=".$employee['id']."'><i class='fas fa-pencil-alt'></i></a>
						<a href='javascript: deleteUser(".$employee['id'].")'><i class='fas fa-user-times'></i></a>";
				$nestedData['first_name'] = $employee['first_name'];
				$nestedData['last_name'] = $employee['last_name'];
				$nestedData['email'] = $employee['email'];
				$nestedData['actions'] = $actions;

				$data[] = $nestedData;

			}
		}

		$json_data = array(
			"draw"            => intval($this->request->getPost('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalFiltered), 
			"data"            => $data   
			);

		echo json_encode($json_data);
	}

	

	//--------------------------------------------------------------------

}