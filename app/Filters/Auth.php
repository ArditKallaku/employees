<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){
        //return to login page unauthorized user
        if(! session()->get('email')){
            return redirect()->to('/');
          }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        // Do something here
    }
}