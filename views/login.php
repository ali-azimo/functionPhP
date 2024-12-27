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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".././boot/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>

<body>
    <section class="Form-general">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-5">
                    <div class="card bg-light p-5 shadow">
                        <h3>Login</h3>
                        <hr>

                        <?php if(!empty($erro_login)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $erro_login ?>
                        </div>

                        <?php endif;?>

                        <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
                            <h6 class="alert alert-success text-center sucess mt-3 p-2">
                                Cadastrado com sucesso!
                            </h6>
                        <?php }?>

                        <form action="" method="Post">
                            <div class="mb-3">
                                <input type="email" name="email" placeholder="Usuario" class="form-control">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="senha" placeholder="Senha" class="form-control">
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <p>Esqueceu a senha?</p>
                                <a href="../security/fogot_pass.php">Recuperar</a>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary w-100">Entrar</button>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <p>Ainda não está cadastrado?</p>
                                <a href="./signup.php">Cadastrar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../js/jquery-3.6.3.min.js"></script>
    <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
        <script>
            setTimeout(() =>{
                $('.sucess').hide()
            }, 3000);
        </script>
    <?php }?>
</body>

</html>