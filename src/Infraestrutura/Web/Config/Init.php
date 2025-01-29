<?php

namespace Src\Infraestrutura\Web\Config;

use Src\Aplicacao\Categoria\CasosDeUso\CarregarCategorias;
use Src\Aplicacao\Frete\CasosDeUso\CarregarFretes;
use Src\Aplicacao\Funrural\CasosDeUso\CarregarFunrural;
use Src\Aplicacao\Industria\CasosDeUso\CarregarIndustrias;
use Src\Aplicacao\Modalidade\CasosDeUso\CarregarModalidade;
use Src\Aplicacao\Negociacao\CasosDeUso\CarregarNegociacoesHoje;
use Src\Aplicacao\Nutricao\CasosDeUso\CarregarNutricoes;
use Src\Aplicacao\Planta\CasosDeUso\CarregarPlantas;
use Src\Aplicacao\Raca\CasosDeUso\CarregarRaca;
use Src\Dominio\Negociacao\Entidades\Municipio;
use Src\Dominio\Negociacao\Entidades\PrecoCDI;

class Init
    {
    public function __construct(string $remetente = '')
        {
        // if ($remetente === 'app') {
        //     $this->buscarNegociacoesHoje();
        //     }
        $this->buscarCdi();
        $this->buscarCategorias();
        $this->buscarMunicipios();
        $this->buscarRacas();
        $this->buscarPlantas();
        $this->buscarIndustrias();
        $this->buscarNutricoes();
        $this->buscarFunrural();
        $this->buscarFretes();
        $this->buscarModalidade();
        }

    public function buscarCdi()
        {
        return PrecoCDI::carregarValorCdiDaApi();
        }
    public function buscarNegociacoesHoje()
        {
        return CarregarNegociacoesHoje::executar();
        }
    public function buscarModalidade()
        {
        return CarregarModalidade::executar();
        }
    public function buscarFretes()
        {
        return CarregarFretes::executar();
        }
    public function buscarCategorias()
        {
        return CarregarCategorias::executar();
        }
    public function buscarMunicipios()
        {
        return Municipio::carregarMunicipiosDaApi();
        }
    public function buscarRacas()
        {
        return CarregarRaca::executar();
        }
    public function buscarPlantas()
        {
        return CarregarPlantas::executar();
        }
    public function buscarIndustrias()
        {
        return CarregarIndustrias::executar();
        }
    public function buscarNutricoes()
        {
        return CarregarNutricoes::executar();
        }

    public function buscarFunrural()
        {
        return CarregarFunrural::executar();
        }
    }