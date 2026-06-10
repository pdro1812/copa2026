<?php

require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    
    public function showLogin($error = null) {
        $view = '../app/Views/login.php';
        require __DIR__ . '/../Views/layout.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuario();
            $user = $usuarioModel->findByEmail($email);

            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                header('Location: index.php?url=home');
                exit;
            } else {
                $this->showLogin("E-mail ou senha inválidos.");
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?url=login');
        exit;
    }

    // Helper para cadastro rápido (útil para testes)
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuario();
            if ($usuarioModel->create($nome, $email, $senha)) {
                header('Location: index.php?url=login');
                exit;
            }
        }
        $view = '../app/Views/registro.php';
        require __DIR__ . '/../Views/layout.php';
    }
}
