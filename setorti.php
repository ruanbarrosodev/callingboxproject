<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor TI</title>
</head>
<body>
<?php
require('connection.php');

if(isset($_POST['editStatus'])){
    $sql = "
    update calling
    set status='".$_POST['status']."'
    where idCalling=".$_POST['idCalling'];
    mysqli_query($conn, $sql);
}
?>
<main>
    <!-- Author Ruan Barroso -->
    <h1> Admin</h1>
        <div class="callings">
            <h2>Chamados: </h2>
            <div class="container-calling">
                <section>ID:</section>
                <section>Horario:</section>
                <section>Setor: </section>
                <section>Servidor: </section>
                <section>Tipo: </section>
                <section>Estado:</section>
                <section>Alterar:</section>
            </div>
            <?php 
            $result = mysqli_query($conn,"select * from calling order by idCalling desc limit 10");
            while($dados = mysqli_fetch_array($result)){
                $dates = date('d/m/Y H:i:s',strtotime($dados['time']));
            ?>            
            <div class="container-calling span">
                <section><?=$dados['idCalling']?></section>
                <section><?=$dates?></section>
                <section><?=$dados['sector']?></section>
                <section><?=$dados['server']?></section>
                <section><?=$dados['type']?></section>
                <section><?=$dados['status']?></section>
                <section>
                    <form action="" class="formEdit" method="POST">
                        <input type="hidden" name="idCalling" value="<?=$dados['idCalling']?>">
                        <select name="status">
                            <option value="Em progresso"> Em progresso </option>
                            <option value="Finalizado"> Finalizado </option>
                        </select>
                        <input type="submit" name="editStatus" value="Salvar">
                    </form>
                </section>
            </div>
            <?php } ?>
        </div>
</main>
<section class="login">
    <div class="putPassword">
                    
    </div>    
</section>
<style>
.login{
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    background-color: #232323;
    display: flex;
    justify-content: center;
    align-items: center;
}
.putPassword {
    background-color: #fff;
    width: 600px;
    height: 450px;
    border-radius: 10px;
}
.formEdit{
    display: flex;
    flex-direction: column;
}
.formEdit select, .formEdit input{
    padding: 5px;
}
* , body{
    margin: 0;
    box-sizing: border-box;
    border-spacing: 0;
    padding: 0;
    font-family: sans-serif;
}
main{
    background-color: lightblue;
    width: 90%;
    margin: 0 auto;
    padding: 20px;
}
h1,h2{
    margin-top: 10px;
    text-align: center;
}
.forms{
    display: flex;
    flex-wrap: wrap;
    padding: 5px;
}
.forms label{
    width: 100%;
    text-align: center;
    font-weight: bold;
}
.forms input{
    padding: 10px;
    font-size: 1.1em;
    margin: 0 auto;
}
.forms input[type=submit]{
    background-color: rgb(87, 175, 171);
    border: none;
    box-shadow: 1px 1px 2px 1px rgb(122, 122, 122);
    margin-bottom: 10px;
    width: 130px;
    border: 1px solid transparent;
}
.forms input[type=submit]:hover{
    cursor: pointer;
    background-color: rgb(100, 247, 247);
    font-weight: bold;
    border: 1px solid black;
}
.container-calling:nth-child(2){
    font-weight: bold;
}
.container-calling{
    display: flex;
    align-items: center;
    justify-content: space-around;
    background-color: rgb(95, 182, 236);
    padding: 5px;
    margin-top: 5px;
}
.container-calling section{
    width: calc(100%/7);
    padding: 5px;
}
.span:nth-child(odd){
    background-color: rgb(236, 250, 250);
}
.span:nth-child(even){
    background-color: rgb(87, 175, 171);
}
</style>
<footer style="margin-top: 100px; height: 10px;width: 100%; text-align:center; margin-bottom: 50px;"><a href="https://ruanbarrodev.netlify.app/ruanx14_">Ruan Barroso</a></footer>
</body>
</html>