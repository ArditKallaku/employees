<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UsersCheck implements FilterInterface{
    
    public function before(RequestInterface $request, $arguments = null){
        //redirect url domain/users to domain/ , domain/users/profile to domain/profile, etc
        // If segment 1 == users
        //we have to redirect the request to the second segment
        $uri = service('uri');
        if($uri->getSegment(1) == 'users'){
          if($uri->getSegment(2) == '')
            $segment = '/';
          else
            $segment = '/'.$uri->getSegment(2);

          return redirect()->to($segment);

        }

        //block access to pages using /index
        $last = $uri->getTotalSegments();
        $lastSegment = $uri->getSegment($last);
        if($lastSegment == 'index'){
            
            return redirect()->to('/profile');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        // Do something here
    }
}