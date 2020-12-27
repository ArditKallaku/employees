<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){
        //return to login page unauthorized admin
        if(session()->get('user_rights') != 1){
            return redirect()->to('/profile');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        // Do something here
    }
}