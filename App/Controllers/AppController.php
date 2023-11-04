<?php

namespace App\Controllers;

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline(){

    
        $this->validaautenticacao();
            // RECUPERAR TWEET
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario', $_SESSION['id']);
            $tweets = $tweet->getAll();
    
            $usuario = $_SESSION['nome'];
    
            // Gerar um novo ID de sessão
            session_regenerate_id(true);
    
            // Definir algum valor na nova sessão
            $_SESSION['nome'] = $usuario;
    
            // Criar um cookie com o nome do usuário
            $expire = time() + 3600; // Expira em uma hora a partir do momento atual
            $path = "/"; // Disponível em todo o site
            $domain = ""; // Preencha com o domínio correto se necessário
            $secure = false; // Não limitado a conexões seguras
            $httponly = true; // Apenas acessível via HTTP, não JavaScript
    
            setcookie("usuario", $usuario, $expire, $path, $domain, $secure, $httponly);
    
            // Agora você pode renderizar a view
            $this->view->tweet = $tweets;

            $usuario = Container::getModel('usuario');
            $usuario->__set('id', $_SESSION['id']);

            $this->view->info_usuario = $usuario->getinformacoes();
            $this->view->total_tweet = $usuario->gettotaltweet();
            $this->view->total_seguindo = $usuario->gettotalseguindo();
            $this->view->total_seguidores = $usuario->gettotalseguidores();



            $this->render('timeline');
        
    }
    
    public function tweet(){

        $this->validaautenticacao();

          $tweet = Container::getModel('Tweet');

          $tweet->__set('tweet', $_POST['tweet']);
          $tweet->__set('id_usuario', $_SESSION['id']);

          $tweet->salvar();

          header('location: /timeline');
    }

    public function validaautenticacao(){

        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' && !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
            header('location: /?login=erro');
        }

    }

    public function quem_seguir(){
        $this->validaautenticacao();

        $pesquisarPor = isset($_GET['nome']) ? $_GET['nome'] : '';

        $usuarios = array();

        if($pesquisarPor != ''){
           $usuario = Container::getModel('Usuario');
           $usuario->__set('nome', $pesquisarPor);
           $usuario->__set('id', $_SESSION['id']);
           $usuarios = $usuario->getall();
        }

        $this->view->usuarios = $usuarios;

        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getinformacoes();
        $this->view->total_tweet = $usuario->gettotaltweet();
        $this->view->total_seguindo = $usuario->gettotalseguindo();
        $this->view->total_seguidores = $usuario->gettotalseguidores();

        $this->render('quemSeguir');
    }

    public function acao(){
        $this->validaautenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_segindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);
      
        if($acao == 'seguir'){
            $usuario->seguirusuario($id_usuario_segindo);
        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixardeseguir($id_usuario_segindo);
        }

        header('location: /quem_seguir');
    }

    public function delete(){
        $this->validaautenticacao();

        $deleteporid = isset($_GET['id']) ? $_GET['id'] : '';

        $delete = Container::getModel('Tweet');
        $delete->__set('id', $_GET['delete']);

        $delete->deletarTweet();

        header('location: /timeline');

    }
}


?>