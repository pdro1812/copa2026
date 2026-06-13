<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nome, $email, $senha) {
        // Criptografa a senha para segurança
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $hash);
        return $stmt->execute();
    }

    public function getRanking() {
        // Query para contar figurinhas únicas por usuário
        $sql = "SELECT u.nome, COUNT(uf.jogador_id) as total_unicas, SUM(uf.quantidade) as total_figurinhas
                FROM usuarios u
                LEFT JOIN usuario_figurinhas uf ON u.id = uf.usuario_id
                GROUP BY u.id
                ORDER BY total_unicas DESC, total_figurinhas DESC
                LIMIT 10";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
