<?php

require_once __DIR__ . '/../Models/Album.php';

class AlbumController {
    
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        $albumModel = new Album();
        $selecoes = $albumModel->getAllSelecoes();
        $progresso = $albumModel->getProgressoUsuario($_SESSION['usuario_id']);
        $progressoSelecoes = $albumModel->getProgressoPorSelecoes($_SESSION['usuario_id']);

        $view = '../app/Views/meu_album.php';
        require __DIR__ . '/../Views/layout.php';
    }

    public function verSelecao() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        $selecaoId = $_GET['id'] ?? null;
        if (!$selecaoId) {
            header('Location: index.php?url=album');
            exit;
        }

        $albumModel = new Album();
        $selecao = $albumModel->getSelecaoById($selecaoId);
        $jogadores = $albumModel->getJogadoresBySelecao($selecaoId, $_SESSION['usuario_id']);

        $view = '../app/Views/detalhes_selecao.php';
        require __DIR__ . '/../Views/layout.php';
    }
}
