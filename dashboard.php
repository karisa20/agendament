<?php
session_start();
include('conexao.php');
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
$nome = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?php echo $nome; ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?> 👋</h1>
  <a href="horarios.php" class="botao">Gerenciar Horários</a>
  <a href="agendamentos.php" class="botao">Ver Agendamentos</a>
  <a href="agenda.php?user=<?php echo $_SESSION['usuario_nome']; ?>" class="botao">Link Público</a>
  
  <br>
  <a href="logout.php" class="logout">Sair</a>
</div>
</body>
</html>
