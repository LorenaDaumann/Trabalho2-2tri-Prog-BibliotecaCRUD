

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body id="B3">

    <h1 id="TP">Cadastro de Livro</h1><br>
    <form method="POST" action="../controller/insertLivro.php">
        <div class="form-group">
            <label class="LR">Titulo</label>
            <input type="text" name="titulo" class="form-control" id="I1"/>
        </div>

        <div class="form-group">
            <label class="LR">Autor</label>
            <input type="text" name="autor" class="form-control" id="I2"/>
    </div>

    <div class=" form-group">
            <label class="LR">Editora</label>
            <input type="text" name="editora" class="form-control" id="I3"/>
    </div>

    <div class=" form-group">
            <label class="LR">Ano de Publicação</label>
            <input type="number" name="ano" class="form-control" id="I4"/>
    </div>

    <div class=" form-group">
            <label class="LR">Quantidade de Exemplares</label>
            <input type="number" name="quantidade" class="form-control" id="I5"/>
    </div>
    <br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?php if (isset($livro['id'])): ?>
            <input type="hidden" name="id" value="<?= $livro['id'] ?>">
            <?php endif; ?> 
            <button type="submit" class="btn btn-success btn-sm" id="BtnCadastrar">Cadastrar</button>          
        </div>        
    </form>
            <form action="../view/pagina1.php" method="POST">
        <button type="submit" class="btn btn-danger" id="BtnVoltar">Voltar</button>
    </form>
    
    <?php
    session_start();

    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
    }
    if (isset($_SESSION["msg_error"])) {
        echo $_SESSION["msg_error"];
    }
    ?>

</body>

</html>
