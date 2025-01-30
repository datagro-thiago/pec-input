<?php

namespace Src\Infraestrutura\Jobs\NegociacoesApp;

use DateTime;
use Google\Cloud\Core\Timestamp;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Infraestrutura\Web\Servicos\Firebase\FirebaseConn;
use Src\Infraestrutura\Web\Servicos\NegociacaoHandler;
use Src\Infraestrutura\Web\Util\Auditoria;

class BuscarNegociacoesApp
    {
    private NegociacaoHandler $negociacaoHandler;
    private string $job;
    private string $ini0;
    private string $api_version;
    public function __construct()
        {
        $this->negociacaoHandler = new NegociacaoHandler();
        $this->job = date("Y-m-d-H-i-s");
        date_default_timezone_set("Brazil/East");
        $this->ini0 = microtime(true);
        $this->api_version = "1.0.0";
        }
    public function executar(): void
        {
        $response = [];
        $jobs = LOGS;
        // Conexão com o Firebase
        $factory = FirebaseConn::conn();
        $caminho = $jobs . $this->job . "-neg.json";

        //Ira pegar o primeiro dia do mes, comparar com o atual e calcular a diferenca entre eles
        $diaAtual = new DateTime();
        $primeiroDiaMes = new DateTime('first day of this month');
        $diferenca = $diaAtual->diff($primeiroDiaMes);
        $dias = $diferenca->days;

        // Número de dias para filtrar os negócios
        $dd = date('Y-m-d H:i:s', strtotime('-' . $dias . ' day'));
        $stamp = new Timestamp(new DateTime($dd)); // Usando \DateTime para evitar conflito com namespace

        // Abrindo a base de dados Firestore
        $firestore = $factory->createFirestore();
        $database = $firestore->database();
        $collection = $database->collection('publications');

        // Consultando os negócios criados após a data calculada
        $query = $collection->where('type', '=', 'negotiations')->where('creationDate', '>', $stamp);
        $set = $query->documents();

        $json = [];
        $n = 0;

        // Processando os negócios
        foreach ($set as $reg) 
            {
            $nome = substr(strrchr($reg->reference()->name(), "/"), 1); // Pegando o nome da referência
            $j = $reg->data();
            $json[$nome] = $j; // Adicionando o dado no array
            $n++;
            }
        if (!file_exists(($caminho))) 
            {
            file_put_contents($caminho, json_encode($json, JSON_UNESCAPED_SLASHES));
            }
        $lote = new ArquivoLote(
            $this->job . "-neg.json",
            "json",
            date("d-m-Y H:i:s"),
            "app",
            CAMINHO_LOGS,
        );

        echo "Negócios encontrados: $n\n";
        $handler = $this->negociacaoHandler->executar($lote, $this->job);

        if ($handler["status"] === 0) {
            $response["mensagem"] = $handler["mensagem"];
            $response["statusCode"] = 400;
            $response["audit"] = Auditoria::audit($this->ini0, $this->api_version);

            } else {
            $response["mensagem"] = $handler["mensagem"];
            $response["rastreio"] = $handler["rastreio"];
            $response["audit"] = Auditoria::audit($this->ini0, $this->api_version);
            }

            // print_r($response);
        }
    }
