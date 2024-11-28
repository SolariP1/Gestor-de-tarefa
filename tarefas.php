<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tarefa</title>
    <script>
        function enviarFormulario(botao) {
            const descricao = document.getElementById("descricao").value.trim();
            const setor = document.getElementById("setor").value.trim();
            const usu_codigo = document.getElementById("usu_codigo").value.trim();
            const prioridade = document.getElementById("prioridade").value.trim();

            if (!descricao || !setor || !usu_codigo || !prioridade) {
                document.getElementById("mensagem").innerHTML = "Por favor, preencha todos os campos.";
                return;
            }

            const formData = new FormData();
            formData.append('descricao', descricao);
            formData.append('setor', setor);
            formData.append('usu_codigo', usu_codigo);
            formData.append('prioridade', prioridade);
            formData.append('botao', botao);

            fetch('tarefas_insert.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("mensagem").innerHTML = data.mensagem;
                    
                    if (data.sucesso) {
                        document.getElementById("descricao").value = '';
                        document.getElementById("setor").value = '';
                        document.getElementById("usu_codigo").value = '';
                        document.getElementById("prioridade").value = '';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById("mensagem").innerHTML = "Erro ao realizar a operação. Tente novamente.";
                });
        }
    </script>
    <style>
        .sessao{
            text-align: left;
            margin-left: 20px
        }

        body{
            margin: 0;
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

        button{
            background-color: #3880fc;
            color: white;
            margin-left: 10px
        }
    </style>
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
    <section class="sessao">
        <h1>Cadastro de Tarefas</h1>
        <div>
            <label>Descrição:</label><br>
            <input type="text" id="descricao"><p></p>
            
            <label>Setor:</label><br>
            <input type="text" id="setor"><p></p>
            
            <label>Usuario:</label><br>
            <select id="usu_codigo">
            <option value="">Usuário</option>
                <?php
                    include 'conecta.php';
                    $sql = "SELECT usu_codigo, usu_nome FROM usuarios";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['usu_codigo']}'>{$row['usu_nome']}</option>";
                    }
                    mysqli_close($conn);
                ?>
            </select><br><br>

            <label>Prioridade:</label><br>
            <select id="prioridade">
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baixa">Baixa</option>
            </select><p></p>
            
            <button onclick="enviarFormulario('inserir')">Inserir</button>
            
            <p id="mensagem"></p>
        </div>
    </section>
</body>
</html>