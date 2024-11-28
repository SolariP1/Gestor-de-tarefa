<?php
include 'conecta.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

$response = ["sucesso" => false, "mensagem" => ""];

if (!$nome || !$email) {
    $response["mensagem"] = "Por favor, preencha todos os campos.";
} else {
    $sql = "INSERT INTO usuarios (usu_nome, usu_email) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $nome, $email);

        if (mysqli_stmt_execute($stmt)) {
            $response["sucesso"] = true;
            $response["mensagem"] = "Cadastrado com sucesso!";
        } else {
            $response["mensagem"] = "Erro ao cadastrar: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $response["mensagem"] = "Erro ao preparar a consulta.";
    }
}

mysqli_close($conn);
echo json_encode($response);
?>
