<?php
include('conexao.php');

if (!isset($_GET['user'])) {
    echo "Usuário não encontrado!";
    exit;
}

$link_publico = strtolower(trim($_GET['user'] ?? ''));

//Busca o profissional de forma segura
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE link_publico = ?");
$stmt->bind_param("s", $link_publico);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Profissional não encontrado!";
    exit;
}

$usuario = $result->fetch_assoc();
$usuario_id = $usuario['id'];

// 🔹 Busca horários disponíveis (também com prepared statement)
$stmt_h = $conn->prepare("SELECT * FROM horarios WHERE usuario_id = ? AND disponivel = 1 ORDER BY data_hora ASC");
$stmt_h->bind_param("i", $usuario_id);
$stmt_h->execute();
$horarios = $stmt_h->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agenda de <?= htmlspecialchars($usuario['nome']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Agende com <?= htmlspecialchars($usuario['nome']) ?></h2>

<?php if ($horarios->num_rows == 0): ?>
  <p>Não há horários disponíveis no momento.</p>
<?php else: ?>
  <form action="confirmar.php" method="POST" class="form-box>
    <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario_id) ?>">
    <label for="horario">Escolha um horário:</label>
    <select name="horario_id" required>
      <?php while ($h = $horarios->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($h['id']) ?>">
          <?= date("d/m/Y H:i", strtotime($h['data_hora'])) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <input type="text" name="nome_cliente" placeholder="Seu nome" required>
    <input type="tel" name="telefone" placeholder="Seu telefone" required>
    <button class="btn-primary" type="submit">Confirmar Agendamento</button>
  </form>
<?php endif; ?>

</body>
</html>
