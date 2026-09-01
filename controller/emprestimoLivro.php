<?php
session_start();
require_once('../model/Entity.class.php');
$entity = new Entity();

$idLivro = $_POST['id'] ?? null;
$usuario = $_SESSION['usuario'] ?? null;
$action = $_POST['action'] ?? 'emprestar';

if (!empty($idLivro) && !empty($usuario)) { //if para caso o usuário queira devolver o livro
    try {
        $entity->ensureLoanTable();

        if ($action === 'devolver') { 
            $loan = $entity->getActiveLoan($usuario, $idLivro);
            if (empty($loan)) { //caso não haja o que devolver
                $_SESSION['msg_error'] = 'Nenhum empréstimo ativo encontrado para devolução.';
            } else {
                $devolvidoNome = trim($_POST['nome'] ?? '');
                $devolvidoCpf = trim($_POST['cpf'] ?? '');
                $registeredNome = trim($loan['nome'] ?? '');
                $registeredCpf = trim($loan['cpf'] ?? '');

                $normalizedInputCpf = str_replace(['.', '-', ' '], '', $devolvidoCpf);
                $normalizedRegisteredCpf = str_replace(['.', '-', ' '], '', $registeredCpf);

                if ($registeredNome === '' || $registeredCpf === '') {
                    $_SESSION['msg_error'] = 'O empréstimo não possui dados de responsável registrados.';
                } elseif (mb_strtolower($devolvidoNome) !== mb_strtolower($registeredNome) || $normalizedInputCpf !== $normalizedRegisteredCpf) {
                    $_SESSION['msg_error'] = 'Os dados informados para devolução não conferem com o registro do empréstimo.';
                } else {
                    $livros = $entity->getInfo('livro', $idLivro);
                    if (empty($livros)) {
                        $_SESSION['msg_error'] = 'Livro não encontrado.';
                    } else {
                        $quantidade = intval($livros[0]['quantidade'] ?? 0); 
                        if ($entity->destroyLoan($usuario, $idLivro, $devolvidoNome, $devolvidoCpf)) {
                            $entity->update('livro', ['quantidade' => $quantidade + 1], $idLivro);
                            $_SESSION['msg'] = 'Devolvido com sucesso!';
                        } else {
                            $_SESSION['msg_error'] = 'Erro ao devolver: empréstimo não encontrado.';
                        }
                    }
                }
            }
        } else {
            if ($entity->hasActiveLoan($usuario, $idLivro)) {
                $_SESSION['msg_error'] = 'Ação bloqueada, você já tem o livro emprestado no momento';
            } else {
                $livros = $entity->getInfo('livro', $idLivro);
                if (empty($livros)) {
                    $_SESSION['msg_error'] = 'Livro não encontrado.';
                } else {
                    $livro = $livros[0];
                    $quantidade = intval($livro['quantidade'] ?? 0); //converter um valor qualquer em um número inteiro

                    if ($quantidade <= 0) {
                        $_SESSION['msg_error'] = 'Livro indisponível, sem exemplares em estoque.';
                    } else {
                        $entity->createLoan($usuario, $idLivro, $livro['titulo'], $_POST['nome'] ?? '', $_POST['cpf'] ?? '');
                        $entity->update('livro', ['quantidade' => $quantidade - 1], $idLivro);
                        $_SESSION['msg'] = 'Emprestado com sucesso!';
                    }
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg_error'] = 'Erro ao processar o empréstimo: ' . $e->getMessage();
    }
} else {
    $_SESSION['msg_error'] = 'Requisição inválida ou usuário não autenticado.';
}

header('Location:../view/pagina1.php');
exit;
