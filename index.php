<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABRIR CHAMADO</title>
</head>
<body>
<?php
require('connection.php');

if(isset($_POST['makeCall'])){
    $sql = "insert into calling(sector, server, type, status) values (
    '".$_POST['sector']."',
    '".$_POST['server']."',
    '".$_POST['type']."',
     'Em espera')";
    mysqli_query($conn, $sql);
}
?>
<main>
    <!-- Author Ruan Barroso -->
    <h1> Abrir chamado</h1>
        <form action="" method="post">
            <div class="forms">
                <label for="sector">Setor</label>
                <input type="text" id="sector" name="sector" maxlength="100" placeholder="Setor" required>
            </div><!-- 
            <div class="forms">
                <label for="sector">Andar</label>
                <input type="text" id="sector" name="sector" placeholder="Setor e Andar">
            </div> -->
            <div class="forms">
                <label for="sector">Servidor</label>
                <input type="text" id="sector" name="server" placeholder="Servidor" required>
            </div>
            <div class="forms">
                <label for="sector">Tipo de chamado </label>
                <select name="type">
                    <option value="">Selecione</option>
                    <option value="Access">Acesso</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                </select>
            </div>
            <div class="forms">
                <label for="time">Horario </label>
                <input type="text" id="time" name="time" disabled>
            </div>
            <div class="forms">
                <input type="submit" id="makeCall" name="makeCall" value="Abrir agora!">
            </div>
        </form>
        <div class="callings">
            <h2>Chamados: </h2>
            <div class="container-calling">
                <section>Horario:</section>
                <section>Setor:</section>
                <section>Servidor:</section>
                <section>Estado:</section>
            </div>
            <?php 
            $result = mysqli_query($conn,"select * from calling order by idCalling desc limit 5");
            while($dados = mysqli_fetch_array($result)){
                $dates = date('d/m/Y H:i:s',strtotime($dados['time']));
                 if($dados['status']=='Em espera'){
                    $bgColor = 'rgb(128, 128, 128)';
                }else if($dados['status']=='Em progresso'){
                    $bgColor = 'rgb(0, 123, 255)';
                }else{
                    $bgColor = 'rgb(40, 167, 69)';
                }
            ?>            
            <div class="container-calling span">
                <section><?=$dates?></section>
                <section><?=$dados['sector']?></section>
                <section><?=$dados['server']?></section>
                <section class="spacing-flex"><?=$dados['status']?><div class="colorBlock" style="background-color: <?=$bgColor?>"></div> </section>
            </div>
            <?php } ?>

        </div>
</main>
<style>
.spacing-flex{
    display: flex;
    gap: 20px;
    text-align: center;
    justify-content: center;
    width: 100%;
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
    width: 95%;
    margin: 0 auto;
    padding: 40px;
    min-height: 99vh;
}
.colorBlock{
    width: 30px;
    height: 30px;
    border-radius: 400px;
}
h1,h2{
    margin-top: 10px;
    text-align: center;
}
.forms{
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    padding: 5px;
}
.forms label{
    width: 100%;
    text-align: center;
    font-weight: bold;
}
.forms input, .forms select{
    padding: 10px;
    font-size: 1.1em;
    margin: 0 auto;
    width: 300px;
}
.forms input[type=submit]{
    background-color: rgb(87, 175, 171);
    border-radius: 5px;
    border: none;
    color: color;
    box-shadow: 1px 1px 2px 1px rgb(122, 122, 122);
    margin-bottom: 10px;
    width: 130px;
    border: 1px solid transparent;
}
.forms input[type=submit]:hover{
    cursor: pointer;
    background-color: rgb(173, 255, 255);
    text-decoration: underline;
}
.container-calling:nth-child(2){
    font-weight: bold;
}
.container-calling{
    display: flex;
    align-items: center;
    justify-content: space-around;
    background-color: rgb(95, 182, 236);
    height: 40px;
}
.container-calling section{
    width: 100%;
    text-align: center;
    padding: 5px;
}
.span:nth-child(odd){
    background-color: rgb(236, 250, 250);
}
.span:nth-child(even){
    background-color: rgb(167, 226, 223);
}
</style>
<script>
//document.querySelector("#time").value = "oldWay";
data = new Date();
hoje = data.toLocaleTimeString() + " - " + data.toLocaleDateString();
time.value = hoje;
/*
toLocaleString()	Converts a Date object to a string, using locale conventions
toString()	Converts a Date object to a string
*/
</script>
<!-- <footer style="margin-top: 100px; height: 10px;width: 100%; text-align:center; margin-bottom: 50px;"><a href="https://ruanbarrosodev.netlify.app">Ruan Barroso</a></footer> -->
</body>
</html>