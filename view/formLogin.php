<?php
    session_start();
    $_SESSION["senha"] = "";
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../assets/css/style.css" rel="stylesheet"/>
</head>

<body id="B1">
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
        <?php
            if(isset($_SESSION["erro"])) {
                echo '<div class="alert alert-danger text-center position-absolute top-0 mt-4" style="width:300px;">'
                . $_SESSION["erro"] .
    '</div>';
                unset($_SESSION["erro"]);
    }
    ?>
    <div class="body-login d-flex flex-column align-items-center shadow text-center" id="LogFundo">
        <img src="../assets/img/lock.png" alt="Login" class="mb-3" style="width: 60px;">

        <form name="login" action="../controller/login.php" method="POST">
            <div class="mb-3 mt-3">
                <label for="user" class="form-label">Usuário:</label>
                <input type="text" class="form-control" placeholder="Usuário" name="user" required>
            </div>

            <div class="mb-3 mt-3">
                <label for="user" class="form-label">Senha:</label>
                <input type="password" class="form-control" placeholder="Senha" name="senha" required>
            </div>

            <button type="submit" class="btn btn-primary center" id="BtnEnviar">Enviar</button>

            </form>
        <br>
    </div>
</div>

</body>