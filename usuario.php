<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuario</title>
    <script>
        function enviarFormulario() {
            const nome = document.getElementById("nome").value.trim();
            const email = document.getElementById("email").value.trim();

            if (!nome || !email) {
                document.getElementById("mensagem").innerHTML = "Preencha todos os campos.";
                return;
            }

            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('email', email);

            fetch('usuario_insert.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("mensagem").innerHTML = data.mensagem;
                    if (data.sucesso) {
                        document.getElementById("nome").value = '';
                        document.getElementById("email").value = '';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById("mensagem").innerHTML = "Erro ao realizar a operação. Tente novamente.";
                });
        }
    </script>
    <style>
        body {
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


        button {
            background-color: #3880fc;
            color: white;
            margin-top: 10px;
        }

        .sessao {
            margin: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Controle das Tarefas</h1>
        <nav>
            <a href="usuario.php">Cadastro de Usuario</a>
            <a href="tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciar.php">Gerenciar tarefas</a>
        </nav>
    </header>
    <section class="sessao">
        <h1>Cadastro de Usuario</h1>
        <label>Nome:</label><br>
        <input type="text" id="nome"><br><br>

        <label>Email:</label><br>
        <input type="text" id="email"><br><br>

        <button onclick="enviarFormulario()">Inserir</button>

        <p id="mensagem"></p>
    </section>
</body>
</html>
