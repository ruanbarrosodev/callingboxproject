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
        //console.log(data);
        document.querySelector("#countTotal .percent").innerHTML = data.count;
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

const selecionado = document.querySelector('input[name="filterTime"]:checked').value;
enviarFiltro(selecionado);

document.querySelector("#dateControl").addEventListener('change', () => {
    enviarFiltro(document.querySelector('input[name="filterTime"]:checked').value);
});


