<?php
require('../db/conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


//Funcao para envio de email
function  send_email_reset($get_name,$get_email,$token){

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;


    $mail->Host= 'smtp.gmail.com';
    $mail->Username   = 'webdesign.aejl@gmail.com';
    $mail->Password   = 'knoc dife gtor idvp';

    $mail->SMTPSecure = "tls"; 
    $mail->Port = 587;                                 

    //Recipients
    $mail->setFrom('webdesign.aejl@gmail.com',$get_name);
    $mail->addAddress($get_email, $get_name);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Recuperar senha';
    $mail->Body    = "
                <h2>Ola...</h2>
                <h3>Enviamos esta mensagem ao seu pedido para recuperar a sua senha de usuario</h3> <br> <br>
                <a href='http://localhost/Inicio_log/security/reset_pass.php?token=$token&email=$get_email'>Clique aqui</a>

    ";
    $mail->send();
    echo 'Message has been sent';
   
}


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
           echo "Enviamos o link para recupera a senha no teu email";
           header("Location: fogot_pass.php");
           exit(0);
        }else{
            $erro_user = "Algo errado ocorreu. #1";
            header("Location: fogot_pass.php");
            exit(0);
        }
    }else{
        $erro_user ="Email nao encontrado";
        header("Location: fogot_pass.php");
        exit(0);
    }
}

//Recuperar a senha
if(isset($_POST['pass-update'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_pass = mysqli_real_escape_string($con, $_POST['senha']);
    $confirm_pass = mysqli_real_escape_string($con, $_POST['repete_senha']);

    $token = mysqli_real_escape_string($con, $_POST['pass_token']);
    
    if(!empty($token)){
        if(!empty($email) && !empty($new_pass) && !empty($confirm_pass)){
                //Verificar se token e valido
            $sheck_token  = "SELECT token FROM users WHERE token='$token' LIMIT 1";
            $sheck_token_run = mysqli_query($con, $sheck_token);

            if(mysqli_num_rows($sheck_token_run) > 0){
                if($new_pass == $confirm_pass){
                    $update_pass = "UPDATE users set senha='$new_pass' where token='$token' LIMIT 1";
                    $update_pass_run = mysqli_query($con, $update_pass);

                    if($update_pass_run){
                        $new_token = md5(rand())."azimo";

                        $update_new_token = "UPDATE users SET senha='$new_token' WHERE token='$token' LIMIT 1";
                        $update_new_token_run = mysqli_query($con, $update_new_token);
                        
                        echo "Actualzado com sucesso";
                        header("Location: index.php");
                    }else{
                        echo "Nao foi possivel";
                        header("Location: forgot_pass.php?token=$token&email=$email  ");
                    }
                }else{
                    echo "A senhas nao coincidem";
                header("Location: forgot_pass.php?token=$token&email=$email");
                }

            }else{
               echo "Invalid token";
                header("Location: forgot_pass.php?token=$token&email=$email");
            }
    
        }else{
            echo "Todos campos sao obrigatorio";
            header("Location: forgot_pass.php?token=$token&email=$email");
        }
    }else{
        echo "Nao existe um token disponivel";
        header("Location: forgot_pass.php");
    }
}

?>