
document.addEventListener('DOMContentLoaded', () => {
    const selecionado = document.querySelector('input[name="filterTime"]:checked').value;
    enviarFiltro(selecionado);
});


function enviarFiltro(tipo) {
    const formData = new FormData();
    formData.append('filterTime', tipo);
    formData.append('dateControl', document.querySelector("#dateControl").value);
    fetch('metricas.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        document.querySelector("#countTotal .percent").innerHTML = data.countByDate;
        document.querySelector("#countMetrica5 .percent").innerHTML = data.metrica5;
        document.querySelector("#countMetrica6 .percent").innerHTML = data.metrica6;
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

document.querySelectorAll('input[name="filterTime"]').forEach(radio => {
    radio.addEventListener('change', function() {
        enviarFiltro(this.value);
        //console.log(this.value);
    });
});


document.querySelector("#dateControl").addEventListener('change', () => {
    enviarFiltro(document.querySelector('input[name="filterTime"]:checked').value);
});


