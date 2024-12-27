<?php

require('../db/conn.php');

//VERIFICAR SE EXISTE UMA POSSTAGEM NOS INPUT
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){

    //VERIFICAR SE TODAS AS POSTAGEN FORAM PREENCHIDAS
    if(empty($_POST['nome']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha'])){
        $erro_geral = "Todos campos são obrigatórios!";
    }else{
        //Receber do post e limpar
        $nome = limparPost($_POST['nome']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        
        //Senha criptogrfada
        $senha_Cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);

        //Valodar caracter de nomes para que seja nome valido
        if(!preg_match("/^[a-zA-Z- ']*$/",$nome)){
            $erro_nome = "Apenas letras e epacos em branco";
        }
        //Verificar se o email e valido
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro_email = "Email invalido";
        }
        
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
       
    
        //Inserir no Banco caso nao haja erros
        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_RepSenha)){

            //Verificar se usuario esta cadastrado
            $sql = $pdo ->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuario = $sql->fetch();
            //caso nao exista usuario com email cadastrado - cadastrar
            if(!$usuario){
                $token = "";
                $status = "novo";
                $dataCadastro = date('d-m-Y');
                $sql = $pdo->prepare("INSERT INTO users VALUES (null,?,?,?,?,?,?)");
                if($sql->execute(array($nome, $email, $senha_Cript, $token, $status, $dataCadastro))){
                    //local
                    if($modo == "local"){
                        //caso estej tudo ok redicionar
                    header('location: login.php?result=ok');
                    }
                }
            }else{
                //Caaso exista apresentar erro
                $erro_geral = "Usuario Cadastrado";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../boot/css/bootstrap.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>

<body>
    <section class="Form-general">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-5">
                    <div class="card bg-light p-5 shadow">  
                    <h3>Sign UP</h3>
                    <h6 class="fs-fs-4"> --AzimoDeveloper--</h6>
                    <hr>
                    
                    <?php if(!empty($erro_geral)): ?>
                        <div class="alert alert-danger mt-3 p-2 text-center animate__animated animate__rubberBand">
                            <?= $erro_geral ?>
                        </div>

                    <?php endif;?>

                        
                        <form action="" method="Post">

                            <!-- Entrada de nome -->
                            <div class="mb-3">
                                <input type="nome" name="nome" placeholder="Digite o seu nome" class="form-control"
                                <?php if(isset($_POST['nome'])) echo "value = '".$_POST['nome']."'";?>
                                >
                                
                                <?php if(isset($erro_nome)){ ?>
                                    <h6 class="text-danger text-center p-4"><?php echo $erro_nome; ?></h6>
                                <?php } ?>
                            </div>

                            <!-- Entrada de email -->
                            <div class="mb-3">
                                <input type="email" name="email" placeholder="Digite email" class="form-control"
                                <?php if(isset($_POST['email'])) echo "value = '".$_POST['email']."'";?>
                                >

                                <?php if(isset($erro_email)){ ?>
                                    <h6 class="text-danger text-center p-4"><?php echo $erro_email; ?></h6>
                                <?php } ?>
                            </div>

                            <!-- Entrada de Senh -->
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

                            <!-- Botao de envio de dados -->
                            <div class=" ">
                                <button type="submit " class="btn btn-primary w-100 ">Cadastrar</button>
                            </div>

                            <!-- Entrada de esqueceu senha -->
                            <div class="d-flex gap-2 mt-2 ">
                                <p>Lembrou a senha?</p>
                                <a href="./login.php ">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>