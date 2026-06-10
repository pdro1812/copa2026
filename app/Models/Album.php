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
}
