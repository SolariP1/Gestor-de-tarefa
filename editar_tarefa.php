<?php
include 'conecta.php';

if (isset($_GET['tar_codigo'])) {
    $tar_codigo = $_GET['tar_codigo'];

    $sql = "
        SELECT t.tar_codigo, t.tar_setor, t.tar_descricao, t.tar_prioridade, t.tar_data, t.tar_status, u.usu_nome 
        FROM tarefas t
        LEFT JOIN usuarios u ON t.usu_codigo = u.usu_codigo
        WHERE t.tar_codigo = '$tar_codigo'";

    $query = mysqli_query($conn, $sql);
    $tarefa = mysqli_fetch_assoc($query);

    if (!$tarefa) {
        die("Tarefa não encontrada.");
    }
} else {
    die("ID da tarefa não informado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tar_setor = $_POST['tar_setor'];
    $tar_descricao = $_POST['tar_descricao'];
    $tar_prioridade = $_POST['tar_prioridade'];
    $tar_status = $_POST['tar_status'];

    $sqlUpdate = "
        UPDATE tarefas 
        SET tar_setor = '$tar_setor', tar_descricao = '$tar_descricao', tar_prioridade = '$tar_prioridade', tar_status = '$tar_status'
        WHERE tar_codigo = '$tar_codigo'";

    if (mysqli_query($conn, $sqlUpdate)) {
        echo "<p class='success'>Tarefa atualizada com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao atualizar tarefa: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            text-align: left;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .success {
            color: #28a745;
            margin-top: 10px;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Tarefa #<?php echo $tarefa['tar_codigo']; ?></h2>

    <form method="POST" action="">
        <label for="tar_setor">Setor:</label>
        <input type="text" name="tar_setor" id="tar_setor" value="<?php echo $tarefa['tar_setor']; ?>" required>

        <label for="tar_descricao">Descrição:</label>
        <textarea name="tar_descricao" id="tar_descricao" required><?php echo $tarefa['tar_descricao']; ?></textarea>

        <label for="tar_prioridade">Prioridade:</label>
        <select name="tar_prioridade" id="tar_prioridade" required>
            <option value="Baixa" <?php echo ($tarefa['tar_prioridade'] == 'Baixa') ? 'selected' : ''; ?>>Baixa</option>
            <option value="Média" <?php echo ($tarefa['tar_prioridade'] == 'Média') ? 'selected' : ''; ?>>Média</option>
            <option value="Alta" <?php echo ($tarefa['tar_prioridade'] == 'Alta') ? 'selected' : ''; ?>>Alta</option>
        </select>

        <button type="submit">Atualizar Tarefa</button>
    </form>

    <a href="gerenciar.php">
        <button class="btn-back">Voltar para Tarefas</button>
    </a>
</div>

</body>
</html>
