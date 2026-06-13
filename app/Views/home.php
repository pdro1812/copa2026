<div class="row justify-content-center">
    <!-- Hero Section -->
    <div class="col-12 text-center py-5 bg-dark text-white rounded shadow-lg mb-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=1200'); background-size: cover; background-position: center;">
        <h1 class="display-3 fw-bold mb-3">Copa do Mundo 2026</h1>
        <p class="lead mb-4">Complete sua coleção oficial de figurinhas e gerencie os jogos do maior espetáculo da Terra!</p>
        
        <?php if (!isset($_SESSION['usuario_id'])): ?>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="index.php?url=login" class="btn btn-primary btn-lg px-5 gap-3 rounded-pill"><i class="bi bi-box-arrow-in-right"></i> Entrar agora</a>
                <a href="index.php?url=registro" class="btn btn-outline-light btn-lg px-5 rounded-pill">Criar Conta</a>
            </div>
        <?php else: ?>
            <p class="fs-4">Bem-vindo de volta, <span class="text-warning fw-bold"><?= $_SESSION['usuario_nome'] ?></span>!</p>
        <?php endif; ?>
    </div>

    <!-- Feature Cards -->
    <div class="col-12">
        <div class="row g-4 text-center justify-content-center">
            
            <!-- Card Pacotinho -->
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body p-4">
                        <div class="display-4 text-success mb-3"><i class="bi bi-box2-heart"></i></div>
                        <h3 class="card-title h4">Abrir Pacote</h3>
                        <p class="card-text text-muted small">Tente a sorte e ganhe 5 novas figurinhas.</p>
                        <a href="index.php?url=abrir_pacote" class="btn btn-success w-100 rounded-pill mt-3">Abrir Agora</a>
                    </div>
                </div>
            </div>

            <!-- Card Meu Álbum -->
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body p-4">
                        <div class="display-4 text-primary mb-3"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <h3 class="card-title h4">Meu Álbum</h3>
                        <p class="card-text text-muted small">Visualize sua coleção e seu progresso geral.</p>
                        <a href="index.php?url=album" class="btn btn-primary w-100 rounded-pill mt-3">Ver Álbum</a>
                    </div>
                </div>
            </div>

            <!-- Card Repetidas -->
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body p-4">
                        <div class="display-4 text-danger mb-3"><i class="bi bi-stack"></i></div>
                        <h3 class="card-title h4">Repetidas</h3>
                        <p class="card-text text-muted small">Veja as figurinhas que você tem para troca.</p>
                        <a href="index.php?url=repetidas" class="btn btn-danger w-100 rounded-pill mt-3">Ver Repetidas</a>
                    </div>
                </div>
            </div>

            <!-- Card Ranking -->
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body p-4">
                        <div class="display-4 text-warning mb-3"><i class="bi bi-trophy-fill"></i></div>
                        <h3 class="card-title h4">Ranking</h3>
                        <p class="card-text text-muted small">Veja os maiores colecionadores da plataforma.</p>
                        <a href="index.php?url=ranking" class="btn btn-warning w-100 rounded-pill mt-3 text-dark fw-bold">Ver Ranking</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Admin Section (Quick Actions) -->
    <div class="col-12 mt-5">
        <div class="card border-0 bg-light shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Ferramentas de Sistema</h5>
                    <small class="text-muted">Ações administrativas e de manutenção.</small>
                </div>
                <a href="index.php?url=sync_fifa" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-repeat"></i> Sincronizar Dados FIFA</a>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .transition {
        transition: all 0.3s ease-in-out;
    }
</style>
