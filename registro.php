<?php
include('conexao.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // CSRF check
  if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    die('Request inválida');
  }

  $nome = trim($_POST['nome']);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $senha = $_POST['senha'] ?? '';
  $link_publico = strtolower(trim($_POST['link_publico']));
  $whatsapp = preg_replace('/\D+/', '', $_POST['whatsapp']);

  // Validations
  if (!$email) $erro = "Email inválido";
  elseif (!preg_match('/^[a-z0-9_-]{3,30}$/', $link_publico)) $erro = "Link público inválido";
  elseif (strlen($senha) < 6) $erro = "Senha muito curta";
  elseif (strlen($whatsapp) < 10) $erro = "WhatsApp inválido";

  if (!isset($erro)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica duplicados com prepared
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR link_publico = ?");
    $stmt->bind_param('ss', $email, $link_publico);
    $stmt->execute();
    $r = $stmt->get_result();
    if ($r->num_rows > 0) {
      $erro = "Email ou link já cadastrado!";
    } else {
      $ins = $conn->prepare("INSERT INTO usuarios (nome,email,senha,link_publico,whatsapp) VALUES (?,?,?,?,?)");
      $ins->bind_param('sssss', $nome, $email, $senha_hash, $link_publico, $whatsapp);
      if ($ins->execute()) {
        header("Location: login.php?sucesso=1");
        exit;
      } else {
        $erro = "Erro ao cadastrar";
      }
    }
  }
}

// Gera CSRF token para formulário
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro - Sistema de Agendamento</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-box">
  <h2>Cadastro de Profissional</h2>
  <form method="POST" autocomplete="off" novalidate>
    <input type="text" name="nome" placeholder="Seu nome" required><br>
    <input type="email" name="email" placeholder="Seu email" required><br>
    <input type="password" name="senha" placeholder="Crie uma senha" required><br>
    <input type="text" name="link_publico" placeholder="Seu link público (ex: joao)" required><br>
    <input type="tel" name="whatsapp" placeholder="WhatsApp (ex: 5599999999999)" required><br>

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <button type="submit" class="btn-primary">Cadastrar</button>
  </form>
  <?php if(isset($erro)) echo "<div class='msg error'>".htmlspecialchars($erro)."</div>"; ?>
  <p>Já tem conta? <a href="login.php">Faça login</a></p>
</div>
</body>
</html>