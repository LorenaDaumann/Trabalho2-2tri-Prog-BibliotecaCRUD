<?php
session_start();
require_once("../model/Entity.class.php");
$entity = new Entity();

$idEmprestimo = $_POST["id"] ?? null;

if (!empty($idEmprestimo)) {
    try {
        $entity->delete("emprestimos", $idEmprestimo);
        $_SESSION["msg"] = "Registro de empréstimo deletado com sucesso.";
    } catch (Exception $e) {
        $_SESSION["msg_error"] = "Erro ao deletar registro: " . $e->getMessage();
    }
}

header("Location: ../view/pagina1.php");
exit;
