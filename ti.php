<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHAMADOS - PAINEL</title>
</head>
<body>
<?php
require('connection.php');

if(isset($_POST['editStatus'])){
    if($_POST['status']=='Em progresso'){
        $fieldChanged = "updateTime";
    }else if($_POST['status']=='Finalizado'){
        $fieldChanged = "doneTime";
    }
    date_default_timezone_set('America/Manaus');
    $dateNow = date("Y-m-d H:i:s");
    $sql = "
        update calling
        set status='".$_POST['status']."'
        ,$fieldChanged='$dateNow'
        where idCalling=".$_POST['idCalling'];
        error_log($sql);
        
        mysqli_query($conn, $sql);
        
        header("Location: ./ti");
        exit;
}
if(isset($_POST['sendPassword'])){
    if($_POST['password']=='freedom' || $_POST['password'] == 'FREEDOM'){
        $_SESSION['userti'] = 1;
    }else{
        $erro = '<p class="error">Você não tem permissão para acessar';
    }
}
if(isset($_POST['logout'])){
    unset($_SESSION['userti']);
    session_destroy();
}
?>
<?php 
    if(!empty($_SESSION['userti'])){
?>
<main>
    <form class="logoutForm" action="" method="post">
        <a class="btnMenuUp" href="admin">Administração</a>
        <input class="btnMenuUp" type="submit" name="logout" value="SAIR">
    </form>
    <!-- Author Ruan Barroso -->
    <h1>Administração</h1>
      <div class="filters" style="margin-bottom: 20px;">
            <label for="filterStatus">Estado:</label>
            <select id="filterStatus">
                <option value="all">Todos</option>
                <option value="Em espera">Em espera</option>
                <option value="Em progresso">Em progresso</option>
                <option value="Finalizado">Finalizado</option>
            </select>
            <label for="filterType">Tipo:</label>
            <select id="filterType">
                <option value="all">Todos</option>
                <option value="Software">Software</option>
                <option value="Hardware">Hardware</option>
                <option value="Acesso">Acesso</option>
            </select>
        </div>
        <div class="callings">
            <h2>Chamados </h2>
            <div class="container-calling">
                <section>Descrição:</section>
                <section>Horario:</section>
                <section>Setor: </section>
                <section>Usuário: </section>
                <section>Tipo: </section>
                <section>Estado:</section>
                <section>Alterar:</section>
            </div>
            <?php 
            $result = mysqli_query($conn,"select * from calling order by idCalling desc limit 20");
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
                <section><?=$dados['description']?></section>
                <section><?=$dates?></section>
                <section><?=$dados['sector']?></section>
                <section><?=$dados['server']?></section>
                <section><?=$dados['type']?></section>
                <section><?=$dados['status']?><div class="colorBlock" style="background-color: <?=$bgColor?>"></div></section>
                <section>
                    <?php if($dados['status']!='Finalizado') { ?>
                    <form action="" class="formEdit" method="POST">
                        <input type="hidden" name="idCalling" value="<?=$dados['idCalling']?>">
                        <select class="selectStatus" name="status">
                            <?php 
                            if($dados['status']!='Em progresso'){
                            ?>
                            <option value="Em progresso"> Em progresso </option>
                            <?php } ?>
                            <option value="Finalizado"> Finalizado </option>
                        </select>
                        <input id="btnSalvarStatus" type="submit" name="editStatus" value="Salvar">
                    </form>
                    <?php } ?>
                </section>
            </div>
            <?php } ?>
        </div>
</main>
<script>
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const rows = document.querySelectorAll('.container-calling.span');

    function filterCallings() {
        const status = filterStatus.value;
        const type = filterType.value;

        rows.forEach(row => {
            const estado = row.children[5].textContent.trim();
            const tipo = row.children[4].textContent.trim();

            const matchStatus = (status === 'all' || estado === status);
            const matchType = (type === 'all' || tipo === type);

            if (matchStatus && matchType) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    }
    filterStatus.addEventListener('change', filterCallings);
    filterType.addEventListener('change', filterCallings);

</script>
<?php }else{ ?>
<section class="login">
    <div class="putPassword">
        <?php 
        if(!empty($_SESSION['userti'])){
            var_dump($_SESSION['userti']);
        }
        ?>
            <h2>Senha: </h2>
             <?php 
                if(!empty($erro)){
                    echo $erro;
                }
            ?>
            <form action="" method="post">
                <input type="password" name="password" placeholder="Senha">
                <input type="submit" name="sendPassword" value="Entrar">  
            </form>
    </div>    
</section>
<?php } ?>
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

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
.colorBlock{
    width: 30px;
    height: 30px;
    border-radius: 400px;
}
.putPassword h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
}

.putPassword form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.putPassword input[type="password"] {
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.putPassword input[type="password"]:focus {
    border-color: #007BFF;
    outline: none;
}

.putPassword input[type="submit"] {
    padding: 12px 15px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}
#btnSalvarStatus{
    padding: 10px 14px;
    background-color:rgb(142, 195, 255);
    color: black;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
    border: 1px solid transparent;
    margin-top: 1px;
}
.selectStatus {
    border: 1px solid #ccc;
    padding: 10px 14px;
    border-radius: 6px;
    background-color: #fff;
    box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
}
#btnSalvarStatus:hover{
    background-color: rgb(119, 178, 245);
}
.putPassword input[type="submit"]:hover {
    background-color: #0056b3;
}
.formEdit{
    display: flex;
    flex-direction: column;
}
.formEdit select, .formEdit input{
    padding: 5px;
}
.error{
    color: white;
    background-color: rgb(252, 185, 185);
    border: 1px solid rgb(209, 79, 79);
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 5px;
    color: rgb(209, 79, 79);
    font-weight: bold;
}
.filters {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.filters label {
    font-weight: bold;
    color: #333;
}

.filters select{
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    background-color: #fff;
    box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
    transition: border-color 0.3s, box-shadow 0.3s;
}

.filters select:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
    outline: none;
}

* , body{
    margin: 0;
    box-sizing: border-box;
    border-spacing: 0;
    padding: 0;
    font-family: sans-serif;
}
body{
     background-color: #f4f4f4;
}
main{
    width: 95%;
    margin: 0 auto;
    padding: 20px;
    min-height: 99vh;
    box-shadow: 1px 2px 3px 1px grey;
}
.logoutForm{
    display: flex;
    width: 100%;
    justify-content: space-between;
    font-weight: bold;
    & .btnMenuUp {
        color: black;
        text-decoration: none;
        padding: 20px;
        border-radius: 10px;
        font-weight: bold;
        border: none;
        background-color: #ff4d4d;
        box-shadow: 1px 1px 2px 1px rgb(122, 122, 122);
        border: 1px solid transparent;
    }
    & .btnMenuUp:hover{
        cursor: pointer;
        background-color: #e60000; 
    }
    & .btnMenuUp:nth-child(odd) {
        background-color:rgb(142, 195, 255);
    }
    & .btnMenuUp:nth-child(odd):hover{
        background-color:rgb(155, 202, 255);
    }
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
    /* display: flex; */
}
.span:nth-child(odd){
    background-color: rgb(208, 241, 241);
}
.span:nth-child(even){
    background-color: rgb(182, 235, 232);
}
</style>
<!-- <footer style="margin-top: 100px; height: 10px;width: 100%; text-align:center; margin-bottom: 50px;"><a href="https://ruanbarrodev.netlify.app/ruanx14_">Ruan Barroso</a></footer> -->
</body>
</html>