<?php

require_once __DIR__ . '/../Models/Album.php';

class PacotinhoController {
    
    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        $view = '../app/Views/abrir_pacote.php';
        require __DIR__ . '/../Views/layout.php';
    }

    public function open() {
        // Desativa exibição de erros no meio do JSON
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        // Limpa qualquer saída anterior
        while (ob_get_level()) ob_end_clean();
        
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['error' => 'Não logado']);
            exit;
        }

        try {
            $album = new Album();
            $jogadoresSorteados = $album->drawRandomJogadores(5);

            foreach ($jogadoresSorteados as $jogador) {
                $album->addFigurinhaToUsuario($_SESSION['usuario_id'], $jogador['id']);
            }

            echo json_encode($jogadoresSorteados);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro no banco: ' . $e->getMessage()]);
        }
        exit;
    }
}
