<?php session_start(); 
if(!isset($_SESSION['user_key'])){
    $_SESSION['user_key'] = bin2hex(random_bytes(16));
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>ABRIR CHAMADO</title>
</head>
<body>
<?php
require('connection.php');

if(isset($_POST['makeCall'])){
    $sql = "INSERT INTO calling(sector, server, type, status,description, user_key) VALUES (
        '".$_POST['sector']."',
        '".$_POST['server']."',
        '".$_POST['type']."',
        'Em espera',
        '".$_POST['description']."',
        '".$_SESSION['user_key']."'
    )";

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
if(isset($_POST['enviarNota'])){
    $idCalling = $_POST['idCalling'];
    $nota = $_POST['nota'];
    mysqli_query($conn, "UPDATE calling SET nota = $nota WHERE idCalling=".$idCalling);
    header("Location: ./");
    exit;
}
?>

<?php
// Atualiza todos menos o mais recente para nota 5
$subQuery = "SELECT idCalling FROM calling WHERE user_key = '" . $_SESSION['user_key'] . "' AND status = 'Finalizado' AND (nota IS NULL OR nota = '') ORDER BY idCalling DESC";
$subResult = mysqli_query($conn, $subQuery);
if ($subResult && mysqli_num_rows($subResult) > 1) {
    $ids = [];
    while ($row = mysqli_fetch_assoc($subResult)) {
        $ids[] = $row['idCalling'];
    }
    array_shift($ids); // remove o primeiro (último finalizado)
    $idList = implode(',', $ids);
    mysqli_query($conn, "UPDATE calling SET nota = 5 WHERE idCalling IN ($idList)");
}

// Busca somente o último sem nota para exibir pesquisa
$query = "SELECT * FROM calling WHERE user_key = '" . $_SESSION['user_key'] . "' AND status = 'Finalizado' AND (nota IS NULL OR nota = '') ORDER BY idCalling DESC LIMIT 1";

$result = mysqli_query($conn, $query);


if ($result && mysqli_num_rows($result) > 0):
    $row = mysqli_fetch_assoc($result);
?>
<div class="modal" id="pesquisaModal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Pesquisa de Satisfação</h3>
        <form method="post" action="">
            <input type="hidden" name="idCalling" value="<?= $row['idCalling'] ?>">

           <div class="star-rating">
                <input type="radio" name="nota" value="1" id="pstar1"><label for="pstar1"><i class="fa-solid fa-star"></i></label>
                <input type="radio" name="nota" value="2" id="pstar2"><label for="pstar2"><i class="fa-solid fa-star"></i></label>
                <input type="radio" name="nota" value="3" id="pstar3"><label for="pstar3"><i class="fa-solid fa-star"></i></label>
                <input type="radio" name="nota" value="4" id="pstar4"><label for="pstar4"><i class="fa-solid fa-star"></i></label>
                <input type="radio" name="nota" value="5" id="pstar5"><label for="pstar5"><i class="fa-solid fa-star"></i></label>
            </div>


            <br>
            <label for="comentario">Comentário:</label><br>
            <textarea name="comentario" id="comentario" rows="4" cols="40"></textarea>
            <br><br>
            <button type="submit" name="enviarNota" >Enviar Avaliação</button>
        </form>
    </div>
</div>
<?php endif; ?>

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
    <?php
     $result = mysqli_query($conn,"SELECT * FROM calling WHERE user_key = '".$_SESSION['user_key']."' AND status='Em espera' ORDER BY idCalling DESC LIMIT 10");
    if(mysqli_num_rows($result) > 0){
        $chamado = mysqli_fetch_assoc($result);
    ?>
    <div class="CallingMixFather">
        <div class="callingWaiting">
            <h2>🚨 Chamado em Espera</h2>
            <p><strong>ID:</strong> <?php echo $chamado['idCalling']; ?></p>
            <p><strong>Setor:</strong> <?php echo htmlspecialchars($chamado['sector']); ?></p>
            <p><strong>Servidor:</strong> <?php echo htmlspecialchars($chamado['server']); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($chamado['type']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($chamado['status']); ?></p>
            <p><strong>Data:</strong> <?php echo htmlspecialchars($chamado['time']); ?></p>
        </div>
    </div>
    <?php 
    } else {
    ?>
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
                <label for="sector">Usuário(nome.sobrenome)</label>
                <input type="text" id="sector" name="server" placeholder="Usuário" required>
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
                <label for="Obss">Observação/Descrição </label>
                <textarea id="Obss" name="description"></textarea>
            </div>
            
            <div class="forms">
                <input type="submit" id="makeCall" name="makeCall" value="Abrir agora!">
            </div>
        </form>
    <?php } ?>
        <div class="callings">
            <h2>Chamados: </h2>
            <div class="container-calling">
                <section>Horario:</section>
                <section>Setor:</section>
                <section>Usuário:</section>
                <section>Estado:</section>
            </div>
            <?php
             $result = mysqli_query($conn,"select * from calling  WHERE user_key = '".$_SESSION['user_key']."' ORDER BY idCalling DESC LIMIT 10");
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

            <?php 
            /*
            $result = mysqli_query($conn,"select * from calling  WHERE user_key != '".$_SESSION['user_key']."' ORDER BY idCalling DESC LIMIT 3");
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
            <?php }*/ ?>

        </div>
</main>
<style>
.CallingMixFather{
    display: flex;
    justify-content:  center;
    padding: 20px;
    font-size: 1.4em;
}
.callingWaiting {
    background-color: #ffe9e9;
    border: 1px solid #ff4d4d;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.callingWaiting h2 {
    color: #c10000;
    margin-bottom: 10px;
}

.callingWaiting p {
    margin: 5px 0;
    font-size: 1em;
}

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
.modal {
    display: none; /* Oculta por padrão */
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fff;
    margin: 15% auto; /* Centraliza na vertical */
    padding: 20px;
    border: 1px solid #888;
    width: 300px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    text-align: center;
}

/* Botão */
.modal-content button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 20px;
    margin-top: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.modal-content button:hover {
    background-color: #45a049;
}
.star-rating {
    display: flex;
    flex-direction: row; /* mantém da esquerda para direita */
    justify-content: center; /* centraliza horizontalmente */
    align-items: center;     /* centraliza verticalmente se precisar */
    gap: 0.5rem;            /* espaço entre estrelas, opcional */
    margin-top: 1rem;       /* se quiser afastar um pouco do texto acima */
}
.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    font-size: 2rem;
    color: #ccc;
}

.star-rating .active {
    color: gold;
}
main{
    background-color:rgb(210, 241, 247);
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
.forms input, .forms select, .forms textarea{
    padding: 10px;
    font-size: 1.1em;
    margin: 0 auto;
    width: 350px;
}
.forms textarea{
    width: 500px;
    height: 80px;
}
.forms input[type=submit]{
    background-color: rgb(173, 255, 255);
    border-radius: 5px;
    border: none;
    color: color;
    box-shadow: 1px 1px 2px 1px rgb(122, 122, 122);
    margin-bottom: 10px;
    width: 130px;
    border: 1px solid transparent;
    transition: all 0.2s;/* 
    border: 1px solid rgb(173, 255, 255); */
}
.forms input[type=submit]:hover{
    background-color: rgb(128, 221, 216);
    cursor: pointer;
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
    width: 400px;
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
body{
    background-color: #f4f4f4;
}
main{
    /* background-color: #f4f4f4; */
    box-shadow: 1px 1px 3px 2px grey;
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
// Função para fechar modal
function fecharModal(modal) {
    modal.style.display = "none";
}
document.addEventListener('DOMContentLoaded', () => {
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.close-btn');

    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                fecharModal(modal);
            }
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            fecharModal(modal);
        });
    });

    // Exibe automaticamente modais se existirem
    const pesquisaModal = document.getElementById('pesquisaModal');
    if (pesquisaModal) {
        pesquisaModal.style.display = 'flex';
    }

    const successModal = document.getElementById('successModal');
    if (successModal) {
        successModal.style.display = 'flex';
    }
});

const stars = document.querySelectorAll('.star-rating label');
const radios = document.querySelectorAll('.star-rating input');

stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        // Limpa todas as estrelas
        stars.forEach(s => s.classList.remove('active'));
        
        // Marca até a clicada
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('active');
        }

        // Marca o input correspondente
        radios[index].checked = true;
    });
});



</script>
</html>