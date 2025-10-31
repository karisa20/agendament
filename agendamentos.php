<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Define o filtro
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

$condicao_data = "";

if ($filtro === 'semana') {
    $condicao_data = "AND YEARWEEK(h.data_hora, 1) = YEARWEEK(NOW(), 1)";
} elseif ($filtro === 'mes') {
    $condicao_data = "AND MONTH(h.data_hora) = MONTH(NOW()) AND YEAR(h.data_hora) = YEAR(NOW())";
}

// Consulta os agendamentos com filtro aplicado
$sql = "SELECT a.id, a.nome_cliente, a.telefone, h.data_hora
        FROM agendamentos a
        INNER JOIN horarios h ON a.horario_id = h.id
        WHERE h.usuario_id = $usuario_id $condicao_data
        ORDER BY h.data_hora ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Meus Agendamentos</title>
<link rel="stylesheet" href="style.css">
<style>
body {
  font-family: Arial, sans-serif;
  /* background-color: #f6f8fa; */
  padding: 20px;
}
h2 {
  color: #0544AB;
}
.filtros {
  margin-top: 10px;
  margin-bottom: 15px;
}
.filtros a {
  margin-right: 10px;
  padding: 6px 12px;
  background-color: #0544AB;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
}
.filtros a:hover {
  background-color: #043A8B;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
th, td {
  border: 1px solid #ddd; /* borda table */
  padding: 10px;
  text-align: left;
}
th {
  background-color: #0544AB;
  color: white;
}
tr:nth-child(even) { /* cor de dentro da linha */
  background-color: #f2f2f2;
}
a.voltar {
  display: inline-block;
  margin-top: 20px;
  color: #0544AB;
  text-decoration: none;
}
</style>
</head>
<body>

<h2>📅 Meus Agendamentos</h2>

<div class="filtros">
  <strong>Filtrar por:</strong>
  <a href="?filtro=todos" <?php if($filtro=='todos') echo 'style="background-color:#043A8B"'; ?>>Todos</a>
  <a href="?filtro=semana" <?php if($filtro=='semana') echo 'style="background-color:#043A8B"'; ?>>Esta Semana</a>
  <a href="?filtro=mes" <?php if($filtro=='mes') echo 'style="background-color:#043A8B"'; ?>>Este Mês</a>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
<table>
  <tr>
    <th>Data e Hora</th>
    <th>Cliente</th>
    <th>Telefone</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?php echo date('d/m/Y H:i', strtotime($row['data_hora'])); ?></td>
      <td><?php echo htmlspecialchars($row['nome_cliente']); ?></td>
      <td><?php echo htmlspecialchars($row['telefone']); ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php else: ?>
  <p>Nenhum agendamento encontrado para o filtro selecionado.</p>
<?php endif; ?>

<a href="dashboard.php" class="voltar">⬅ Voltar ao Painel</a>

</body>
</html>