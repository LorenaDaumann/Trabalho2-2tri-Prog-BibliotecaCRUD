<?php
    session_start();

    $user = $_POST["user"];
    $senha = $_POST["senha"];

    require_once("../model/Entity.class.php");
    $entity = new Entity();

    $result = $entity->login("usuario", $user, $senha);

    if($result){
        $_SESSION["usuario"] = $user;
        header("Location: ../view/pagina1.php");
    } else {
        $_SESSION["erro"] = "Usuário ou senha incorreto";
        header("Location: ../view/formLogin.php");
    }
?>