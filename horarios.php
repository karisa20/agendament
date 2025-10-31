<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

//Inserir novo horário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['data_hora'])) {
    $data_hora = $_POST['data_hora'];
    $sql = "INSERT INTO horarios (usuario_id, data_hora) VALUES ('$usuario_id', '$data_hora')";
    mysqli_query($conn, $sql);
}

//Excluir horário
if (isset($_GET['excluir'])) {
    $id_horario = $_GET['excluir'];
    $sql = "DELETE FROM horarios WHERE id='$id_horario' AND usuario_id='$usuario_id'";
    mysqli_query($conn, $sql);
}

//Listar horários cadastrados
$sql = "SELECT * FROM horarios WHERE usuario_id='$usuario_id' ORDER BY data_hora ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Gerenciar Horários</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="page-header">
  <a href="dashboard.php" class="back-link">← Voltar</a>
  <h1 style="color:#fff; margin:0;">Gerenciar Horários</h1>
  
</div>
</header>

<main>
  <form method="POST" class="form-box">
    <label for="data_hora">Novo horário:</label><br>
    <input type="datetime-local" name="data_hora" required> <br> <br>
    <button type="submit" class="btn-primary ">Adicionar</button>
  </form>

  <h2>Horários cadastrados</h2>
  <table>
    <tr>
      <th>Data e Hora</th>
      <th>Status</th>
      <th>Ações</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?php echo date("d/m/Y H:i", strtotime($row['data_hora'])); ?></td>
      <td><?php echo $row['disponivel'] ? "Disponível" : "Indisponível"; ?></td>
      <td>
        <a href="?excluir=<?php echo $row['id']; ?>" onclick="return confirm('Excluir este horário?')">Excluir</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</main>

</body>
</html>
