<?php

session_start();

require_once '../app/Controllers/AuthController.php';

$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');

$auth = new AuthController();

switch ($url) {
    case 'home':
        $view = null;
        echo "<h1>Home</h1><p>Bem-vindo ao sistema do Álbum!</p>";
        // Temporariamente renderizando o layout sem view para a home
        require 'app/Views/layout.php';
        break;
        
    case 'login':
        $auth->showLogin();
        break;
        
    case 'login_process':
        $auth->login();
        break;
        
    case 'logout':
        $auth->logout();
        break;
        
    case 'registro':
        $auth->register();
        break;

    case 'album':
    case 'jogos':
        // TODO: Implementar nos próximos commits
        echo "<h1>Em breve...</h1><a href='index.php'>Voltar</a>";
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        break;
}
