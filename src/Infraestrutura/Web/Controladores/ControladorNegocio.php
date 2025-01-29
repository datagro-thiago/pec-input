<?php

namespace Src\Infraestrutura\Web\Controladores;

use DateTime;
use Exception;
use Src\Aplicacao\Industria\CasosDeUso\BuscarIndustriaPorNome;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Infraestrutura\Web\Servicos\NegociacaoHandler;
use Src\Infraestrutura\Web\Util\Auditoria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ControladorNegocio
    {
    private NegociacaoHandler $negociacaoHandler;
    private BuscarIndustriaPorNome $industria;
    private string $ini0;
    private string $api_version;
    private string $job;

    public function __construct()
        {
        $this->negociacaoHandler = new NegociacaoHandler();
        date_default_timezone_set("Brazil/East");
        $this->ini0 = microtime(true);
        $this->api_version = "1.0.0";
        $this->job = date("Y-m-d-H-i-s");
        $this->industria = new BuscarIndustriaPorNome();
        }
    public function upload(Request $request): JsonResponse
        {
        try {
            // Inicializar a resposta padrão
            $response = [
                "statusCode" => 200,
            ];

            // Validar login do usuário
            $login = new ControladorLogin();
            $validarLogin = $login->login($request);
            if ($validarLogin["status"] == 0) {
                $response["mensagem"] = "Usuário sem permissão.";
                $response["statusCode"] = 403;
                return new JsonResponse($response, 403);
                }

            // Recuperar arquivo e remetente
            $arquivoLote = $request->files->get("arquivo");
            $remetente = $request->get("remetente") ?? "";

            // Validar se o arquivo e o remetente estão presentes
            if (!$arquivoLote) {
                $response["mensagem"] = "Arquivo ou remetente ausente.";
                $response["statusCode"] = 400;
                return new JsonResponse($response, 400);
                }
            // Criar lote de arquivo
            $tipoArquivo = pathinfo($arquivoLote->getClientOriginalName(), PATHINFO_EXTENSION);
            $tempName = $arquivoLote->getRealPath();
            $lote = new ArquivoLote(
                $arquivoLote->getClientOriginalName(),
                $tipoArquivo,
                date("d-m-Y H:i:s"),
                $remetente,
                $tempName
            );

            // Processar o arquivo
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
            } catch (Exception $e) {
            // Captura qualquer erro inesperado e retorna uma mensagem amigável
            $response["mensagem"] = "Erro inesperado: " . $e->getMessage();
            $response["statusCode"] = 500;
            $response["audit"] = Auditoria::audit($this->ini0, $this->api_version);
            }

        return new JsonResponse($response, $response["statusCode"]);
        }


    // public function recuperarNegociacao(Request $request): JsonResponse {
    //     try {
    //         $nome = !empty($request->get("nome")) ? $request->get("nome"): "";
    //         $senha = !empty($request->get("senha")) ? $request->get("senha"): "";
    //         $data = !empty($request->get("data")) ? $request->get("data"): "";
    //         $id = !empty($request->get("id")) ? $request->get("id"): "";

    //         if (!empty($id) && !empty($senha)) {
    //             $negociacao = $this->buscar->executar(null, null, $id, $senha);
    //             return new JsonResponse($negociacao);
    //         }

    //         if (!empty($nome) && !empty($senha) && !empty($data)) {
    //             $idIndustria = $this->industria->executar($nome);

    //             $negociacoes = $this->buscar->executar($idIndustria["id"], $data, null, $senha);
    //             return new JsonResponse ($negociacoes, 200);
    //         }

    //         return new JsonResponse("ID ou senha invalidos.", 404);

    //     } catch (Exception $e) {
    //         return new JsonResponse($e->getMessage(),500);
    //     }
    // }
    }