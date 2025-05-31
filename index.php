<?php session_start(); ?>
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
    $_SESSION['success'] = [
        'sector' => $_POST['sector'],
        'server' => $_POST['server'],
        'status' => 'Em espera',
        'type' => $_POST['type'],
        'time' => date('d/m/Y H:i:s')
    ];
    header("Location: ./");
    exit;
}
?>
<?php if(!empty($_SESSION['success'])): ?>
<div class="modal" id="successModal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Chamado criado com sucesso!<br>
        Para o SETOR: <b><?= $_SESSION['success']['sector'] ?></b><br>
        Para o SERVIDOR: <b><?= $_SESSION['success']['server'] ?></b><br>
        O estado está: <b><?= $_SESSION['success']['status'] ?> <br> Tipo: <?= $_SESSION['success']['type'] ?></b><br>
        No horário: <b><?= $_SESSION['success']['time'] ?></b></p>
    </div>
</div>
<?php unset($_SESSION['success']); endif; ?>

<main>
    <!-- Author Ruan Barroso -->
    <div class="descriptionStatus">
        <div><div class="colorBlock" style="background-color: rgb(128, 128, 128)"></div><b><h3> Em espera </h3></b> - Não iniciado, aguardando na fila de chamados.</div>
        <div><div class="colorBlock" style="background-color: rgb(0, 123, 255)"></div><b><h3>Em processo</h3></b> - Iniciado, aguardando chamado ser resolvido.</div>
        <div><div class="colorBlock" style="background-color: rgb(40, 167, 69)"></div><b><h3>Finalizado</h3></b> - Chamado finalizado.</div>
    </div>
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
                <select name="type" required>
                    <option value="">Selecione</option>
                    <option value="Acesso">Acesso</option>
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
            $result = mysqli_query($conn,"select * from calling order by idCalling desc limit 6");
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
.descriptionStatus{
    background-color: rgb(195, 248, 245);
    padding: 20px;
    max-width: 400px;
    position: absolute;
    display: flex;
    flex-direction: column;
}
.spacing-flex{
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-direction: column;
    align-items:center;
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
.modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal-content {
    position: relative;
    background: #fff;
    padding: 20px 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    font-weight: bold;
    font-size: 16px;
    animation: fadeIn 0.3s ease;
    text-align: center;
    width: 500px;
    height: 200px;
}
.close-btn {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #888;
}
.close-btn:hover {
    color: #000;
}

@keyframes fadeIn {
    from {opacity: 0; transform: scale(0.9);}
    to {opacity: 1; transform: scale(1);}
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
<script>
const modal = document.getElementById('successModal');
if(modal){
    const closeBtn = modal.querySelector('.close-btn');
    modal.addEventListener('click', (e) => {
        if(e.target === modal) modal.style.display = 'none';
    });
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    setTimeout(() => {
        modal.style.display = 'none';
    }, 10000);
}
</script>

</script>
</html>