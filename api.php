<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Illuminate\Database\Capsule\Manager as Capsule;


require 'vendor/autoload.php';

$app = new \Slim\App(
    ['settings'=> [
        'displayErrorDetails' => true
    ]]
);

$container = $app->getContainer();
$container['db'] = function(){

    
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'twitter_clone',
        'username'  => 'root',
        'password'  => '',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};


$app->group('/api/v1', function(){
	
	// LISTA DE POST

    $this->get('/tweets/{id_logado}', function($request, $response, $args) {
        
        $idUsuario = $args['id_logado'];
        $db = $this->get('db');
        
        $tweets = $db->table('tweets as t')
            ->select('t.id', 't.id_usuario', 'u.nome', 't.tweet')
            ->selectRaw("DATE_FORMAT(t.data, '%d/%m/%y %h:%i') as data") // Use selectRaw para a expressão SQL bruta
            ->leftJoin('usuarios as u', 't.id_usuario', '=', 'u.id')
            ->where(function($query) use ($idUsuario) {
                $query->where('t.id_usuario', $idUsuario)
                    ->orWhereIn('t.id_usuario', function($subquery) use ($idUsuario) {
                        $subquery->select('id_usuario_segindo')
                            ->from('usuarios_seguidores')
                            ->where('id_usuario', $idUsuario);
                    });
            })
            ->orderBy('data', 'DESC')
            ->get();
    
        $tl = [];
        foreach ($tweets as $tweet) {
            $tl[] = [
                'id' => $tweet->id,
                'id_usuario' => $tweet->id_usuario,
                'nome' => $tweet->nome,
                'tweet' => $tweet->tweet,
                'data' => $tweet->data,
            ];
        }
    
        return $response->withJson(['tweets' => $tl]);
    });
    
    //METODO DE POSTAGEM USANDO API GET

    $this->get('/tweets/adiciona/{id_usuario}/{tweet}', function($request, $response){


        $db = $this->get('db');

        $id_usuario = $request->getAttribute('id_usuario');
        $tweet = $request->getAttribute('tweet');
    
         /* INSERIR */
        $db->table('tweets')->insert([
            'id_usuario' => $id_usuario,
            'tweet' => $tweet
        ]);

        return $response->withJson( $tweet );

	});

        //METODO DE POSTAGEM USANDO API POST 
});




$app->run();


