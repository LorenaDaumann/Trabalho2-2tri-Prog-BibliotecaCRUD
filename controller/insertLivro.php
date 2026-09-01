<?php
session_start();
require_once("../model/Entity.class.php");
$entity = new Entity();


$dados = $_POST;
print_r($dados);

if(isset($dados) && !empty($dados)){
    try{
        $entity->insert("livro", $dados);
        $_SESSION["msg"] = "Cadastrado com sucesso!";
    }catch(Exception $e){
        $_SESSION["msg_error"] = $e;
    }
}

header("Location: ../view/LivroRegister.php");

?>