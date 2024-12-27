<?php

require('../db/conn.php');
require_once('../public/index.php');


//Mailer para enviar mensagem

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


//Funcao para envio de email
function  send_email_reset($email,$token){

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
    $mail->setFrom('webdesign.aejl@gmail.com');
    $mail->addAddress($email);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Recuperar senha';
    $mail->Body    = "
                <h2>Ola...</h2>
                <h3>Enviamos esta mensagem ao seu pedido para recuperar a sua senha de usuario</h3> <br> <br>
                <a href='http://localhost/Inicio_log/security/reset_pass.php?token=$token&email=$email'>Clique aqui</a>

    ";
    if($mail->send()){
        echo 'Message has been sent';  

    }else{
        echo "Erro ao enviar a mensagem, verifique sua conexao com a internet";
    }
}

//Verificar se o botao foi clicado
if(isset($_POST['pass-reset'])){

    if(empty($_POST['email'])){
        $erro = "O campo email e obrigatorio";
    }else{
        //Receber dados do post e limpa
        $email = limparPost($_POST['email']);

    //Verificar se usuaro existe
    $sql = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $sql->execute(array($email,));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

   
        if($usuario){
            $token = sha1(uniqid().date('d-m-Y-H-i-s'));

            //Actualizar o token no banco
            $sql = $pdo->prepare("UPDATE users SET token=? WHERE email=? LIMIT 1");
            if($sql->execute(array($token,$email))){
                send_email_reset($email,$token);
                //Armazenar na SESSAO
                $msg = "Enviamos o link para recupera a senha no teu email";
                $_SESSION['TOKEN'] = $token;
                header('location: fogot_pass.php');
            }else{
                $erro_user = "Algo errado ocorreu. #1";
                header("Location: fogot_pass.php");
            }
        }else{
            $erro = "Email nao cadastrado";
        } 
    }
}

?>
    <section class="Form-general">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-5">
                    <div class="card bg-light p-5 shadow">
                        <h3>Recuperar senha</h3>
                        <hr>                       

                        <!-- Mensam de erro -->
                        <?php if(isset($erro)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $erro ?>
                        </div>
                        <?php endif;?>

                        <?php if(isset($msg)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $msg ?>
                        </div>

                        <?php endif;?>

                        <form action="" method="Post">
                            <div class="mb-3">
                                <input type="email" name="email" placeholder="Usuario" class="form-control">
                            </div>

                            <div class="">
                                <button type="submit" name="pass-reset" class="btn btn-primary w-100">Envio de Link para cecuperar senha</button>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <p>Lembrou a senha?</p>
                                <a href="../views/login.php">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
