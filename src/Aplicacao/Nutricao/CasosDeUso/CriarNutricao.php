<?php

namespace Src\Aplicacao\Nutricao\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Nutricao;
use Src\Dominio\Negociacao\Gateways\NutricaoGateway;
use Src\Dominio\Negociacao\Servicos\ServicoNutricao;
use Src\Infraestrutura\Bd\Persistencia\NutricaoRepositorio;

class CriarNutricao
    {
    private ServicoNutricao $servicoNutricao;
    private NutricaoGateway $gateway;

    public function __construct()
        {
        $this->servicoNutricao = new ServicoNutricao();
        $this->gateway = new NutricaoRepositorio();
        }

    public function executar(string $nome)
        {
        $id = 0;
        $nomeFormatado = $this->servicoNutricao->formatarNome($nome);
        $alias = [
            $nomeFormatado
        ];
        $planta = Nutricao::novo(
            $nome,
            $alias
        );
        $identificadorTab = $this->gateway->salvar($planta);
        if (!empty($identificadorTab)) {
            $id = $identificadorTab;
            }

        return $id;
        }

    }