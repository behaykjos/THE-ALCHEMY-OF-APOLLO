# The Alchemy of Apollo

Projeto web desenvolvido em PHP, HTML, CSS, JavaScript, Bootstrap e MySQL. A aplicação apresenta informações acerca do sol e inclui funcionalidades de autenticação, perfis de utilizador e interação entre utilizadores.

## Funcionalidades principais

- Registo de utilizadores;
- Login tradicional com e-mail e palavra-passe;
- Login através do Google Identity Services;
- Login automático após o registo;
- Sessões PHP e logout;
- Palavra-passe protegida com `password_hash()`;
- Perfis de utilizador e edição de perfil;
- Upload de imagens de perfil e publicações;
- Criação e visualização de publicações;
- Comentários e respostas a comentários;
- Sistema de seguidores;
- Pesquisa de utilizadores;
- Comunidade de utilizadores;
- Tema claro e escuro;
- Layout responsivo com Bootstrap;
- Páginas informativas sobre Apollo e Michael Jackson.

## Tecnologias utilizadas

- PHP
- MySQL/MariaDB
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- jQuery
- Google Identity Services
- Font Awesome
- Google Fonts

## Requisitos

- Windows;
- XAMPP, WAMP ou outro servidor PHP;
- PHP 7.4 ou superior;
- MySQL ou MariaDB;
- Extensão PHP MySQLi;
- Navegador com JavaScript ativado.

## Instalação com XAMPP

1. Copie esta pasta para:

   ```text
   C:\xampp\htdocs\
   ```

2. Abra o XAMPP e inicie os módulos **Apache** e **MySQL**.

3. Abra o phpMyAdmin em:

   ```text
   http://localhost/phpmyadmin
   ```

4. Crie uma base de dados com o nome:

   ```text
   alchemy_of_apollo
   ```

5. Confirme as credenciais no ficheiro `config.php`:

   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "alchemy_of_apollo";
   ```

6. Crie ou importe as tabelas necessárias para utilizadores, publicações, comentários e seguidores. Se necessário, utilize o ficheiro `projeto5/setup_db.php` como referência para a configuração da base de dados.

7. Aceda ao projeto através de:

   ```text
   http://localhost/Trabalho_Bootstrap_Karen_Gomes_6_12F/
   ```

## Estrutura principal

```text
Trabalho_Bootstrap_Karen_Gomes_6_12F/
│
├── config.php                  # Ligação à base de dados principal
├── index.php                   # Página inicial
├── login.php                   # Login e registo
├── google-login.php            # Autenticação com Google
├── logout.php                  # Terminar sessão
├── profile.php                 # Perfil do utilizador autenticado
├── user.php                    # Visualização de perfil
├── edit-profile.php            # Edição do perfil
├── community.php               # Área da comunidade
├── new_post.php                # Formulário de nova publicação
├── post.php                    # Visualização de publicação
├── post_action.php             # Operações das publicações
├── comment_action.php          # Operações dos comentários
├── send_comment.php            # Envio de comentários
├── load_comments.php           # Carregamento de comentários
├── load_replies.php            # Carregamento de respostas
├── follow_action.php           # Sistema de seguidores
├── search_users.php             # Pesquisa de utilizadores
├── haroldo styles.css          # Estilos personalizados
│
├── Mídias/                     # Imagens, ícones e outros recursos
├── uploads/                    # Ficheiros enviados pelos utilizadores
├── projeto4/                   # Módulo complementar de loja
└── projeto5/                   # Módulo complementar de loja
```

## Páginas de conteúdo

O projeto inclui páginas temáticas e informativas, entre elas:

- `inthebenigging.php`
- `aprotagonista.php`
- `euouvidizer.php`
- `juropordeus.php`
- `showsdosol.php`
- `SUS.php`

Estas páginas são acessíveis a partir da navegação da aplicação.

## Módulos `projeto4` e `projeto5`

As pastas `projeto4` e `projeto5` contêm versões/módulos complementares relacionados com uma loja online, incluindo:

- Produtos;
- Carrinho de compras;
- Checkout;
- Registo e login;
- Pesquisa;
- Gestão de encomendas;
- Configuração própria de base de dados.

O `projeto5` inclui ficheiros como `Cart.class.php`, `cartAction.php`, `viewCart.php`, `checkout.php` e `orderSuccess.php`.

## Login com Google

O ficheiro `login.php` utiliza o Google Identity Services. Para ativar esta funcionalidade em ambiente local ou de produção:

1. Crie um projeto na Google Cloud Console;
2. Configure um Client ID OAuth;
3. Adicione o domínio e os URI autorizados;
4. Atualize o `data-client_id` no `login.php`;
5. Confirme o endereço utilizado pelo `google-login.php`.

Em produção, substitua os endereços `http://localhost` por URLs HTTPS válidos.

## Segurança

O projeto utiliza prepared statements e funções nativas do PHP para proteger as credenciais. Recomenda-se ainda:

- Utilizar HTTPS em produção;
- Não publicar as credenciais da base de dados;
- Validar e limitar os ficheiros enviados para `uploads/`;
- Escapar conteúdo apresentado no HTML com `htmlspecialchars()`;
- Desativar mensagens detalhadas de erro em produção;
- Configurar corretamente os domínios autorizados do Google Login.

## Resolução de problemas

### Erro de ligação à base de dados

Verifique se o MySQL está ativo no XAMPP e se o nome da base de dados em `config.php` está correto.

### Imagens não aparecem

Confirme se a pasta `uploads/` existe e se os caminhos das imagens estão corretos.

### Login com Google não funciona

Confirme o Client ID, o URI de login e os domínios autorizados na Google Cloud Console.

### Página PHP não abre

Confirme se o Apache está ativo e se o projeto está dentro de `C:\xampp\htdocs\`.

## Autoria

Projeto desenvolvido por **Karen Gomes** para fins educativos.

## Licença

Este projeto destina-se a fins académicos e educativos. Os conteúdos multimédia utilizados devem respeitar os respetivos direitos de autor.
