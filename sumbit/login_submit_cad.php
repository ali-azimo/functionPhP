<?php

require('../db/conn.php');

if(isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){
    //Receber dados do post e limpa
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);


    //Verificar se usuaro existe
    $sql = $pdo->prepare("SELECT * FROM users WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$senha_cript));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if($usuario){
        //Existe usuario
        //Criar token (sequencia de numero e letra de usuario id)
        if($usuario['status']=="confirmado"){
            $token = sha1(uniqid().date('d-m-Y-H-i-s'));

            //Actualizar o token no banco
            $sql = $pdo->prepare("UPDATE users SET token=? WHERE email=? AND senha=?");
            if($sql->execute(array($token, $email, $senha_cript))){
                //Armazenar na SESSAO
                $_SESSION['TOKEN'] = $token;
                header('location: restrita.php');
            }
        }else{
            $erro_login = "Confirme o seu cadastro no email";
        } 
        
    }else{
        $erro_login = "Usuario ou senha invalida";
    }
}
?>