<?php
// Iniciar sessão com segurança
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict'
]);

// Configuração do banco
$mysqli = new mysqli("localhost", "root", "", "agendament");
if ($mysqli->connect_error) {
    error_log("Erro de conexão: " . $mysqli->connect_error);
    exit("Erro interno. Tente novamente mais tarde.");
}

// Função para sanitizar dados de entrada
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Função segura de consulta preparada
function db_query($mysqli, $query, $types = "", $params = []) {
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        error_log("Erro SQL: " . $mysqli->error);
        return false;
    }

    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result();
}
?>
