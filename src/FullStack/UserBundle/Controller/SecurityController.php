<?php

namespace FullStack\UserBundle\Controller;

use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\OAuthServerBundle\Entity\ClientManager;
use FOS\UserBundle\Model\UserManager;
use FullStack\OAuthServerBundle\Entity\Client;
use FullStack\UserBundle\Entity\User;
use FullStack\Utils\Trt\RequestUtils;
use FullStack\Utils\Trt\ResponseUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    use ResponseUtils,RequestUtils;

    public function postLogin_checkAction(Request $request)
    {

      try{
          $this->validateParams($request,["username","password"],"POST");
          $username = $request->request->get('username');
          $password = $request->request->get('password');
          $grantType= "password";

          $client = $this->getClientOauth();
          $clientId= $client->getPublicId();
          $clientSecret= $client->getSecret();

          $request->request->set('usersame',$username);
          $request->request->set('password',$password);
          $request->request->set('client_id',$clientId);
          $request->request->set('client_secret',$clientSecret);
          $request->request->set('grant_type',$grantType);

          /** @var TokenController $tokenService */
          $tokenService = $this->get('fos_oauth_server.controller.token');

          /** @var Response $response */
          $response = $tokenService->tokenAction($request);
          $token = json_decode($response->getContent(),true);
          if(isset($token['error'])){
              throw new Exception($token['error_description']);
          }

          return $this->getSuccessResponse($token);
          /*
           * use this if want you have users with multiple roles and want to redirect users to different url after login
           $nextUrl = $this->getNextUrl($request);
          return $this->getSuccessResponse(array_merge($token,['next_url'=>$nextUrl]));
          */

      }catch (Exception $e){
          return $this->getFailureResponse($e->getMessage(),$e->getCode());
      }
    }

    private function getClientOauth(){

        /** @var ClientManager $clientManager */
        $clientManager =$this->get('fos_oauth_server.client_manager.default');

        /** @var Client $client */
        $client = $clientManager->findClientBy(array());

        if($client instanceof Client){
            return $client;
        }

        $client = $clientManager->createClient();
        $client->setRedirectUris(['localhost']);
        $client->setAllowedGrantTypes(['password',"refresh_token"]);

        $clientManager->updateClient($client);

        return $client;
    }

    private function getNextUrl(Request $request){

        /** @var UserManager $fosUserManager */
        $fosUserManager =$this->get('fos_user.user_manager');
        $user = $fosUserManager->findUserByUsernameOrEmail($request->query->get('username'));
        if($user->hasRole(User::ROLE_USER)){
            $nextUrl = $this->getApplicationBaseUrl($request);
        }else{
            $nextUrl = $this->getApplicationBaseUrl($request);
        }

        return $nextUrl;
    }

}
