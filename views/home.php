<?php
require('../db/conn.php');

//Verificar se esta cadastrado
require_once __DIR__ . '/../public/index.php';

//Verificar Auteticacao
$user = auth($_SESSION['TOKEN']);
if($user){

    echo "<strong class='d-none'>Seja bem vindo   ".$user['nome']."</strong>";
    echo "<a href='logout.php' class='d-none'>Sair</a>";
}else{
    //Rediionar
    header('location: login.php');
}

?>

<nav class="container mt-5 p-4 border rounded-3 shadow-sm bg-light">
    <div class="row align-items-center">
        <div class="col">
            <h4>Apicacao  PHP</h4>
        </div>

        <div class="col text-center">
            <a href="home.php">home</a>
            <span class="mx-2">|</span>
            <a href="?rota=page1">page1</a>
            <span class="mx-2">|</span>
            <a href="?rota=page2">page2</a>
            <span class="mx-2">|</span>
            <a href="?rota=page3">page3</a>
            
        </div>


        <div class="col text-end">
            <span><strong><?= $user['nome']?></strong></span>
            <span class="mx-2">|</span>
            <a href="logout.php">Sair</a>
        </div>
    </div>
</nav>



<div class="">
    <h4>Home</h4>

    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe aspernatur corporis, sit enim quod ab, temporibus odio delectus, quas iste officiis laudantium! Nemo perferendis laboriosam et. Magni perspiciatis quisquam adipisci architecto nemo placeat praesentium, ea ratione ut nisi impedit officia.</p>
</div>