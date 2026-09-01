<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <title>Document</title>

    <?php
    session_start();
    require_once('../model/Entity.class.php');
    $entity = new Entity();
    $idLivro = $_POST["id"];
    ?>
</head>

<body id="B4">
    <div class="form-">
    <!--Criar formulario -->
    <h1 id="TP2">Alterar livro</h1>
    <form method="POST" action="../controller/UpdateLivro.php">
        <div class="form-group">

        <?php
            foreach($entity->getInfo("livro", $idLivro)as $livro);
        ?>

    <input type="hidden" name="id" value="<?= $idLivro ?>?>">

            <label class="LR2">Titulo</label>
            <input type="text" name="titulo" class="form-control" id="IU1" value="<?=  $livro ['titulo'] ?>"/>
        </div>

        <div class="form-group">
            <label class="LR2">Autor</label>
            <input type="text" name="autor" class="form-control" id="IU2" value="<?=  $livro ['autor'] ?>"/>
    </div>

    <div class=" form-group">
            <label class="LR2">Editora</label>
            <input type="text" name="editora" class="form-control" id="IU3" value="<?=  $livro ['editora'] ?>"/>
    </div>
    </div>

    <div class=" form-group">
            <label class="LR2">Ano de Publicação</label>
            <input type="number" name="ano" class="form-control" id="IU4" value="<?=  $livro ['ano'] ?>"/>
    </div>
    </div>

    <div class=" form-group">
            <label class="LR2">Quantidade de Exemplares</label>
            <input type="number" name="quantidade" class="form-control" id="IU5" value="<?=  $livro ['quantidade'] ?>"/>
    </div>
    </div>
    <br>
    <button type=" submit" class="btn btn-primary" id="BtnSalvar">Salvar</button>
    </form>
            <form action="../view/pagina1.php" method="POST">
        <button type="submit" class="btn btn-danger" id="BtnVoltar2">Voltar</button>
    </form>

    <?php
    session_start();

    if(isset($_SESSION["msg"])){
        echo $_SESSION["msg"];
    }
    if(isset($_SESSION["msg_error"])){
        echo $_SESSION["msg_error"];
    }
    ?>

</body>

</html>