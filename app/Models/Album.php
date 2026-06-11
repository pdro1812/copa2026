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

        if ($res) return $res['id'];

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
