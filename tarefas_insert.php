<?php
include 'conecta.php';

$descricao = $_POST['descricao'] ?? null;
$setor = $_POST['setor'] ?? null;
$usu_codigo = $_POST['usu_codigo'] ?? null;
$prioridade = $_POST['prioridade'] ?? null;
$date = date('Y-m-d') ?? null;
$botao = $_POST['botao'] ?? null;

$response = ["sucesso" => false, "mensagem" => ""];

if (!$descricao || !$setor || !$usu_codigo || !$prioridade) {
    $response["mensagem"] = "Preencha todos os campos!";
    echo json_encode($response);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO tarefas (tar_descricao, tar_setor, tar_prioridade, tar_data, tar_status, usu_codigo) VALUES (?, ?, ?, ?, 'à fazer', ?)");
mysqli_stmt_bind_param($stmt, "ssssi", $descricao, $setor, $prioridade, $date, $usu_codigo);

if (mysqli_stmt_execute($stmt)) {
    $response["sucesso"] = true;
    $response["mensagem"] = "Tarefa cadastrada com sucesso!";
} else {
    $response["mensagem"] = "Erro ao cadastrar: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode($response);
?>