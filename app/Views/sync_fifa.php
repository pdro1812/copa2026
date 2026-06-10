<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Sincronização FIFA API</h4>
                <a href="index.php?url=home" class="btn btn-sm btn-light">Voltar</a>
            </div>
            <div class="card-body bg-dark text-light" style="max-height: 500px; overflow-y: auto; font-family: monospace;" id="sync-log">
                <!-- O log será inserido aqui em tempo real -->
                <p class="text-warning">Iniciando processo... Por favor, não feche esta página.</p>
            </div>
            <div class="card-footer text-muted" id="sync-status">
                Aguardando finalização...
            </div>
        </div>
    </div>
</div>

<script>
    // Função para rolar o log automaticamente para baixo
    const logDiv = document.getElementById('sync-log');
    const observer = new MutationObserver(() => {
        logDiv.scrollTop = logDiv.scrollHeight;
    });
    observer.observe(logDiv, { childList: true });
</script>
