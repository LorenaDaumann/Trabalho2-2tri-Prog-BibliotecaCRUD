<?php
 session_start();
 require_once("../model/Entity.class.php");
 $entity = new Entity();

 $idLivro = $_POST["id"];

 if(isset($idLivro) && !empty($idLivro)){
    try{
        $entity->delete("livro", $idLivro);
        $_SESSION["msg"] = "Deletado com sucesso";
    } catch (Exception $e){
        $_SESSION["msg_error"] = "$e";
    }
 }

header("Location: ../view/pagina1.php");

 ?>