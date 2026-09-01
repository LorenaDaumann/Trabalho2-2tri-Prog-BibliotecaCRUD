<?php
session_start();
require_once('../model/Entity.class.php');
$entity = new Entity();

$idLivro = $_POST["id"];
$dados = $_POST;

if(isset($dados) && !empty($dados)){
    try {
        $entity->update("livro", $dados, $idLivro);
        $_SESSION["msg"] = "Alterado com sucesso";
    }catch (Exception $e){
        $_SESSION["msg_error"] = "$e";
    }
}

header('Location:../view/pagina1.php');

?>