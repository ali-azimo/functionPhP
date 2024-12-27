<?php
require('../db/conn.php');


//Verificar se o botao foi clicado
if(isset($_POST['pass-reset'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());

    $sheck_email = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $sheck_email_run = mysqli_query($con, $sheck_email);

    //Verificar o email
    if(mysqli_num_rows($sheck_email_run) > 0){
        $row = mysqli_fetch_array($sheck_email_run);
        $get_name = $row['nome'];
        $get_email = $row['email'];

        //Update token
        $update_token = "UPDATE users SET token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($con, $update_token);

        //Se tive sucesso
        if($update_token_run){
           send_email_reset($get_name,$get_email,$token);
           $user = "Enviamos o link para recupera a senha no teu email";
           header("Location: fogot_pass.php");
           exit(0);
        }else{
            $erro_login = "Algo errado ocorreu. #1";
            header("Location: fogot_pass.php");
            exit(0);
        }
    }else{
        echo "Email nao encontrado";
        header("Location: fogot_pass.php");
        exit(0);
    }
}

?>