<?php

namespace Src\Aplicacao\Modalidade\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Modalidade;
use Src\Dominio\Negociacao\Gateways\ModalidadeGateway;
use Src\Dominio\Negociacao\Servicos\ServicoModalidade;
use Src\Infraestrutura\Bd\Persistencia\ModalidadeRepositorio;

class CriarModalidade
    {

    private ServicoModalidade $servicoModalidade;
    private ModalidadeGateway $gateway;

    public function __construct()
        {
        $this->servicoModalidade = new ServicoModalidade();
        $this->gateway = new ModalidadeRepositorio();
        }

    public function executar(string $nome, string $slug, string $enum): int
        {
        $time = microtime();

        $retorno = 0;
        $nomeFormatado = $this->servicoModalidade->formatarNome($nome);
        $slug = [$nomeFormatado];
        $modalidade = Modalidade::novo(
            $nome,
            $slug,
            $enum
        );

        $id = $this->gateway->salvar($modalidade);

        if (!empty($id)) {
            $retorno = $id;
            }

        return $retorno;
        }
    }