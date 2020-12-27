<?php namespace App\Models;

use CodeIgniter\Model;

class EmployeesModel extends Model{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'first_name', 'last_name', 'email', 'department_id'];

    function allEmployeesCount($depId){
        $builder = $this->builder();
        return $builder
                ->where('department_id', $depId)
                ->where('user_rights', '2')
                ->countAllResults();
    }

    function allEmployees($depId, $limit, $start, $col, $dir){
        $builder = $this->builder();
        $query = $builder
                ->where('department_id', $depId)
                ->where('user_rights', '2')
                ->limit($limit,$start)
                ->orderBy($col,$dir)
                ->get()
                ->getResultArray();

        if(!empty($query)){
            return $query;
        }
        else{
            return null;
        }
    }

    function employeeSearch($depId, $limit, $start, $search, $col, $dir){
        $builder = $this->builder();
        $query = $builder
                ->where('department_id', $depId)
                ->where('user_rights', '2')
                ->like('first_name',$search)
                ->orLike('last_name',$search)
                ->orLike('email',$search)
                ->limit($limit,$start)
                ->orderBy($col,$dir)
                ->get()
                ->getResultArray();

        if(!empty($query)){
            return $query;
        }
        else{
            return null;
        }
    }

    function employeeSearchCount($depId, $search){
        $builder = $this->builder();
        $total = $builder
                ->where('department_id', $depId)
                ->where('user_rights', '2')
                ->like('first_name',$search)
                ->orLike('last_name',$search)
                ->orLike('email',$search)
                ->countAllResults();
        return $total;
    }

}