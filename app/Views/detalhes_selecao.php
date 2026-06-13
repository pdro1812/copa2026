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
    
    .missing {
        filter: grayscale(1);
        opacity: 0.4;
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
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white shadow-sm rounded">
    <a href="index.php?url=album" class="btn btn-outline-secondary">&larr; Voltar</a>
    <div class="d-flex align-items-center">
        <?php if ($selecao['bandeira_url']): ?>
            <img src="<?= $selecao['bandeira_url'] ?>" class="flag-mini me-3 shadow-sm border">
        <?php endif; ?>
        <h2 class="mb-0"><?= $selecao['nome'] ?></h2>
    </div>
    <span class="badge bg-dark fs-5"><?= $selecao['sigla'] ?></span>
</div>

<div class="row justify-content-center">
    <?php foreach ($jogadores as $j): ?>
        <div class="col-auto">
            <div class="figurinha-container">
                <?php if (isset($j['quantidade']) && $j['quantidade'] > 1): ?>
                    <div class="repeat-badge"><?= $j['quantidade'] ?></div>
                <?php endif; ?>
                
                <div class="sticker-card <?= empty($j['quantidade']) ? 'missing' : '' ?>">
                    <span class="badge bg-light text-dark border mb-1" style="font-size: 0.7em"><?= $j['codigo_figurinha'] ?></span>
                    
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
