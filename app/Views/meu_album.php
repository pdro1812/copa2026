<style>
    .flag-progress-container {
        position: relative;
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 15px;
    }
    
    /* Círculo de progresso dinâmico usando conic-gradient */
    .progress-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        z-index: 1;
    }
    
    .flag-img {
        position: relative;
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        z-index: 2;
        background: white;
        border: 2px solid white;
    }

    .percent-label {
        position: absolute;
        bottom: -5px;
        background: #198754;
        color: white;
        font-size: 0.7em;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
        z-index: 3;
        border: 2px solid white;
    }
</style>

<div class="row mb-4">
    <div class="col-12 text-center">
        <h2 class="display-5">Meu Álbum da Copa 2026</h2>
        <div class="progress mt-3 shadow-sm" style="height: 35px; border-radius: 20px;">
            <?php 
                $pctGeral = ($progresso['total_album'] > 0) ? ($progresso['total_usuario'] / $progresso['total_album']) * 100 : 0;
            ?>
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: <?= $pctGeral ?>%;">
                <span class="fw-bold"><?= round($pctGeral, 1) ?>% Completado (<?= $progresso['total_usuario'] ?> / <?= $progresso['total_album'] ?>)</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($selecoes as $s): 
        $pctSelecao = $progressoSelecoes[$s['id']] ?? 0;
        // Cor do anel: cinza se 0, verde se tiver algo
        $ringColor = $pctSelecao > 0 ? '#198754' : '#e9ecef';
    ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 border-0 hover-shadow transition">
                <div class="card-body text-center d-flex flex-column align-items-center">
                    
                    <div class="flag-progress-container">
                        <!-- O anel de progresso -->
                        <div class="progress-ring" style="background: conic-gradient(<?= $ringColor ?> <?= $pctSelecao ?>%, #e9ecef 0deg);"></div>
                        
                        <?php if ($s['bandeira_url']): ?>
                            <img src="<?= $s['bandeira_url'] ?>" class="flag-img shadow-sm">
                        <?php else: ?>
                            <div class="flag-img d-flex align-items-center justify-content-center h1 mb-0">🌍</div>
                        <?php endif; ?>
                        
                        <span class="percent-label"><?= round($pctSelecao) ?>%</span>
                    </div>

                    <h5 class="card-title mb-0"><?= $s['nome'] ?></h5>
                    <p class="text-muted small"><?= $s['sigla'] ?></p>
                    
                    <a href="index.php?url=album_selecao&id=<?= $s['id'] ?>" class="btn btn-primary btn-sm mt-auto w-100 rounded-pill">Ver Coleção</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
