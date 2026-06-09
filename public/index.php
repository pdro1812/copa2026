<?php

// Basic routing
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');

switch ($url) {
    case 'home':
        echo "<h1>Bem-vindo ao Álbum da Copa 2026</h1>";
        break;
    case 'login':
        echo "<h1>Página de Login</h1>";
        break;
    default:
        echo "<h1>404 - Página não encontrada</h1>";
        break;
}
