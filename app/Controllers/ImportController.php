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

        $view = '../app/Views/sync_fifa.php';
        require __DIR__ . '/../Views/layout.php';

        while (ob_get_level() > 0) ob_end_flush();
        flush();

        echo "<script>var logContainer = document.getElementById('sync-log');</script>";

        set_time_limit(0);
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

                    echo "<script>if(logContainer) logContainer.innerHTML += '<p class=\"text-info mb-1\"><strong>🌍 Sincronizando: " . addslashes($nomePais) . " ({$sigla})</strong></p>';</script>";
                    flush();

                    // Conforme novo JSON: A chave correta é 'Players' e não 'Squad'
                    $players = $dados['Players'] ?? $dados['Squad'] ?? null;

                    if ($players && is_array($players)) {
                        $count = 1;
                        foreach ($players as $player) {
                            $nomePlayer = addslashes($player['PlayerName'][0]['Description'] ?? 'Desconhecido');
                            
                            // Posição localizada conforme exemplo
                            $posicao = $player['PositionLocalized'][0]['Description'] ?? null;
                            if (!$posicao && isset($player['Position'])) {
                                $mapPos = [0 => 'Goleiro', 1 => 'Defensor', 2 => 'Meio-campista', 3 => 'Atacante'];
                                $posicao = $mapPos[$player['Position']] ?? 'Jogador';
                            }

                            // Foto URL se disponível
                            $fotoUrl = $player['PlayerPicture']['PictureUrl'] ?? '';

                            $codigo = $sigla . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
                            
                            $album->saveJogador($selecaoId, $nomePlayer, $posicao, $fotoUrl, $codigo);
                            echo "<script>if(logContainer) logContainer.innerHTML += '<div class=\"ps-3 text-secondary\" style=\"font-size: 0.8em\"> ⚽ {$codigo} - {$nomePlayer} ({$posicao})</div>';</script>";
                            flush();
                            $count++;
                        }
                    } else {
                        echo "<script>if(logContainer) logContainer.innerHTML += '<p class=\"text-warning ms-3\">⚠️ Nenhum jogador encontrado (Chave Players/Squad ausente).</p>';</script>";
                    }
                }
            } else {
                echo "<script>if(logContainer) logContainer.innerHTML += '<p class=\"text-danger\">❌ Erro no ID {$teamId}. HTTP: {$httpCode}</p>';</script>";
                flush();
            }
            usleep(600000); 
        }

        echo "<script>document.getElementById('sync-status').innerHTML = '<span class=\"text-success\">✅ Sincronização concluída com sucesso!</span>';</script>";
        flush();
        fclose($file);
    }
}
