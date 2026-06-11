<style>
    .pack-container { perspective: 1000px; min-height: 400px; }
    .card-figurinha {
        width: 150px; height: 220px; background: #fff; border: 2px solid #ddd;
        border-radius: 10px; margin: 10px; display: inline-block;
        transition: transform 0.6s; transform-style: preserve-3d;
        position: relative; cursor: pointer;
    }
    .card-front, .card-back {
        position: absolute; width: 100%; height: 100%;
        backface-visibility: hidden; display: flex; flex-direction: column;
        align-items: center; justify-content: center; padding: 10px;
        text-align: center; border-radius: 8px;
    }
    .card-back { background: linear-gradient(45deg, #1a237e, #0d47a1); color: white; }
    .card-front { background: #fff; transform: rotateY(180deg); border: 4px solid #fbc02d; }
    .card-figurinha.revealed .card-back { transform: rotateY(180deg); }
    .card-figurinha.revealed .card-front { transform: rotateY(0deg); }
    #pack-visual { display: none; }
</style>

<div class="text-center py-5">
    <div id="setup-view">
        <h2 class="mb-4">📦 Pacotinho de Figurinhas</h2>
        <p class="lead">Você está prestes a abrir um pacote com 5 figurinhas aleatórias!</p>
        <button id="btn-open-pack" class="btn btn-warning btn-lg px-5 shadow">Abrir Meu Pacote!</button>
    </div>

    <div id="pack-visual" class="mt-4">
        <h3 class="mb-4">Suas novas figurinhas:</h3>
        <div class="d-flex flex-wrap justify-content-center pack-container" id="cards-container"></div>
        <div class="mt-5">
            <a href="index.php?url=abrir_pacote" class="btn btn-outline-primary">Abrir Outro Pacote</a>
            <a href="index.php?url=album" class="btn btn-primary ms-2">Ir para o Álbum</a>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-open-pack').addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Abrindo...';

    fetch('index.php?url=processar_pacote')
        .then(response => response.text())
        .then(text => {
            try {
                const jogadores = JSON.parse(text);
                
                if (jogadores.error) {
                    alert('Erro: ' + jogadores.error);
                    location.reload();
                    return;
                }

                if (jogadores.length === 0) {
                    alert('Nenhum jogador encontrado! Sincronize os dados primeiro.');
                    location.reload();
                    return;
                }

                document.getElementById('setup-view').style.display = 'none';
                document.getElementById('pack-visual').style.display = 'block';
                
                const container = document.getElementById('cards-container');
                container.innerHTML = '';
                
                jogadores.forEach((j, index) => {
                    const card = document.createElement('div');
                    card.className = 'card-figurinha';
                    const fotoUrl = (j.foto_url && j.foto_url !== '') ? j.foto_url : 'https://cdn-icons-png.flaticon.com/512/166/166344.png';

                    card.innerHTML = `
                        <div class="card-back">
                            <img src="https://upload.wikimedia.org/wikipedia/pt/1/10/Logotipo_da_Copa_do_Mundo_FIFA_de_2026.png" width="80" style="opacity: 0.5">
                        </div>
                        <div class="card-front">
                            <div class="badge bg-danger mb-2">${j.sigla}</div>
                            <img src="${fotoUrl}" class="img-fluid rounded mb-2" style="max-height: 80px; object-fit: contain;">
                            <div class="fw-bold" style="font-size: 0.85em; line-height: 1.2">${j.nome}</div>
                            <div class="text-muted" style="font-size: 0.75em">${j.posicao}</div>
                            <div class="mt-auto fw-bold text-primary" style="font-size: 0.9em">${j.codigo_figurinha}</div>
                        </div>
                    `;
                    container.appendChild(card);
                    setTimeout(() => card.classList.add('revealed'), index * 400);
                });
            } catch (e) {
                console.error('Erro ao processar JSON:', text);
                alert('Erro na resposta do servidor. Verifique o console.');
                this.disabled = false;
                this.innerHTML = 'Abrir Meu Pacote!';
            }
        })
        .catch(err => {
            alert('Erro na requisição. Tente novamente.');
            this.disabled = false;
            this.innerHTML = 'Abrir Meu Pacote!';
        });
});
</script>
