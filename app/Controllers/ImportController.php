<?php

require_once __DIR__ . '/../Models/Album.php';

class ImportController {
    
    public function syncFifa() {
        $csvPath = __DIR__ . '/../../testesAPIs/selecoes_ids.csv';
        if (!file_exists($csvPath)) {
            echo "Arquivo CSV não encontrado em: {$csvPath}";
            return;
        }

        $album = new Album();
        $file = fopen($csvPath, 'r');
        fgetcsv($file); // Pula cabeçalho

        // Carrega o layout inicial com a view de log
        $view = '../app/Views/sync_fifa.php';
        require __DIR__ . '/../Views/layout.php';

        // Script para inserir no log via JS (hack para streaming em PHP nativo com Bootstrap)
        echo "<script>const log = document.getElementById('sync-log'); log.innerHTML = '';</script>";

        set_time_limit(0);
        ob_implicit_flush(true);
        while (ob_get_level()) ob_end_clean();

        while (($row = fgetcsv($file)) !== FALSE) {
            $teamId = $row[0];
            $nomeSugestao = $row[1];

            $url = "https://api.fifa.com/api/v3/teams/{$teamId}/squad?idCompetition=17&idSeason=285023&language=pt";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $dados = json_decode($response, true);
                if (isset($dados['TeamName'][0]['Description'])) {
                    $nomePais = $dados['TeamName'][0]['Description'];
                    $sigla = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nomePais), 0, 3));
                    $selecaoId = $album->saveSelecao($nomePais, $sigla);

                    echo "<script>log.innerHTML += '<p class=\"text-info mb-1\"><strong>Sincronizando: {$nomePais} ({$sigla})</strong></p>';</script>";

                    if (isset($dados['Squad'])) {
                        $count = 1;
                        foreach ($dados['Squad'] as $player) {
                            $nomePlayer = addslashes($player['PlayerName'][0]['Description'] ?? 'Desconhecido');
                            $posicao = $player['Position'] ?? 'N/A';
                            $codigo = $sigla . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
                            
                            $album->saveJogador($selecaoId, $nomePlayer, $posicao, '', $codigo);
                            echo "<script>log.innerHTML += '<div class=\"ps-3 text-secondary\" style=\"font-size: 0.8em\"> ⚽ {$codigo} - {$nomePlayer}</div>';</script>";
                            $count++;
                        }
                    }
                }
            } else {
                echo "<script>log.innerHTML += '<p class=\"text-danger\">Erro ID {$teamId}. HTTP: {$httpCode}</p>';</script>";
            }
            usleep(500000); 
        }

        echo "<script>document.getElementById('sync-status').innerHTML = '<span class=\"text-success\">Sincronização concluída com sucesso!</span>';</script>";
        fclose($file);
    }
}
