<?php

namespace Amir_nadjib\Todo_list\Controllers;

use Amir_nadjib\Todo_list\Models\User;
use PDO;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Views\Twig;

class LoginController extends HomeController
{


    public function getLogin (RequestInterface $request, ResponseInterface $response) {

        return $this->twig->render($response, 'login.tpl');
    }
    public function postLogin (RequestInterface $request, ResponseInterface $response) {

         $authEmail = $_POST['email'] ;
         $authPassword = $_POST['password'] ;

         $users = $this->pdo->prepare('select * from users where email = :mail ');
         $users->bindParam(':mail', $authEmail, PDO::PARAM_STR);
         $users->execute() ;
         if($users->rowCount() != 1)
         {
             // handle error later
             $res = json_encode("no") ;
             $response->getBody()->write($res) ;
             return $response->withStatus(200);
         }

        $user = $users->fetch();

        if($user['password']== $authPassword)
         {
         // redirect to tasks page
             $_SESSION['user'] =$authEmail ;
             $_SESSION['userId'] =$user['id'] ;
             $res = array($user['id']);
             $res = json_encode($res) ;
             $response->getBody()->write($res) ;
             return $response->withStatus(200);
         }
        $res = json_encode("no") ;
        $response->getBody()->write($res) ;
        return $response->withStatus(200);

    }

    public function signOut (RequestInterface $request, ResponseInterface $response) {

        session_reset();
        return $response->withStatus(200);

    }
}
