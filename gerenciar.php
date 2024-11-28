<?php
include 'conecta.php';

$sqlAFazer = "
    SELECT t.tar_codigo, t.tar_setor, t.tar_descricao, t.tar_prioridade, t.tar_data, t.tar_status, u.usu_nome 
    FROM tarefas t
    LEFT JOIN usuarios u ON t.usu_codigo = u.usu_codigo
    WHERE t.tar_status = 'à fazer'";

$sqlEmAndamento = "
    SELECT t.tar_codigo, t.tar_setor, t.tar_descricao, t.tar_prioridade, t.tar_data, t.tar_status, u.usu_nome 
    FROM tarefas t
    LEFT JOIN usuarios u ON t.usu_codigo = u.usu_codigo
    WHERE t.tar_status = 'em andamento'";

$sqlConcluido = "
    SELECT t.tar_codigo, t.tar_setor, t.tar_descricao, t.tar_prioridade, t.tar_data, t.tar_status, u.usu_nome 
    FROM tarefas t
    LEFT JOIN usuarios u ON t.usu_codigo = u.usu_codigo
    WHERE t.tar_status = 'concluído'";

$queryTarefasAFazer = mysqli_query($conn, $sqlAFazer);
$queryTarefasEmAndamento = mysqli_query($conn, $sqlEmAndamento);
$queryTarefasConcluido = mysqli_query($conn, $sqlConcluido);
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }

        header {
            background-color: #3880fc;
            color: white;
            position: relative;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin-left: 10px;
            font-size: 2rem;
        }

        nav {
            background-color: #3880fc;
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 25px;
            background-color: #3880fc;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #3880fc;
        }

        .tarefas {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .coluna {
            width: 30%;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .coluna h2 {
            text-align: center;
            color: #3880fc;
            margin-bottom: 20px;
        }

        .cartao {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 20px;
            font-size: 1rem;
        }

        .cartao h3 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #3880fc;
        }

        .task-info {
            margin: 10px 0;
        }

        .task-info span {
            font-weight: bold;
        }

        .statusSelect {
            width: 100%;
            padding: 5px;
            margin-top: 10px;
        }

        .btn-atualiza, .btn-apaga {
            padding: 8px 15px;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-atualiza {
            background-color: #3880fc;
        }

        .btn-apaga {
            background-color: #e74c3c;
        }

        .btn-editar {
            padding: 8px 15px;
            color: white;
            background-color: #f39c12;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-editar:hover {
            background-color: #e67e22;
        }

        .message {
            margin: 20px;
            text-align: center;
            font-size: 1.2rem;
            color: green;
        }
    </style>
    <script>
        function atualizarStatus(tarefaId) {
            const status = document.getElementById('status-' + tarefaId).value;
            const formData = new FormData();
            formData.append('tar_codigo', tarefaId);
            formData.append('status', status);

            fetch('gerenciar_modify.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    window.location.reload();
                } else {
                    document.getElementById("mensagem").innerHTML = data.mensagem;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById("mensagem").innerHTML = "Erro ao atualizar o status.";
            });
        }

        function excluirTarefa(tarefaId) {
            if (confirm("Tem certeza de que deseja excluir esta tarefa?")) {
                const formData = new FormData();
                formData.append('tar_codigo', tarefaId);
                formData.append('acao', 'excluir');

                fetch('gerenciar_modify.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        window.location.reload();
                    } else {
                        document.getElementById("mensagem").innerHTML = data.mensagem;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById("mensagem").innerHTML = "Erro ao excluir a tarefa.";
                });
            }
        }
    </script>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>Controle das Tarefas</h1>
        </div>
        <nav>
            <a href="usuario.php">Cadastro de Usuario</a>
            <a href="tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciar.php">Gerenciar tarefas</a>
        </nav>
    </header>

    <section>
        <div class="tarefas">
        <div class='coluna'>
    <h2>À Fazer</h2>
    <?php
    if (mysqli_num_rows($queryTarefasAFazer) > 0) {
        while ($linhaTarefa = mysqli_fetch_assoc($queryTarefasAFazer)) {
            echo "<div class='cartao' id='tarefa-{$linhaTarefa['tar_codigo']}'>";
            echo "<h3>Tarefa #{$linhaTarefa['tar_codigo']}</h3>";
            echo "<div class='task-info'><span>Setor:</span> {$linhaTarefa['tar_setor']}</div>";
            echo "<div class='task-info'><span>Data:</span> {$linhaTarefa['tar_data']}</div>";
            echo "<div class='task-info'><span>Vinculado a:</span> {$linhaTarefa['usu_nome']}</div>";
            echo "<div class='task-info'><span>Descrição:</span> {$linhaTarefa['tar_descricao']}</div>";
            echo "<div class='task-info'><span>Prioridade:</span> {$linhaTarefa['tar_prioridade']}</div>";
            echo "<div class='task-info'><span>Status:</span>
                    <select id='status-{$linhaTarefa['tar_codigo']}' class='statusSelect'>
                        <option value='à fazer' selected>À fazer</option>
                        <option value='em andamento'>Em andamento</option>
                        <option value='concluído'>Concluído</option>
                    </select>
                  </div>";
            echo "<div>
                    <button onclick='atualizarStatus({$linhaTarefa['tar_codigo']})' class='btn-atualiza'>Atualizar</button>
                    <button onclick='excluirTarefa({$linhaTarefa['tar_codigo']})' class='btn-apaga'>Excluir</button>
                    <a href='editar_tarefa.php?tar_codigo=" . $linhaTarefa['tar_codigo'] . "' class='btn-editar'>Editar</a>
                  </div>";
            echo "</div>";
        }
    } else {
        echo "<div class='message'>Nenhuma tarefa à fazer.</div>";
    }
    ?>
</div>

<div class='coluna'>
    <h2>Em Andamento</h2>
    <?php
    if (mysqli_num_rows($queryTarefasEmAndamento) > 0) {
        while ($linhaTarefa = mysqli_fetch_assoc($queryTarefasEmAndamento)) {
            echo "<div class='cartao' id='tarefa-{$linhaTarefa['tar_codigo']}'>";
            echo "<h3>Tarefa #{$linhaTarefa['tar_codigo']}</h3>";
            echo "<div class='task-info'><span>Setor:</span> {$linhaTarefa['tar_setor']}</div>";
            echo "<div class='task-info'><span>Data:</span> {$linhaTarefa['tar_data']}</div>";
            echo "<div class='task-info'><span>Vinculado a:</span> {$linhaTarefa['usu_nome']}</div>";
            echo "<div class='task-info'><span>Descrição:</span> {$linhaTarefa['tar_descricao']}</div>";
            echo "<div class='task-info'><span>Prioridade:</span> {$linhaTarefa['tar_prioridade']}</div>";
            echo "<div class='task-info'><span>Status:</span>
                    <select id='status-{$linhaTarefa['tar_codigo']}' class='statusSelect'>
                        <option value='à fazer'>À fazer</option>
                        <option value='em andamento' selected>Em andamento</option>
                        <option value='concluído'>Concluído</option>
                    </select>
                  </div>";
            echo "<div>
                    <button onclick='atualizarStatus({$linhaTarefa['tar_codigo']})' class='btn-atualiza'>Atualizar</button>
                    <button onclick='excluirTarefa({$linhaTarefa['tar_codigo']})' class='btn-apaga'>Excluir</button>
                    <a href='editar_tarefa.php?tar_codigo=" . $linhaTarefa['tar_codigo'] . "' class='btn-editar'>Editar</a>
                  </div>";
            echo "</div>";
        }
    } else {
        echo "<div class='message'>Nenhuma tarefa em andamento.</div>";
    }
    ?>
</div>

<div class='coluna'>
    <h2>Concluído</h2>
    <?php
    if (mysqli_num_rows($queryTarefasConcluido) > 0) {
        while ($linhaTarefa = mysqli_fetch_assoc($queryTarefasConcluido)) {
            echo "<div class='cartao' id='tarefa-{$linhaTarefa['tar_codigo']}'>";
            echo "<h3>Tarefa #{$linhaTarefa['tar_codigo']}</h3>";
            echo "<div class='task-info'><span>Setor:</span> {$linhaTarefa['tar_setor']}</div>";
            echo "<div class='task-info'><span>Data:</span> {$linhaTarefa['tar_data']}</div>";
            echo "<div class='task-info'><span>Vinculado a:</span> {$linhaTarefa['usu_nome']}</div>";
            echo "<div class='task-info'><span>Descrição:</span> {$linhaTarefa['tar_descricao']}</div>";
            echo "<div class='task-info'><span>Prioridade:</span> {$linhaTarefa['tar_prioridade']}</div>";
            echo "<div class='task-info'><span>Status:</span>
                    <select id='status-{$linhaTarefa['tar_codigo']}' class='statusSelect'>
                        <option value='à fazer'>À fazer</option>
                        <option value='em andamento'>Em andamento</option>
                        <option value='concluído' selected>Concluído</option>
                    </select>
                  </div>";
            echo "<div>
                    <button onclick='atualizarStatus({$linhaTarefa['tar_codigo']})' class='btn-atualiza'>Atualizar</button>
                    <button onclick='excluirTarefa({$linhaTarefa['tar_codigo']})' class='btn-apaga'>Excluir</button>
                    <a href='editar_tarefa.php?tar_codigo=" . $linhaTarefa['tar_codigo'] . "' class='btn-editar'>Editar</a>
                  </div>";
            echo "</div>";
        }
    } else {
        echo "<div class='message'>Nenhuma tarefa concluída.</div>";
    }
    ?>
</div>

        <p id="mensagem" class="message"></p>
    </section>
</body>
</html>

<?php mysqli_close($conn); ?>