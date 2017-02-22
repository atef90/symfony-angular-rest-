<?php

namespace FullStack\UserBundle\Controller;


use FullStack\UserBundle\Entity\User;
use FullStack\Utils\Trt\ResponseUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    use ResponseUtils;

    public function getAuthenticatedAction(Request $request)
    {

      try{
          /** @var User $user */
          $user = $this->getUser();

          return $this->getSuccessResponse([
              'username'=>$user->getUsername(),
              "email"=>$user->getEmail()
          ]);

      }catch (Exception $e){
          return $this->getFailureResponse($e->getMessage(),$e->getCode());
      }
    }
}
