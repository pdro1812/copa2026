<style>
    .figurinha-container {
        width: 140px;
        height: 200px;
        position: relative;
        margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .figurinha-container:hover { transform: scale(1.05); }
    
    .sticker-card {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        border: 2px solid #ddd;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 8px;
        position: relative;
        overflow: hidden;
    }
    
    .repeat-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8em;
        font-weight: bold;
        z-index: 10;
        border: 2px solid white;
    }
    
    .sticker-photo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 5px;
    }

    .flag-mini {
        width: 20px;
        height: 20px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 5px;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 shadow-sm rounded">
            <h2 class="mb-0"><i class="bi bi-stack text-danger"></i> Minhas Figurinhas Repetidas</h2>
            <a href="index.php?url=album" class="btn btn-primary">Voltar para o Álbum</a>
        </div>
    </div>
</div>

<?php if (empty($jogadores)): ?>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="display-1 text-muted mb-4"><i class="bi bi-emoji-smile"></i></div>
            <h3>Você ainda não tem figurinhas repetidas!</h3>
            <p class="text-muted">Continue abrindo pacotinhos para completar sua coleção e ver as trocas disponíveis.</p>
            <a href="index.php?url=abrir_pacote" class="btn btn-success btn-lg mt-3">Abrir Pacotinho</a>
        </div>
    </div>
<?php else: ?>
    <div class="row justify-content-center">
        <?php foreach ($jogadores as $j): ?>
            <div class="col-auto">
                <div class="figurinha-container">
                    <!-- Mostra a quantidade extra (n-1) como repetidas, ou o total -->
                    <div class="repeat-badge"><?= $j['quantidade'] - 1 ?></div>
                    
                    <div class="sticker-card">
                        <div class="d-flex align-items-center mb-1">
                            <?php if ($j['bandeira_url']): ?>
                                <img src="<?= $j['bandeira_url'] ?>" class="flag-mini">
                            <?php endif; ?>
                            <span class="badge bg-light text-dark border" style="font-size: 0.6em"><?= $j['codigo_figurinha'] ?></span>
                        </div>
                        
                        <?php 
                            $fotoUrl = ($j['foto_url'] && $j['foto_url'] !== '') ? $j['foto_url'] : 'https://cdn-icons-png.flaticon.com/512/166/166344.png';
                        ?>
                        <img src="<?= $fotoUrl ?>" class="sticker-photo">
                        
                        <div class="fw-bold text-center" style="font-size: 0.75em; line-height: 1.1; height: 30px; overflow: hidden;">
                            <?= $j['nome'] ?>
                        </div>
                        <div class="text-muted" style="font-size: 0.65em;"><?= $j['posicao'] ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
