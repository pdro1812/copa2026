<?php

require_once __DIR__ . '/../../config/database.php';

class Album {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveSelecao($nome, $sigla, $bandeira_url = '') {
        $stmt = $this->db->prepare("SELECT id FROM selecoes WHERE nome = :nome");
        $stmt->execute([':nome' => $nome]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            // Se já existe, atualizamos a sigla e a bandeira (caso tenham mudado ou não existissem)
            $stmt = $this->db->prepare("UPDATE selecoes SET sigla = :sigla, bandeira_url = :bandeira WHERE id = :id");
            $stmt->execute([':sigla' => $sigla, ':bandeira' => $bandeira_url, ':id' => $res['id']]);
            return $res['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO selecoes (nome, sigla, bandeira_url) VALUES (:nome, :sigla, :bandeira_url)");
        $stmt->execute([
            ':nome' => $nome,
            ':sigla' => $sigla,
            ':bandeira_url' => $bandeira_url
        ]);
        return $this->db->lastInsertId();
    }

    public function saveJogador($selecaoId, $nome, $posicao, $fotoUrl, $codigo) {
        $stmt = $this->db->prepare("SELECT id FROM jogadores WHERE codigo_figurinha = :codigo");
        $stmt->execute([':codigo' => $codigo]);
        if ($stmt->fetch()) return true;

        $stmt = $this->db->prepare("INSERT INTO jogadores (selecao_id, nome, posicao, foto_url, codigo_figurinha) VALUES (:selecao_id, :nome, :posicao, :foto_url, :codigo)");
        return $stmt->execute([
            ':selecao_id' => $selecaoId,
            ':nome' => $nome,
            ':posicao' => $posicao,
            ':foto_url' => $fotoUrl,
            ':codigo' => $codigo
        ]);
    }

    public function getAllSelecoes() {
        return $this->db->query("SELECT * FROM selecoes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSelecaoById($id) {
        $stmt = $this->db->prepare("SELECT * FROM selecoes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getJogadoresBySelecao($selecaoId, $usuarioId) {
        $stmt = $this->db->prepare("
            SELECT j.*, uf.quantidade 
            FROM jogadores j 
            LEFT JOIN usuario_figurinhas uf ON j.id = uf.jogador_id AND uf.usuario_id = :uid
            WHERE j.selecao_id = :sid
            ORDER BY j.codigo_figurinha
        ");
        $stmt->execute([':sid' => $selecaoId, ':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgressoUsuario($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM jogadores) as total_album,
                (SELECT COUNT(*) FROM usuario_figurinhas WHERE usuario_id = :uid) as total_usuario
        ");
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProgressoPorSelecoes($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT 
                s.id,
                COUNT(j.id) as total_jogadores,
                COUNT(uf.jogador_id) as total_usuario
            FROM selecoes s
            JOIN jogadores j ON s.id = j.selecao_id
            LEFT JOIN usuario_figurinhas uf ON j.id = uf.jogador_id AND uf.usuario_id = :uid
            GROUP BY s.id
        ");
        $stmt->execute([':uid' => $usuarioId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $progresso = [];
        foreach ($rows as $row) {
            $progresso[$row['id']] = ($row['total_jogadores'] > 0) ? ($row['total_usuario'] / $row['total_jogadores']) * 100 : 0;
        }
        return $progresso;
    }

    public function getRepetidas($usuarioId) {
        $stmt = $this->db->prepare("
            SELECT j.*, s.nome as selecao_nome, s.sigla, s.bandeira_url, uf.quantidade
            FROM usuario_figurinhas uf
            JOIN jogadores j ON uf.jogador_id = j.id
            JOIN selecoes s ON j.selecao_id = s.id
            WHERE uf.usuario_id = :uid AND uf.quantidade > 1
            ORDER BY s.nome, j.codigo_figurinha
        ");
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function drawRandomJogadores($limit = 5) {
        $stmt = $this->db->query("SELECT j.id, j.nome, j.posicao, j.foto_url, j.codigo_figurinha, s.nome as selecao_nome, s.sigla 
                                  FROM jogadores j 
                                  JOIN selecoes s ON j.selecao_id = s.id 
                                  ORDER BY RAND() LIMIT $limit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addFigurinhaToUsuario($usuarioId, $jogadorId) {
        // Verifica se já tem
        $stmt = $this->db->prepare("SELECT quantidade FROM usuario_figurinhas WHERE usuario_id = :uid AND jogador_id = :jid");
        $stmt->execute([':uid' => $usuarioId, ':jid' => $jogadorId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            // Incrementa
            $stmt = $this->db->prepare("UPDATE usuario_figurinhas SET quantidade = quantidade + 1 WHERE usuario_id = :uid AND jogador_id = :jid");
            return $stmt->execute([':uid' => $usuarioId, ':jid' => $jogadorId]);
        } else {
            // Insere novo
            $stmt = $this->db->prepare("INSERT INTO usuario_figurinhas (usuario_id, jogador_id, quantidade) VALUES (:uid, :jid, 1)");
            return $stmt->execute([':uid' => $usuarioId, ':jid' => $jogadorId]);
        }
    }
}
