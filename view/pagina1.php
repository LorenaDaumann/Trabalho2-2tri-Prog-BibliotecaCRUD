<?php
session_start();

require_once('../model/Entity.class.php');

$livroEntity = new Entity();
$usuario = $_SESSION['usuario'] ?? null;
$emprestimos = [];
if (!empty($usuario)) {
    $emprestimos = $livroEntity->listLoansByUser($usuario);
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body id="B2">
    <img src="../assets/img/Banner2 - Editado.jpg">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3"> <!--botão de saída da sessão-->
            <h1 id="MsgP1">Olá, <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Usuário'); ?>!</h1>
            <form action="../controller/logout.php" method="POST">
                <button type="submit" class="btn btn-danger" id="BtnSair">Sair</button>
            </form>
        </div>

        <?php if (isset($_SESSION['msg'])) : ?> <!--mensagem de sucesso ao emprestar o livro-->
            <div class="alert alert-success"><?php echo $_SESSION['msg']; ?></div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['msg_error'])) : ?> <!--mensagem de erro ao emprestar-->
            <div class="alert alert-danger"><?php echo $_SESSION['msg_error']; ?></div>
            <?php unset($_SESSION['msg_error']); ?>
        <?php endif; ?>

        <div class="mb-3">
            <a href="./livroRegister.php" class="btn btn-success" id="BtnInserir">Inserir</a>
            <button class="btn btn-primary tab-button active" data-target="livrosTab" id="BtnLivros">Livros</button>
            <button class="btn btn-secondary tab-button" data-target="historicoTab" id="BtnEmprestimo">Meus Empréstimos</button>
        </div>

        <div id="livrosTab" class="tab-content-section">
            <div class="table-responsive">
                <table border="1px" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livroEntity->list('livro') as $livro) { ?> <!--lista de livros-->
                            <tr>
                                <td><?php echo htmlspecialchars($livro['id']); ?></td>
                                <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($livro['autor']); ?></td>
                                <td><?php echo htmlspecialchars($livro['editora']); ?></td>
                                <td><?php echo htmlspecialchars($livro['ano']); ?></td>
                                <td><?php echo htmlspecialchars($livro['quantidade']); ?></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <form method="POST" action="./livroUpdate.php">
                                            <input type="hidden" name="id" value="<?= $livro['id'] ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm" id="BtnAlterar">Alterar</button>
                                        </form>
                                        <form method="POST" action="../controller/DeleteLivro.php" onsubmit="return confirm('Deseja realmente excluir?');">
                                            <input type="hidden" name="id" value="<?= $livro['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" id="BtnDeletar">Deletar</button>
                                        </form>
                                        <button type="button" class="btn btn-success btn-sm open-loan-modal" data-action="emprestar" data-book-id="<?= $livro['id'] ?>" data-book-title="<?= htmlspecialchars($livro['titulo'], ENT_QUOTES) ?>" id="BtnEmprestar">
                                            Emprestar
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm open-loan-modal" data-action="devolver" data-book-id="<?= $livro['id'] ?>"
                                         data-book-title="<?= htmlspecialchars($livro['titulo'], ENT_QUOTES) ?>" <?= !empty($usuario) && $livroEntity->hasActiveLoan($usuario, $livro['id']) ? '' : 'disabled' ?> id="BtnDevolver">
                                            Devolver
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="historicoTab" class="tab-content-section d-none">
            <h2>Histórico de Empréstimos</h2>
            <div class="table-responsive">
                <table border="1px" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Livro</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Status</th>
                            <th>Data do Empréstimo</th>
                            <th>Apagar Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($emprestimos)) : ?>
                            <tr>
                                <td colspan="7">Nenhum empréstimo registrado.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($emprestimos as $emprestimo) : ?> <!--lista de registro dos emprestimos-->
                                <tr>
                                    <td><?php echo htmlspecialchars($emprestimo['id']); ?></td>
                                    <td><?php echo htmlspecialchars($emprestimo['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($emprestimo['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($emprestimo['cpf']); ?></td>
                                    <td><?php echo htmlspecialchars($emprestimo['status']); ?></td>
                                    <td><?php echo htmlspecialchars($emprestimo['data_emprestimo']); ?></td>
                                    <td> 
                                        <form method="POST" action="../controller/DeleteEmprestimo.php" onsubmit="return confirm('Deseja realmente excluir?');">
                                            <input type="hidden" name="id" value="<?= $emprestimo['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" id="BtnDeletar">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="emprestimoModal" class="modal-overlay d-none">
        <div class="modal-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" id="modalTitle">Registrar empréstimo</h5>
                <button type="button" class="btn-close" id="closeLoanModal" aria-label="Fechar"></button>
            </div>
            <p id="modalBookTitle" class="mb-3 fw-semibold"></p>
            <form method="POST" action="../controller/emprestimoLivro.php">
                <input type="hidden" name="id" id="modalBookId">
                <input type="hidden" name="action" id="modalAction" value="emprestar">
                <div class="mb-3">
                    <label for="modalNome" class="form-label">Nome do responsável</label>
                    <input type="text" id="modalNome" name="nome" class="form-control" placeholder="Nome do responsável" required>
                </div>
                <div class="mb-3">
                    <label for="modalCpf" class="form-label">CPF</label>
                    <input type="text" id="modalCpf" name="cpf" class="form-control" placeholder="CPF" required>
                </div>
                <button type="submit" class="btn btn-success me-2" id="modalSubmitButton">Confirmar empréstimo</button>
                <button type="button" class="btn btn-secondary" id="cancelLoanModal">Cancelar</button>
            </form>
        </div>
    </div>


    <script>
        //sistema de abas, sem recarregar a página ao clicar no botão
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.tab-button');
            const sections = document.querySelectorAll('.tab-content-section');

            buttons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const target = this.dataset.target;
                    buttons.forEach(btn => btn.classList.remove('active'));
                    sections.forEach(section => section.classList.add('d-none'));
                    this.classList.add('active');
                    document.getElementById(target).classList.remove('d-none');
                });
            });

            //captura de infos e armazenando em constantes
            const modal = document.getElementById('emprestimoModal');
            const modalBookId = document.getElementById('modalBookId');
            const modalAction = document.getElementById('modalAction');
            const modalTitle = document.getElementById('modalTitle');
            const modalBookTitle = document.getElementById('modalBookTitle');
            const modalNome = document.getElementById('modalNome');
            const modalCpf = document.getElementById('modalCpf');
            const modalSubmitButton = document.getElementById('modalSubmitButton');
            const closeLoanModal = document.getElementById('closeLoanModal');
            const cancelLoanModal = document.getElementById('cancelLoanModal');

            //adiciona um evento de clique a todos os botões que abrem o modal
            document.querySelectorAll('.open-loan-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.dataset.action || 'emprestar';
                    modalAction.value = action;
                    modalBookId.value = this.dataset.bookId;
                    modalBookTitle.textContent = 'Livro: ' + this.dataset.bookTitle;
                    modalNome.value = '';
                    modalCpf.value = '';
                    if (action === 'devolver') {
                        modalTitle.textContent = 'Registrar devolução';
                        modalSubmitButton.textContent = 'Confirmar devolução';
                    } else {
                        modalTitle.textContent = 'Registrar empréstimo';
                        modalSubmitButton.textContent = 'Confirmar empréstimo';
                    }
                    modal.classList.remove('d-none');
                });
            });

            const closeModal = () => modal.classList.add('d-none');

            closeLoanModal.addEventListener('click', closeModal);
            cancelLoanModal.addEventListener('click', closeModal);
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</body>