<?php


namespace Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso;

use Exception;
use RuntimeException;
use Shuchkin\SimpleXLSX;
use Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso\RegistrarArquivoLoteNaBase;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Arquivo\Servicos\ServicoArquivoLote;

class PrepararArquivoLote
    {
    private RegistrarArquivoLoteNaBase $registrar;
    private ServicoArquivoLote $servico;
    public function __construct()
        {
        $this->servico = new ServicoArquivoLote();
        $this->registrar = new RegistrarArquivoLoteNaBase();
        }
    public function executar(ArquivoLote $lote, string $dirLote): array
        {
        $time = microtime();
        $dados = [];
        try {
            $this->registrar->executar($lote, $dirLote);

            if (strtoupper($lote->getTipo()) === "JSON") {
                $dados = $this->preparaJson($lote->getId()->idString(), $dirLote, $lote->getRemetente());
                }

            if (strtoupper($lote->getTipo()) === "XLS" || strtoupper($lote->getTipo()) === "XLSX") {
                $dados = $this->preparaXlsx($lote->getId()->idString(), $dirLote, $lote->getRemetente());
                }

            if (strtoupper($lote->getTipo()) === "CSV") {
                $dados = $this->preparaCsv($lote->getId()->idString(), $dirLote, $lote->getRemetente());
                if (empty($lote->getRemetente())) {
                    $lote->setRemetente($dados['lote']['industria']);
                    }
                }
            return [
                "dados" => $dados,
                "lote" => $lote
            ];
            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }
        }

    private function preparaJson(string $id, string $caminho, string $remetente): array
        {
        $time = microtime();
        try {
            $matriz = file_get_contents($caminho);
            //se estiver em um encoding diferente, converto
            if (($enc = mb_detect_encoding($matriz, ['ASCII', 'UTF-8', 'ISO-8859-1'])) != 'UTF-8') {
                $matriz = iconv($enc, 'UTF-8', $matriz);
                }
            $dados = json_decode($matriz, true, 512, JSON_THROW_ON_ERROR);

            if ($remetente === "app") {
                $dadosApp = $this->servico->tratarJsonApp($dados);
                $resultado = $this->alteraChaveArray($dadosApp, CASE_LOWER);
                } else {
                $resultado = $this->alteraChaveArray($dados, CASE_LOWER);
                }

            // Verifica se 'NEGOCIOS' existe no array $dados
            if (isset(($resultado['negocios'])) && is_array($resultado['negocios'])) {
                foreach ($resultado['negocios'] as $index => &$negocio) {
                    $negocio += ['arquivo' => $id, 'linha' => $index]; //add id e linha ao negocio arquivo
                    }
                unset($negocio);
                } else {
                throw new Exception("A chave 'NEGOCIOS' não foi encontrada no arquivo JSON.");
                }

            return $resultado;
            } catch (Exception $e) {
            return ["mensagem" => "Erro inesperado: " . $e->getMessage()];
            }
        }

    //responsavel por fazer um processamento recursivo no array e transformar todas as chaves em minusculas
    function alteraChaveArray(array $array, int $case = CASE_LOWER): array
        {
        return array_map(
            fn($value) => is_array($value) ? $this->alteraChaveArray($value, $case) : $value,
            array_change_key_case($array, $case)
        );
        }
    private function preparaXlsx(string $id, string $dirLote, string $remetente): array
        {
        $time = microtime();
        try {
            $dados = [];
            $headers = [];
            $l = 5;
                
            
            if (!file_exists($dirLote)) {
                throw new RuntimeException("Arquivo não encontrado em: $dirLote");
                }

                $mapaColunas = [
                    'Modalidade' => 'modalidade',
                    'Categoria' => 'categoria',
                    'Quantidade' => 'quantidade',
                    'Valor Sem Bonificação' => 'valor',
                    'Bonus' => 'bonus',
                    'Valor do Bonus' => 'vbonus',
                    'Data da Negociação' => 'datahora',
                    'Data do Abate' => 'dtabate',
                    'Dias de Pagamento' => 'diasPagto',
                    'UF origem' => 'origemUf',
                    'UF destino' => 'destinoUf',
                    'Cidade Origem' => 'origem',
                    'Cidade Destino' => 'destino',
                    'Funrural' => 'funrural',
                    'Frete' => 'frete',
                    'Nutrição' => 'nutricao',
                    'Raça' => 'raca',
                    'Planta Frigorífica' => 'planta',
                    'Modalidade de Pesagem' => 'pesomodo',
                ];

            ////////////////////////////////////////////////////////////////////////
            if ($remetente === "Minerva") {
                $l = 0;
                $mapaColunas = [
                    'codigo' => 'idNegocio',
                    'Data Abate' => 'dtabate',
                    'Qtd. Produto' => 'quantidade',
                    'Negociação' => 'modalidade',
                    'Valor' => 'valor',
                    'Prazo Pagamento (Dias)' => 'diasPagto',
                    'Raça' => 'raca',
                    'Estado (Fazenda)' => 'origemUf',
                    'Estado (Fazenda)' => 'destinoUf',
                    'Cód. Empresa' => 'idIndustria',
                    'Descrição Produto' => 'categoria',
                    'Orgânico' => 'nutricao',
                    'Valor Frete (Estimativa)' => 'frete',
                    'Valor Premiação Cobertura' => 'bonus',
                    'Valor Premiação Angus' => 'vbonus',
                    'Terminação' => 'nutricao',
                    'Data Lançamento' => 'datahora',
                    'Valor @ Sem Premiação' => 'vs',
                    'Valor Premiação U.E' => 'vpue',
                    'Valor Premiação U.E. HQB' => 'vpuehqb',
                    'Valor Premiação Idade' => 'vpi'
                ];
                }

            if ($planilha = SimpleXLSX::parseFile($dirLote)) {
                foreach ($planilha->readRows() as $index => $linha) {
                    if ($index === $l) {
                        foreach ($linha as $colunaIndex => $header) {
                            array_push($headers, $header); // Amazena os cabecalhos
                            }
                        } else {
                        // Processar  os dados das linhas seguintes
                        foreach ($linha as $colunaIndex => $valor) {
                            $header = $headers[$colunaIndex] ?? null; // Obter o cabeçalho correspondente
                            if ($header and isset($mapaColunas[$header])) {
                                $linhaDados[$mapaColunas[$header]] = trim((string) $valor); // Remover espaços
                                if ($remetente === "Minerva") { 
                                    if (isset($linhaDados['destinoUf'])) {
                                        $linhaDados['origemUf'] = $linhaDados['destinoUf']; // 'origem' igual a 'destino'
                                        }
                                    }
                                }
                            }
                        $linhaDados['linha'] = $index + 1;
                        $linhaDados['arquivo'] = $id;

                        // Verifica se a chave "idNegocio" existe e se o valor é diferente de 0
                        if (!empty($linhaDados) && (!isset($linhaDados['idNegocio']) || $linhaDados['idNegocio'] != 0)) {
                            $dados[] = $linhaDados;
                            }
                        }
                    }
                return ['NEGOCIOS' => $dados];
                } else {
                return ['mensagem' => SimpleXLSX::parseError()];
                }
            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }
        }

    public function preparaCsv(string $id, string $caminho, string $rementente): array
        {
        $negociacoes = [];
        $dados = [];
        $cabecalho = [];

        if (($handle = fopen($caminho, "r")) !== FALSE) {
            // Lê todas as linhas do CSV
            while (($data = fgetcsv($handle, null, ';')) !== FALSE) {
                $dados[] = $data; // $data será um array com os valores de cada linha
                }
            fclose($handle);
            }

        if (count($dados) > 0) {
            // Define o cabeçalho
            $cabecalho = array_map('trim', $dados[0]);

            for ($i = 1; $i < count($dados); $i++) {
                $linha = array_map('trim', $dados[$i]);

                if (count($linha) === count($cabecalho)) {
                    // Mapeia os valores da linha com base no cabeçalho
                    $linhaAssociativa = array_combine($cabecalho, $linha);
                    $resultado = $this->alteraChaveArray($linhaAssociativa, CASE_LOWER);

                    if ($resultado) {
                        $dadosLote = [
                            'industria' => $resultado['industria'] ?? "",
                            'lote' => $resultado['lote'] ?? null,
                            'filename' => $resultado['filename'] ?? null
                        ];

                        $negociacoes[] = [
                            'datahora' => $resultado['datahora'] ?? null,
                            'idNegocio' => $resultado['idnegocio'] ?? null,
                            'bonus' => $resultado['bonus'] ?? null,
                            'vbonus' => $resultado['vbonus'] ?? null,
                            'raca' => $resultado['raca'] ?? null,
                            'categoria' => $resultado['categoria'] ?? null,
                            'destino' => $resultado['destino'] ?? null,
                            'frete' => $resultado['frete'] ?? null,
                            'funrural' => $resultado['funrural'] ?? null,
                            'nutricao' => $resultado['nutricao'] ?? null,
                            'origem' => $resultado['origem'] ?? null,
                            'diasPagto' => $resultado['diasPagto'] ?? ($resultado["diaspagto"] ?? null),
                            'quantidade' => $resultado['quantidade'] ?? null,
                            'dtAbate' => $resultado['dtAbate'] ?? ($resultado["dtabate"] ?? null),
                            'planta' => $resultado['planta'] ?? null,
                            'valor' => $resultado['valor'] ?? null,
                            'pesomodo' => $resultado['pesomodo'] ?? null,
                            'pesopercent' => $resultado['pesopercent'] ?? null,
                            'modalidade' => $resultado['modalidade'] ?? null,
                            'linha' => $i,
                            'arquivo' => $id
                        ];
                        }
                    }
                }
            }
        return ['NEGOCIOS' => $negociacoes, 'lote' => $dadosLote];
        }
    }
