<?php
require('../db/conn.php');
require_once('../public/index.php');


//VERIFICAR SE EXISTE UMA POSSTAGEM NOS INPUT
if(isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){

    //VERIFICAR SE TODAS AS POSTAGEN FORAM PREENCHIDAS
    if(empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha'])){
        $erro_geral = "Todos campos são obrigatórios!";
    }else{
        //Receber do post e limpar
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $token = limparPost($_POST['pass_token']);
        
        //Senha criptogrfada
        $senha_Cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        
        if ( ! preg_match("/[a-z]/i", $senha)) {
            $erro_senha = "A senha deve ter letras e numeros";
        }
        //Validacao de senha + de 8 disgitos
        if(strlen($senha) < 6 ){
            $erro_senha = "A senha deve ter 8 digitos ou mais";
        }
        if ( ! preg_match("/[a-z]/i", $senha)) {
            $erro_senha = "A senha deve ter letras e numeros";
        }
        
        //verificar se repete senha e igual
        if($senha !== $repete_senha){
            $erro_RepSenha = "A senha nao confere";
        }
            //Recuperar a senha
        if(isset($_GET['token'])) {
        $token = $_GET['token'];
        $senha_cript = sha1($senha);


        $usuario = $con->query("SELECT * FROM users WHERE token = '$token' limit 1");

        if($usuario->num_rows == 0) {
            echo "Mudado com sucesso";
            header("Location: ../views/login.php");
            exit();
        }

        if(isset($_POST['senha'])) {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $senha_cript = sha1($senha);


            $usuario = $con->query("UPDATE users SET senha = '$senha_cript' WHERE email = '$email' limit 1");

            if($usuario) {
                header("Location: ../views/login.php");

                $msg = "Senha actualizada com sucesso";
                    }
            }$con->close();
            }else 
            {
            header("Location: ../views/login.php");
            $msg = "Ja pode logar";

            exit();
        }
    }
    
     
}
 
?>
    <section class="Form-general">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-5">
                    <div class="card bg-light p-5 shadow">
                        <h3>Redinir senha</h3>
                        <hr>
                        <form action="" method="POST">

                        <?php if(!empty($erro_geral)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $erro_geral ?>
                        </div>
                         <?php endif;?>
                        <?php if(!empty($erro_senha) && !empty($erro_RepSenha)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $erro_senha ?>
                        </div>
                         <?php endif;?>
                        
                            <div class="mb-3">
                                <input type="hidden" name="pass_token" placeholder="Senha" class="form-control"
                                value="
                                <?php
                                if(isset($_GET['token'])) {echo $_GET['token'];}?>"
                                
                                >
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" placeholder="email" class="form-control"
                                value="
                                <?php
                                if(isset($_GET['email'])) {echo $_GET['email'];}?>"
                                >
                            </div>
                        
                            <!-- Entrada de Senha -->
                            <div class="mb-3">
                                <input type="password" name="senha" placeholder="Senha" class="form-control"
                                <?php if(isset($_POST['senha'])) echo "value = '".$_POST['senha']."'";?>
                                >
                                <?php if(isset($erro_senha)){ ?>
                                    <h6 class="text-danger text-center 4"><?php echo $erro_senha; ?></h6>
                                <?php } ?>
                            </div>

                            <!-- Entrada de repete senha -->
                            <div class="mb-3">
                                <input type="password" name="repete_senha" placeholder="Repete a senha" class="form-control"
                                <?php if(isset($_POST['repete_senha'])) echo "value = '".$_POST['repete_senha']."'";?>
                                >
                                <?php if(isset($erro_RepSenha)){ ?>
                                    <h6 class="text-danger text-center p4"><?php echo $erro_RepSenha; ?></h6>
                                <?php } ?>
                            </div>

                            <div class="">
                                <button type="submit" name="pass-update" class="btn btn-primary w-100">Redifinir senha</button>
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