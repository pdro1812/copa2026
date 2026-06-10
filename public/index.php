<?php

session_start();

require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/ImportController.php';

$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');

$auth = new AuthController();
$import = new ImportController();

switch ($url) {
    case 'home':
        $view = null;
        require 'app/Views/layout.php';
        ?>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-body text-center p-5">
                        <h1 class="display-4 mb-4">Bem-vindo ao Álbum Virtual</h1>
                        <p class="lead mb-5 text-muted">Gerencie sua coleção de figurinhas e acompanhe os jogos da Copa 2026.</p>
                        
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                            <a href="index.php?url=album" class="btn btn-primary btn-lg px-4">Meu Álbum</a>
                            <a href="index.php?url=sync_fifa" class="btn btn-outline-warning btn-lg px-4">Sincronizar FIFA</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
        
    case 'sync_fifa':
        $import->syncFifa();
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
