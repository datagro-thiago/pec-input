<?php

namespace Src\Dominio\Arquivo\Servicos;

class ServicoArquivoLote
    {
    public function pegarExtensaoArquivo(string $extensaoArquivo): array
        {
        $status = 1;
        $mensagem = "";

        $extensao = $extensaoArquivo;

        if ($extensao == "") {
            $status = 0;
            $mensagem = "Erro ao verificar extensao do arquivo: $extensaoArquivo";
            }
        return
            [
                "extensao" => $extensao,
                "status" => $status,
                "mensagem" => $mensagem
            ];
        }

    public function moverArquivo(string $atual, string $destino): array
        {
        $mensagem = "";
        $status = 1;
        $mover = move_uploaded_file($atual, $destino);
        if (!$mover) {
            $status = 0;
            $mensagem = "Falha ao mover o arquivo para: $destino";
            }

        return
            [
            "status" => $status,
            "mensagem" => $mensagem
            ];
        }

    //Trato o json caso venha do app
    public function tratarJsonApp(array $json): array
    {
        $novaEstrutura["negocios"] = [];
        $chaves = [
            "status" => "aprovado",
            "tradingDate" => "datahora",
            "quantity" => "quantidade",
            "modality" => "modalidade",
            "type" => "operacao",
            "bonusValue" => "vbonus",
            "category" => "categoria",
            "originCity" => "origem",
            "breed" => "raca",
            "destinationCity" => "destino",
            "freight" => "frete",
            "nutrition" => "nutricao",
            "payment" => "diasPagto",
            "slaughterDate" => "dtAbate",
            "property" => "planta",
            "weightMode" => "pesomodo",
            "bonus" => "bonus",
            "value" => "valor",
            "slaughterHouse" => 'industria',
        ];

        $count = 0;
        // Itera sobre o array principal
        foreach ($json as $chave => $valor) {
            // Itera sobre o conteúdo do elemento atual
            foreach ($valor["content"] as $key => $value) {
                // Verifica se a chave está no mapeamento
                if (array_key_exists($key, $chaves)) {
                    $novaChave = $chaves[$key];
                    $novaEstrutura["negocios"][$count]["idNegocio"] = $chave;
                    // Adiciona os dados mapeados na estrutura "negocios"
                    if (!isset($novaEstrutura["negocios"][$count])) {
                        $novaEstrutura["negocios"][$count] = [];
                    }
                    $novaEstrutura["negocios"][$count][$novaChave] = $value;
                }
            }
            $count ++;
        }
        return $novaEstrutura;
    }
    }