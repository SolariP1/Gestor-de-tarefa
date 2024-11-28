<?php
include 'conecta.php';

$response = ["sucesso" => false, "mensagem" => ""];

if (isset($_POST['tar_codigo']) && isset($_POST['status'])) {
    $tar_codigo = $_POST['tar_codigo'];
    $status = $_POST['status'];

    $sql = "UPDATE tarefas SET tar_status = '$status' WHERE tar_codigo = $tar_codigo";
    if (mysqli_query($conn, $sql)) {
        $response["sucesso"] = true;
        $response["mensagem"] = "Status atualizado com sucesso!";
    } else {
        $response["mensagem"] = "Erro ao atualizar status: " . mysqli_error($conn);
    }
} 
elseif (isset($_POST['tar_codigo']) && isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
    $tar_codigo = $_POST['tar_codigo'];

    $sql = "DELETE FROM tarefas WHERE tar_codigo = $tar_codigo";
    if (mysqli_query($conn, $sql)) {
        $response["sucesso"] = true;
        $response["mensagem"] = "Tarefa excluída com sucesso!";
    } else {
        $response["mensagem"] = "Erro ao excluir tarefa: " . mysqli_error($conn);
    }
} 
else {
    $response["mensagem"] = "Ação inválida ou parâmetros ausentes.";
}

mysqli_close($conn);
echo json_encode($response);
?>