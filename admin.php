<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - PAINEL</title>
</head>
<body>
<?php
require('connection.php');

if(isset($_POST['sendPassword'])){
    if($_POST['password']=='freedom' || $_POST['password'] == 'FREEDOM'){
        $_SESSION['userti'] = 1;
    }else{
        $erro = '<p class="error">Você não tem permissão para acessar</p>';
    }
}
if(isset($_POST['logout'])){
    unset($_SESSION['userti']);
    session_destroy();
}
?>

<?php if(!empty($_SESSION['userti'])): ?>
<main>
    <form class="logoutForm" action="" method="post">
        <a class="btnMenuUp" href="ti">Chamados</a>
        <input class="btnMenuUp" type="submit" name="logout" value="SAIR">
    </form>
    <h1 style="text-align:center; margin: 20px 0;">PAINEL</h1>
    <div class="container" style="display: grid; grid-template-rows: auto auto auto; gap: 20px; padding: 20px;">
    <!-- Linha 1: Selects -->
        <div class="linha1">
            <form action="generateExcel.php" method="post">
            <div class="select-group">
                <label for="select1">Tipo de chamado</label>
                <select id="select1" name="tipoChamado">
                    <option value="">Selecione</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Acesso">Acesso</option>
                </select>
            </div>
            <div class="select-group">
                <label for="select2">Estado do chamado</label>
                <select id="select2" name="status">
                    <option value="">Selecione</option>
                    <option value="Em espera">Em espera</option>
                    <option value="Em processo" >Em andamento</option>
                    <option>Finalizado</option>
                </select>
            </div>
            <div class="select-group">
                <label for="select4">Período</label>
                <div class="radios">
                    <label><input type="radio" name="filterDownload" value="all" checked> TODOS</label>
                    <label><input type="radio" name="filterDownload" value="day"> DIA</label>
                    <label><input type="radio" name="filterDownload" value="week"> SEMANA</label>
                    <label><input type="radio" name="filterDownload" value="month"> MÊS</label>
                </div>  
                
                <label><input style="width: 100%; height: 40px;" id="dateSearch" type="date" name="dateSearch"></label>
            </div>
            <button type="submit" id="btnDownload">Download</button>
        </form>
        </div>
        <?php 


        ?>
        <div class="linha2">
            <div class="radio-group">
                <label><input id="dateControl" type="date" name="dateControl"></label>
                <label><input type="radio" name="filterTime" value="dia" checked> DIA</label>
                <label><input type="radio" name="filterTime" value="semana"> SEMANA</label>
                <label><input type="radio" name="filterTime" value="mes"> MÊS</label>
            </div>
            <div class="info-card">
                <div>Soma do tempo total de atendimentos / Nº de chamados concluídos</div>
                <div class="percent">75%</div>
                <button>Ver Mais</button>
            </div>
            <div class="info-card">
                <div>Tempo total para resolver os chamados / Nº total de chamados resolvidos</div>
                <div class="percent">60%</div>
                <button>Ver Mais</button>
            </div>
            <div class="info-card">
                <div>(% de respostas positivas em pesquisas de satisfação)</div>
                <div class="percent">45%</div>
                <button>Ver Mais</button>
            </div>
            <!-- <div class="info-card">
                <div>Nº de chamados resolvidos no primeiro atendimento / total de chamados</div>
                <div class="percent">30%</div>
                <button>Ver Mais</button>
            </div> -->
        </div>

        <div class="linha3">
            <div class="info-card" id="countTotal">
                <div>Quantidade de chamados registrados (diário, semanal, mensal) (ok)</div>
                <div class="percent"></div>
                <button>Ver Mais</button>
            </div>
            <!-- <div class="info-card">
                <div>Nº de chamados reabertos / Nº total de chamados</div>
                <div class="percent">15%</div>
                <button>Ver Mais</button>
            </div> -->
            <div class="info-card">
                <div>Nº de chamados atendidos dentro do prazo / Nº total de chamados</div>
                <div class="percent">50%</div>
                <button>Ver Mais</button>
            </div> 
            <div class="info-card" id="countMetrica6">
                <div>Chamados abertos há mais de 2 dias</div>
                <div class="percent"></div>
                <button>Ver Mais</button>
            </div>
        </div>
    </div>
</main>

<?php else: ?>
<section class="login">
    <div class="putPassword">
        <h2>Senha:</h2>
        <?php if(!empty($erro)) echo $erro; ?>
        <form action="" method="post">
            <input type="password" name="password" placeholder="Senha">
            <input type="submit" name="sendPassword" value="Entrar">  
        </form>
    </div>    
</section>
<?php endif; ?>
<style>
.info-card button{
    display: none;
}
* {
    margin: 0;
    box-sizing: border-box;
    padding: 0;
    font-family: sans-serif;
}
body {
    background-color: #f4f4f4;
    padding: 20px;
}
.container {
    display: grid;
    grid-template-rows: auto auto auto;
    gap: 20px;
    width: 100%;
}
.linha1 form {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
}
.select-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.linha1 label {
    font-weight: bold;
}
.linha1 select, .linha1 button {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.linha2, .linha3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    position: relative;
}
.radio-group {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
    & input[type=date]{
        width: 200px;
        padding: 5px;
    }
}
.info-card {
    background-color: #e0f0ff;
    padding: 15px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    /* font-size: 0.8em; */
    text-align: center;
    margin-top: 40px;
    min-height: 200px;
    
}
.info-card div:first-child {
    min-height: 40px;
}
.info-card .percent {
    font-size: 24px;
    font-weight: bold;
    color: #007BFF;
}
.info-card button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #007BFF;
    color: #fff;
    cursor: pointer;
}
.info-card button:hover {
    background-color: #0056b3;
}
#btnDownload {
    padding: 5px;
    border-radius: 5px;
    color: black;
    cursor: pointer;
    border: 1px solid grey;
    padding: none;
}
#btnDownload:hover {
    background-color:rgb(189, 219, 253);
}
.login {
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
    width: 400px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}
.putPassword h2 {
    font-size: 24px;
    color: #333;
}
.putPassword input[type="password"],
.putPassword input[type="submit"] {
    padding: 10px;
    width: 100%;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.putPassword input[type="submit"] {
    background-color: #007BFF;
    color: white;
    cursor: pointer;
}
.putPassword input[type="submit"]:hover {
    background-color: #0056b3;
}
.error {
    color: #b30000;
    font-weight: bold;
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
</style>

</body>
<script src="admin.js"></script>
</html>
