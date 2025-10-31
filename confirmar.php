<?php
include('conexao.php');

// Verifica se veio algo do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $horario_id = $_POST['horario_id'];
    $nome_cliente = trim($_POST['nome_cliente']);
    $telefone = trim($_POST['telefone']);
    $usuario_id = $_POST['usuario_id'];

    // Verifica se o horário ainda está disponível
    $sql_check = "SELECT * FROM horarios WHERE id='$horario_id' AND disponivel=1";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) == 0) {
        echo "<h3>Esse horário já foi agendado. 😕</h3>";
        echo "<a href='agenda.php?user=".$_GET['user']."'>Voltar para agenda</a>";
        exit;
    }

    // Insere o agendamento
    $sql_insert = "INSERT INTO agendamentos (horario_id, nome_cliente, telefone)
                   VALUES ('$horario_id', '$nome_cliente', '$telefone')";
    if (mysqli_query($conn, $sql_insert)) {

        // Marca o horário como indisponível
        $sql_update = "UPDATE horarios SET disponivel=0 WHERE id='$horario_id'";
        mysqli_query($conn, $sql_update);

        // Busca dados do profissional
        $sql_user = "SELECT nome, link_publico FROM usuarios WHERE id='$usuario_id'";
        $result_user = mysqli_query($conn, $sql_user);
        $usuario = mysqli_fetch_assoc($result_user);

        // Busca data e hora do horário agendado
        $sql_h = "SELECT data_hora FROM horarios WHERE id='$horario_id'";
        $result_h = mysqli_query($conn, $sql_h);
        $horario = mysqli_fetch_assoc($result_h);

        $data_formatada = date("d/m/Y \à\s H:i", strtotime($horario['data_hora']));
        $nome_profissional = $usuario['nome'];

        // Monta mensagem de WhatsApp
        $mensagem = "Oi, sou o $nome_cliente! Gostaria de confirmar o agendamento no dia $data_formatada.";
        $mensagem_url = urlencode($mensagem);

        // Redireciona pro WhatsApp Web (sem número fixo ainda)
        header("Location: sucesso.php?mensagem=$mensagem_url");
        exit;
    } else {
        echo "Erro ao salvar agendamento.";
    }
} else {
    echo "Acesso inválido.";
}
?>