<?php

namespace App\Controllers;

//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

		$this->render('index');
	}

	public function inscreverse() {

		$this->view->usuarios = array(
			'nome' => '',
			'email' => '',
			'senha' => '',
		);
		
		$this->view->errocadastro = false;

		$this->render('inscreverse');
	}
	
	public function registrar() {

		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		if($usuario->validarcadastro() && count($usuario->getusuarioporemail()) == 0){

			$usuario->salvar();
			$this->render('cadastro');

		}else{

			$this->view->usuarios = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha'],
			);

			$this->view->errocadastro = true;

			$this->render('inscreverse');
		}
		
	}

}


?>