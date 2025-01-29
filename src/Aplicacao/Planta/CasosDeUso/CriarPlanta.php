<?php

namespace Src\Aplicacao\Planta\CasosDeUso;

use Src\Dominio\Negociacao\Gateways\PlantaGateway;
use Src\Dominio\Negociacao\Servicos\ServicoPlanta;
use Src\Dominio\Negociacao\Entidades\Planta;
use Src\Infraestrutura\Bd\Persistencia\PlantaRepositorio;

class CriarPlanta
    {
    private ServicoPlanta $servicoPlanta;
    private PlantaGateway $gateway;

    public function __construct()
        {
        $this->servicoPlanta = new ServicoPlanta();
        $this->gateway = new PlantaRepositorio();
        }

    public function executar(string $nome, int $industriaId): ?Planta
        {
        $slug = $this->servicoPlanta->formatarNome($nome);
        $alias = [
            $slug
        ];
        $planta = Planta::novo(
            $nome,
            $alias,
            $industriaId,
            null
        );
        $identificadorTab = $this->gateway->salvar($planta);
        if (!empty($identificadorTab)) {
            return $planta;
            }
        return null;
        }

    }