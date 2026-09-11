# Para executar o código:
- Tenha o Xamp instalado e habilite as funções Apache e MySQL dentro do app

- Vá no link: https://demo.phpmyadmin.net/master-config/public/
- Clique em Novo, escreva o nome do banco como "Aula" e crie ele (Enter)
- Vá na aba SQL
- Cole o seguinte código:
  
      create database aula;
      use aula;
      
      create table usuario(
          usuario VARCHAR(60) PRIMARY KEY NOT NULL,
          senha VARCHAR(60) NOT NULL
      );
      
      INSERT INTO usuario(usuario, senha)
      VALUES ('admin', 'admin'),
             ('teste', '123');
      
      
      CREATE TABLE livro(
          id INT PRIMARY KEY auto_increment,
          titulo VARCHAR(60),
          autor VARCHAR(60),
          editora VARCHAR(60),
          ano INT,
          quantidade INT
      );
      
      INSERT INTO livro(titulo, autor, editora, ano, quantidade)
      VALUES ('Dom Quixote', 'Miguel de Cervantes', 'PNLD', 1605, 5),
             ('O diário de Anne Frank', 'Anne Frank', 'PNLD', 1947, 3),
             ('A hora da estrela', 'Clarice Lispector', 'PNLD', 1977, 9);
             
      CREATE TABLE emprestimos (
          id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
          usuario VARCHAR(255) NOT NULL,
          livro_id INT(11) NOT NULL,
          titulo VARCHAR(255) NOT NULL,
          status VARCHAR(50) NOT NULL,
          data_emprestimo DATETIME NOT NULL,
          data_devolucao DATETIME DEFAULT NULL
      ) 

- Cole em seu navegador o link: localhost/nomedapasta - Exemplo: localhost/Trabalho2-2tri-Prog-BibliotecaCRUD (sem o Xamp e o banco dará erro)
