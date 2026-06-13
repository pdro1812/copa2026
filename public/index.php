<?php

ob_start();
session_start();

require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/ImportController.php';
require_once '../app/Controllers/PacotinhoController.php';
require_once '../app/Controllers/AlbumController.php';

$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');

$auth = new AuthController();
$import = new ImportController();
$pacotinho = new PacotinhoController();
$album = new AlbumController();

switch ($url) {
    case 'home':
        $view = '../app/Views/home.php';
        require '../app/Views/layout.php';
        break;
        
    case 'album':
        $album->index();
        break;

    case 'album_selecao':
        $album->verSelecao();
        break;

    case 'repetidas':
        $album->repetidas();
        break;

    case 'ranking':
        $userModel = new Usuario();
        $ranking = $userModel->getRanking();
        $view = '../app/Views/ranking.php';
        require '../app/Views/layout.php';
        break;

    case 'sync_fifa':
        $import->syncFifa();
        break;

    case 'abrir_pacote':
        $pacotinho->index();
        break;

    case 'processar_pacote':
        $pacotinho->open();
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
