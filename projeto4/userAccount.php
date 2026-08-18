<?php
//inicia sessão
session_start();

//carrega e utilizar classe do user
include 'user.php';
$user = new User();

if(isset($_POST['signupSubmit'])){
    //verificar se os dados não estão vazios
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) &&
       !empty($_POST['email']) && !empty($_POST['phone']) &&
       !empty($_POST['password']) && !empty($_POST['confirm_password'])){

        //verificar se a confirmacao da password é igual
        if($_POST['password'] != $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg']  = 'Confirme a sua senha deve ser igual.';
        }else{
            //verificação se o utilizador existe na base de dados
            $prevCond['where'] = array(
                'email' => $_POST['email'],
            );
            $prevCond['return_type'] = 'count';
            $prevUser = $user->getRows($prevCond);
            if($prevUser > 0){
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg']  = 'Este email já existe, por favor use outro e-mail.';
            }else{
                //insere dados na base de dados
                $userData = array(
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                    'phone'      => $_POST['phone'],
                    'morada'     => $_POST['morada'],
                    'contribuinte'=> $_POST['contribuinte'],
                    'password'   => md5($_POST['password']),
                );

                $insert = $user->insert($userData);
                if($insert){
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Registo feito com sucesso, faça o login à sua conta.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Ocorreu algum problema, por favor tente novamente.';
                }
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Todos os campos são obrigatórios, por favor preencha todos os campos.';
    }

    //armazena o status de inserção na sessão
    $_SESSION['sessData'] = $sessData;

    //redireciona para a página inicial de registo
    header("Location:registration.php");
}elseif(isset($_POST['loginSubmit'])){
    //check whether login details are empty
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        //verifique se os detalhes de login estão vazios
        $conditions['where'] = array(
            'email'    => $_POST['email'],
            'password' => md5($_POST['password']),
        );
        $conditions['return_type'] = 'single';
        $userData = $user->getRows($conditions);

        //define dados de user e status com base em credenciais de login
        if($userData){
            $sessData['userLoggedIn'] = TRUE;
            $sessData['userID']       = $userData['id'];
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg']  = 'Bem vindo '.$userData['first_name'].'!';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg']  = 'Email ou senha incorretos, tente novamente.';
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Introduza o seu email e password.';
    }

    //armazenar o status de login na sessão
    $_SESSION['sessData'] = $sessData;

    //redireciona para a página de login
    header("Location:index.php");
}elseif(!empty($_REQUEST['logoutSubmit'])){
    //remove sessão de dados da sessão
    unset($_SESSION['sessData']);
    session_unset();
    session_destroy();

    $sessData['status']['type'] = 'success';
    $sessData['status']['msg']  = 'Você fez logout com sucesso da sua conta.';

    //armazena o status de logout na sessão
    $_SESSION['sessData'] = $sessData;

    header("Location:index.php");
}
?>
