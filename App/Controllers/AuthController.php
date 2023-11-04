<?php

namespace App\Controllers;

//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

    public function autenticar(){

        $usuario = Container::getModel('Usuario');

        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', md5( $_POST['senha']));

        $usuario->autenticar();

        if($usuario->__get('id') != '' && $usuario->__get('nome')){
           
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] =  $usuario->__get('nome');

            header('location: /timeline');

        } else{
            header('location: /?login=erro');

        }


    }

    public function sair(){

        session_start();
        session_destroy();

        

        // Remover cookie relacionado ao usuário
        $cookie_name = "usuario"; // Substitua pelo nome do cookie
        $cookie_expire = time() - 3600; // Define o tempo de expiração no passado para expirar imediatamente
        $cookie_path = "/"; // Mesmo caminho usado ao definir o cookie original
        $cookie_domain = ""; // Domínio do cookie, se necessário
        $cookie_secure = false; // Se necessário
        $cookie_httponly = true; // Se necessário

        setcookie($cookie_name, "", $cookie_expire, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);

        header('location: /');

    }
}


?>