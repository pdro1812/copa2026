import requests
import time
import os
import csv

# Configurações da varredura
id_alvo = 285023
intervalo = 2 # Pode aumentar esse número para varrer mais de uma vez!
id_inicio = id_alvo - intervalo
id_fim = 285023

# Caminho absoluto para a raiz do projeto (onde o script está localizado)
diretorio_raiz = os.path.dirname(os.path.abspath(__file__))
nome_arquivo = os.path.join(diretorio_raiz, "selecoes_ids.csv")

# Parâmetros fixos da API
id_competition = 17
id_season = 285023
language = "pt"

headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    "Accept": "application/json",
    "Accept-Language": "pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7"
}

# --- 1. VERIFICA O QUE JÁ EXISTE NO ARQUIVO ---
ids_ja_existentes = set()
if os.path.exists(nome_arquivo):
    with open(nome_arquivo, mode='r', encoding='utf-8') as arquivo:
        leitor = csv.reader(arquivo)
        next(leitor, None)  # Pula a primeira linha (cabeçalho)
        for linha in leitor:
            if linha:  # Se a linha não estiver vazia
                ids_ja_existentes.add(int(linha[0]))  # Adiciona o ID salvo na memória

times_encontrados = []

print(f"🔍 Iniciando varredura de IDs: do {id_inicio} até o {id_fim}...")
print(f"📂 Arquivo atual possui {len(ids_ja_existentes)} seleções salvas.")
print("-" * 50)

# --- 2. FAZ A VARREDURA ---
for team_id in range(id_inicio, id_fim + 1):
    url = f"https://api.fifa.com/api/v3/teams/{team_id}/squad?idCompetition={id_competition}&idSeason={id_season}&language={language}"
    
    try:
        response = requests.get(url, headers=headers)
        
        if response.status_code == 200:
            dados = response.json()
            
            if dados.get("TeamName") is not None:
                nome_pais = dados["TeamName"][0]["Description"]
                
                # Regra do Alerta: Verifica se o ID já estava no arquivo
                if team_id in ids_ja_existentes:
                    print(f"⚠️ ALERTA: A seleção {nome_pais} (ID {team_id}) já está no arquivo, mas será salva novamente!")
                else:
                    print(f"✅ SUCESSO: ID {team_id} -> {nome_pais}")
                
                times_encontrados.append({"ID": team_id, "Pais": nome_pais})
            else:
                print(f"❌ VAZIO: ID {team_id}")
                
        else:
            print(f"⚠️ ERRO DA API no ID {team_id}. Status Code: {response.status_code}")
            
    except Exception as e:
        print(f"🛑 Erro no script ao tentar o ID {team_id}: {e}")
    
    time.sleep(1)

# --- 3. SALVA OS DADOS NO ARQUIVO ---
print("\n" + "=" * 50)
print("📊 RESUMO DA VARREDURA")
print("=" * 50)
print(f"Total de países encontrados nesta rodada: {len(times_encontrados)}")

if times_encontrados:
    # Abre o arquivo em modo 'a' (append) para adicionar no final sem apagar o que já tem
    arquivo_existe = os.path.exists(nome_arquivo)
    
    with open(nome_arquivo, mode='a', newline='', encoding='utf-8') as arquivo:
        escritor = csv.writer(arquivo)
        
        # Se o arquivo não existia, cria o cabeçalho primeiro
        if not arquivo_existe:
            escritor.writerow(["ID", "Pais"])
            
        # Salva os times encontrados na rodada
        for time_valido in times_encontrados:
            escritor.writerow([time_valido['ID'], time_valido['Pais']])
            
    print(f"💾 Dados salvos com sucesso no arquivo '{nome_arquivo}'!")
else:
    print("Nenhum dado novo para salvar.")