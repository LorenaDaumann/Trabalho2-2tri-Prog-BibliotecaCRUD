<?php
    //classe que vai manipular o banco de dados
    //classe genérica que serve pra manipular tudo; extends:herança
    require_once("Conexao.class.php");

    class Entity extends Conexao{

        //Listar
        public function list($table)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table ORDER BY id ASC";

            $statement = $pdo->query($sql);
            $statement->execute(); //-> : acessar um método ou uma propriedade

            return $statement->fetchAll(); //transforma a tabela do banco em um vetor 
        }

        //Listar
        public function listEmprestadoLivro($table,$titulo)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table where titulo='$titulo' and status='Emprestado' ORDER BY id ASC";

            $statement = $pdo->query($sql);
            $statement->execute(); //-> : acessar um método ou uma propriedade

            return $statement->fetchAll(); //transforma a tabela do banco em um vetor 
        }

        public function ensureLoanTable()
        {
            $pdo = parent::getInstance();
            $sql = "CREATE TABLE IF NOT EXISTS emprestimos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario VARCHAR(255) NOT NULL,
                livro_id INT NOT NULL,
                titulo VARCHAR(255) NOT NULL,
                nome VARCHAR(255) NULL,
                cpf VARCHAR(50) NULL,
                devolvido_por_nome VARCHAR(255) NULL,
                devolvido_por_cpf VARCHAR(50) NULL,
                status VARCHAR(50) NOT NULL,
                data_emprestimo DATETIME NOT NULL,
                data_devolucao DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $pdo->exec($sql);

            $this->ensureColumnExists('emprestimos', 'nome', 'VARCHAR(255) NULL');
            $this->ensureColumnExists('emprestimos', 'cpf', 'VARCHAR(50) NULL');
            $this->ensureColumnExists('emprestimos', 'devolvido_por_nome', 'VARCHAR(255) NULL');
            $this->ensureColumnExists('emprestimos', 'devolvido_por_cpf', 'VARCHAR(50) NULL');
        }

        public function ensureColumnExists($table, $column, $definition)
        {
            $pdo = parent::getInstance();
            $sql = "SHOW COLUMNS FROM `$table` LIKE :column";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':column', $column);
            $statement->execute();

            if ($statement->rowCount() === 0) {
                $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            }
        }

        public function hasActiveLoan($usuario, $livro_id)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT COUNT(*) FROM emprestimos WHERE usuario = :usuario AND livro_id = :livro_id AND status = 'Emprestado'";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":usuario", $usuario);
            $statement->bindValue(":livro_id", $livro_id);
            $statement->execute();

            return $statement->fetchColumn() > 0;
        }

        public function getActiveLoan($usuario, $livro_id)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM emprestimos WHERE usuario = :usuario AND livro_id = :livro_id AND status = 'Emprestado' ORDER BY data_emprestimo DESC LIMIT 1";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":usuario", $usuario);
            $statement->bindValue(":livro_id", $livro_id);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public function createLoan($usuario, $livro_id, $titulo, $nome = '', $cpf = '')
        {
            $pdo = parent::getInstance();
            $sql = "INSERT INTO emprestimos (usuario, livro_id, titulo, nome, cpf, status, data_emprestimo) VALUES (:usuario, :livro_id, :titulo, :nome, :cpf, 'Emprestado', NOW())";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":usuario", $usuario);
            $statement->bindValue(":livro_id", $livro_id);
            $statement->bindValue(":titulo", $titulo);
            $statement->bindValue(":nome", $nome);
            $statement->bindValue(":cpf", $cpf);
            $statement->execute();
        }

        public function destroyLoan($usuario, $livro_id, $devolvido_nome = '', $devolvido_cpf = '')
        {
            $pdo = parent::getInstance();
            $sql = "UPDATE emprestimos SET status = 'Devolvido', data_devolucao = NOW(), devolvido_por_nome = :devolvido_nome, devolvido_por_cpf = :devolvido_cpf WHERE usuario = :usuario AND livro_id = :livro_id AND status = 'Emprestado'";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":usuario", $usuario);
            $statement->bindValue(":livro_id", $livro_id);
            $statement->bindValue(":devolvido_nome", $devolvido_nome);
            $statement->bindValue(":devolvido_cpf", $devolvido_cpf);
            $statement->execute();

            return $statement->rowCount();
        }

        public function listLoansByUser($usuario)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM emprestimos WHERE usuario = :usuario ORDER BY data_emprestimo DESC";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":usuario", $usuario);
            $statement->execute();

            return $statement->fetchAll();
        }

        //INSERT
        public function insert($table,$data)
        {
            $pdo = parent::getInstance();
            $fields = implode(", ",array_keys($data));
            $values = ":".implode(", :",array_keys($data));

            $sql="INSERT INTO $table($fields) VALUES ($values)";
            $statement = $pdo->prepare($sql);

            foreach($data as $key => $value){
                $statement->bindValue(":$key",$value,PDO::PARAM_STR);
            }

            $statement->execute();

        }


        public function delete($table,$id)
        {
            $pdo = parent::getInstance();
            $sql = "DELETE FROM $table WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":id",$id);
            $statement->execute();
        }

        public function getInfo($table,$id)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":id",$id);
            $statement->execute();

            return $statement->fetchAll();
        }

        public function getInfoTitulo($table,$titulo)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table WHERE titulo = :titulo";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(":titulo",$titulo);
            $statement->execute();

            return $statement->fetchAll();
        }

        public function get($table)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table";
            $statement = $pdo->prepare($sql);
            $statement->execute();

            return $statement->fetchAll();
        }

        public function update($table,$data,$id)
        {
            $pdo = parent::getInstance();
            $new_values = "";
            foreach($data as $key => $value)
            {
                $new_values .= "$key=:$key, ";
            }
            $new_values = substr($new_values,0,-2);
            $sql = "UPDATE $table SET $new_values WHERE id = :id";
            $statement = $pdo->prepare($sql);
            foreach($data as $key => $value)
            {
                $statement->bindValue(":$key",$value,PDO::PARAM_STR);
            }
            $statement->bindValue(":id",$id);
            $statement->execute();
        }


        public function autocomplete($table,$texto)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table WHERE titulo LIKE '%$texto%'";
           
            $statement = $pdo->query($sql);
            $statement->execute();

            $return_arr = [];
            while($row = $statement->fetch()) {
               $return_arr[] =  $row['titulo'];
           }

            return $return_arr;
        }

        public function login($table,$usuario,$senha)
        {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $table WHERE usuario = ? AND senha   = ? ";
    
            $statement = $pdo->prepare($sql);
            $statement->execute([$usuario, $senha]);

            $return_arr = [];
            while($row = $statement->fetch()) {
               $return_arr[] =  $row['usuario'];
            }

            return $return_arr;
        }

    
    }

?>