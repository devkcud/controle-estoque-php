<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Inventário</title>

    <style>
        table * {
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Sistema de Inventário</h1>
    <p>Por: André Luis, Carlos Daniel</p>

    <?php
    session_start();
    if (!isset($_SESSION['inventario'])) {
        $_SESSION['inventario'] = [];
    }
    $inventario = $_SESSION['inventario'];

    function generateUniqueId() {
        return uniqid();
    }

    function adicionarItem($nome, $quantidade, $preco) {
        global $inventario;
        $id = generateUniqueId();
        $item = [
            'id' => $id,
            'nome' => $nome,
            'quantidade' => $quantidade,
            'preco' => $preco
        ];
        $inventario[$id] = $item;
        $_SESSION['inventario'] = $inventario;
    }

    function exibirInventario() {
        global $inventario;
        if (empty($inventario)) {
            echo "O inventário está vazio.<br>";
        } else {
            echo "<h2>Inventário</h2>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Nome</th><th>Quantidade</th><th>Preço</th><th>Ações</th></tr>";
            foreach ($inventario as $id => $item) {
                echo "<tr>";
                echo "<td>" . $id . "</td>";
                echo "<td>" . $item['nome'] . "</td>";
                echo "<td>" . $item['quantidade'] . "</td>";
                echo "<td>R$" . $item['preco'] . "</td>";
                echo "<td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' name='excluir'>Excluir</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    function atualizarItem($id, $nome, $quantidade, $preco) {
        global $inventario;
        if (isset($inventario[$id])) {
            if (!empty($nome)) {
                $inventario[$id]['nome'] = $nome;
            }
            if (!empty($quantidade)) {
                $inventario[$id]['quantidade'] = $quantidade;
            }
            if (!empty($preco)) {
                $inventario[$id]['preco'] = $preco;
            }
            $_SESSION['inventario'] = $inventario;
            echo "Item atualizado com sucesso.<br>";
        } else {
            echo "Item não encontrado.<br>";
        }
    }

    function excluirItem($id) {
        global $inventario;
        if (isset($inventario[$id])) {
            unset($inventario[$id]);
            $_SESSION['inventario'] = $inventario;
            echo "Item excluído com sucesso.<br>";
        } else {
            echo "Item não encontrado.<br>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['adicionar'])) {
            $nome = $_POST['nome'];
            $quantidade = $_POST['quantidade'];
            $preco = $_POST['preco'];
            adicionarItem($nome, $quantidade, $preco);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (isset($_POST['atualizar'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $quantidade = $_POST['quantidade'];
            $preco = $_POST['preco'];
            atualizarItem($id, $nome, $quantidade, $preco);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (isset($_POST['excluir'])) {
            $id = $_POST['id'];
            excluirItem($id);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
    ?>

    <h2>Adicionar um Item</h2>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required>
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required>
        <button type="submit" name="adicionar">Adicionar Item</button>
    </form>

    <h2>Atualizar um Item</h2>
    <form method="post">
        <label for="id">ID do Item:</label>
        <input type="text" id="id" name="id">
        <label for="nome">Novo Nome:</label>
        <input type="text" id="nome" name="nome">
        <label for="quantidade">Nova Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade">
        <label for="preco">Novo Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01">
        <button type="submit" name="atualizar">Atualizar Item</button>
    </form>

    <h2>Excluir um Item</h2>
    <form method="post">
        <label for="id">ID do Item:</label>
        <input type="text" id="id" name="id">
        <button type="submit" name="excluir">Excluir Item</button>
    </form>

    <?php
    exibirInventario();
    ?>
</body>
</html>
