<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white p-3">
                <h3 class="mb-0"><i class="bi bi-trophy-fill text-warning"></i> Ranking de Colecionadores</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Veja quem são os maiores colecionadores da Copa 2026!</p>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="100">Posição</th>
                                <th>Colecionador</th>
                                <th class="text-center">Figurinhas Únicas</th>
                                <th class="text-center">Total Acumulado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ranking)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum colecionador encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ranking as $index => $row): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index === 0): ?>
                                                <span class="badge bg-warning text-dark fs-6">1º 🏆</span>
                                            <?php elseif ($index === 1): ?>
                                                <span class="badge bg-secondary fs-6">2º 🥈</span>
                                            <?php elseif ($index === 2): ?>
                                                <span class="badge bg-danger fs-6" style="background-color: #cd7f32 !important;">3º 🥉</span>
                                            <?php else: ?>
                                                <span class="ms-2 fw-bold text-muted"><?= $index + 1 ?>º</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold"><?= $row['nome'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success rounded-pill"><?= $row['total_unicas'] ?></span>
                                        </td>
                                        <td class="text-center text-muted">
                                            <?= $row['total_figurinhas'] ?: 0 ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
