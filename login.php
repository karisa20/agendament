<?php
session_start();
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$email = $_POST['email'];
$senha_digitada = $_POST['senha'];

// Busca o usuário pelo email
$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Verifica se a senha digitada confere com o hash armazenado
    if (password_verify($senha_digitada, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
} else {
    $erro = "Email ou senha incorretos!";
}
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - Sistema de Agendamento</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-box">
  <h2 style="margin:0 0 12px 0;color:#fff;">Entrar</h2>

  <form method="POST" action="login.php" autocomplete="off" novalidate>
    <input type="email" name="email" placeholder="Seu email" required>
    <input type="password" name="senha" placeholder="Sua senha" required>
    <button class="btn-primary" type="submit">Entrar</button>
  </form>

  <div class="form-footer">
    <p>Não tem conta? <a href="registro.php">Cadastre-se</a></p>
  </div>

  <?php if(isset($erro)) echo "<div class='msg error'>".htmlspecialchars($erro)."</div>"; ?>
</div>
</body>
</html>