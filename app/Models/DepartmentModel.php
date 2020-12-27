<?php namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model{
  protected $table = 'departments';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'description', 'parent_dept'];




}