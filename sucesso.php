<?php
if (!isset($_GET['mensagem'])) {
    echo "Nada a confirmar.";
    exit;
}

$mensagem = $_GET['mensagem'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendamento Confirmado</title>
<link rel="stylesheet" href="style.css">
<meta http-equiv="refresh" content="3; url=https://wa.me/?text=<?php echo $mensagem; ?>">
</head>
<body>

<div class="login-container">
  <h2>Agendamento realizado com sucesso! ✅</h2>
  <p>Você será redirecionado para o WhatsApp em alguns segundos...</p>
  <p><a href="https://wa.me/?text=<?php echo $mensagem; ?>">Clique aqui se não for redirecionado automaticamente</a></p>
</div>

</body>
</html>
