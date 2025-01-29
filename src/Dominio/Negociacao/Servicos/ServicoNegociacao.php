<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Aplicacao\Cache\ServicoCache;
use Src\Aplicacao\Categoria\CasosDeUso\BuscarCategoriaPorAlias;
use Src\Aplicacao\Frete\CasosDeUso\BuscarFrete;
use Src\Aplicacao\Funrural\CasosDeUso\BuscarFunruralPorAlias;
use Src\Aplicacao\Industria\CasosDeUso\BuscarIndustriaPorNome;
use Src\Aplicacao\Modalidade\CasosDeUso\BuscarModalidadePorSlug;
use Src\Aplicacao\Nutricao\CasosDeUso\BuscarNutricaoPorAlias;
use Src\Aplicacao\Planta\CasosDeUso\BuscarPlantaPorAlias;
use Src\Aplicacao\Raca\CasosDeUso\BuscarRacaPorAlias;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;


use Src\Dominio\Negociacao\Entidades\Planta;
use Src\Dominio\Negociacao\Enums\OperacaoEnum;
use Src\Dominio\Negociacao\Servicos\ServicoIndustria;
use Src\Dominio\Negociacao\Servicos\ServicoMunicipio;


class ServicoNegociacao
    {
    private ServicoIndustria $servicoIndustria;
    private BuscarIndustriaPorNome $buscarPorNome;
    private BuscarCategoriaPorAlias $buscarCategoria;
    private BuscarFunruralPorAlias $buscarFunrural;
    private BuscarNutricaoPorAlias $buscarNutricao;
    private BuscarPlantaPorAlias $buscarPlanta;
    private BuscarRacaPorAlias $buscarRaca;
    private BuscarFrete $buscarFrete;
    private BuscarModalidadePorSlug $buscarModalidade;

    public function __construct()
        {
        $this->servicoIndustria = new ServicoIndustria();
        $this->buscarPorNome = new BuscarIndustriaPorNome();
        $this->buscarCategoria = new BuscarCategoriaPorAlias();
        $this->buscarFunrural = new BuscarFunruralPorAlias();
        $this->buscarNutricao = new BuscarNutricaoPorAlias();
        $this->buscarPlanta = new BuscarPlantaPorAlias();
        $this->buscarRaca = new BuscarRacaPorAlias();
        $this->buscarFrete = new BuscarFrete();
        $this->buscarModalidade = new BuscarModalidadePorSlug();
        }

    public function definirFonte(string $remetente): string
        {
        $industria = ServicoCache::buscar($remetente, function () use ($remetente) {
            return $this->servicoIndustria->buscarIndustriaPorNome($remetente);
            });
        if ($remetente === "app") {
            return "AC";
            }
        return "I";
        }

    public function definirValorBase(?array $bonus, float $vbonus, string $remetente, float $valorBase, int $diasPgto): float|int
        {
        $valor = 0;
        $valorDi = ServicoCdi::buscarCdi();


        //TODO: PROVISORIO -- Calcula Bonus
        $industriasBonus = ['Minerva', 'BarraMansa1', 'jbsfriboi', 'frialto1', 'Zanchetta'];
        if (in_array($remetente, $industriasBonus)) {
            if ($remetente === 'Minerva') {
                if (!empty($bonus)) {
                    foreach ($bonus as $b) {
                        $valor += (float) $b;
                        }
                    }
                $valorBase = $valor;
                } else {
                $valorBase += $vbonus;
                }
            }

        if ((int) $diasPgto != 0) {
            // $prazoPgto = $diasPgto / 360;
            $t = 1 / 360;
            $taxaDia = pow((1 + $valorDi / 100), $t);
            $taxaPrazo = pow($taxaDia, (int) $diasPgto);
            $valorPresente = $valorBase / $taxaPrazo;
            $valorBase = round($valorPresente, 2);
            }

        return $valorBase;
        }

    public function definirAprovacao(string $fonte, ?string $industria, ?string $aprovado): int
        {
        $i = strtolower($industria);
        $ap = strtolower($aprovado);
        //TODO: passar para o banco de dados essas industrias, e reescrever a condicional
        $industrias = ['frigol', 'friboi', 'barra', 'estrela', 'master', 'natrurafrig', 'sul', 'jbs', 'minerva', 'marfrig', 'zanchetta', 'mondelli', 'prima', 'beter', 'barramansa'];
        $aprovadoCasos = ['aprovado', 'aprovada'];

        // Valida aprovacao para negociacoes vindas do app
        if (!empty($industria) && !in_array($i, $industrias) && in_array($ap, $aprovadoCasos)) {
            return 1;
            }
        if ($fonte === 'I') {
            return 1;
            }
        return 0;
        }
    public function definirParte(ArquivoLote $lote): int
        {
        $remetente = $lote->getRemetente();
        $parte = ServicoCache::buscar($remetente, function () use ($remetente): int {
            return $this->buscarPorNome->executar($remetente);
            });

        if (is_object($parte)) {
            $id = $parte->getId();
            return $id;
            } else {
            return $parte;
            }
        }
    public function definirArquivo(ArquivoLote $arquivo): ArquivoLote
        {
        return $arquivo;
        }
    public function definirCategoria(string $categoria): ?int
        {
        $categoria = ServicoCache::buscar($categoria, function () use ($categoria): int {
            return $this->buscarCategoria->executar($categoria);
            });
        return $categoria;
        }
    public function definirFrete(string $freteNeg, string $remetente): ?string
        {
        $frete = $this->buscarFrete->executar($remetente);
        if (!empty($frete)) {
            return $frete;
            }
        if (empty($freteNeg)) {
            return 'FOB';
            }
        return $freteNeg;
        }
    public function definirOperacao(string $operacao): string
        {
        $validar = OperacaoEnum::isValid($operacao);
        $operacao = !empty($validar) ? $validar : $operacao;

        return $operacao;
        }

    public function definirModalidade(string $modalidade): string
        {
        // TODO: provisorio
        $possiveis = ['Balcao', 'balcao', 'Balcão', 'balcão', 'BALCAO', 'BALCÃO', 'b', 'B'];
        $modalidade = in_array($modalidade, $possiveis) ? 'B' : 'T';

        return $modalidade;
        }

    public function definirBonus(string $bonus, string $vBonus): string
        {
        $estruturaBonus = [
            "bonus" => $bonus,
            "vbonus" => $vBonus,
        ];
        return json_encode($estruturaBonus);
        }

    public function definirFunrural(string $funrural): ?int
        {
        $funrural = ServicoCache::buscar($funrural, function () use ($funrural) {
            return $this->buscarFunrural->executar($funrural);
            });

        return $funrural;
        }

    public function definirNutricao(string $nutricao): ?int
        {
        $nutricao = ServicoCache::buscar($nutricao, function () use ($nutricao) {
            return $this->buscarNutricao->executar($nutricao);
            });
        return $nutricao;
        }

    public function definirPlanta(string $planta, int $idIndustria): ?Planta
        {
        $planta = ServicoCache::buscar($planta, function () use ($planta, $idIndustria) {
            return $this->buscarPlanta->executar($planta, $idIndustria);
            });
        if (is_int($planta) || empty($planta)) {
            return null;
            }
        return $planta;
        }

    public function definirRaca(string $raca): ?int
        {
        $raca = ServicoCache::buscar($raca, function () use ($raca) {
            return $this->buscarRaca->executar($raca);
            });
        return $raca;
        }

        public function resolverMunicipio(string $origemM, string $destinoM, ?string $ufPlanta): array
        {
            $buscarMunicipioComCache = function (string $nome) use ($ufPlanta) {
                return ServicoCache::buscar($nome, function () use ($nome, $ufPlanta) {
                    return ServicoMunicipio::buscarMunicipio($nome, $ufPlanta);
                });
            };
            $formatarMunicipio = fn(string $municipio): string => 
                trim(str_replace('NA -', '', $municipio));
        
            // Formatar e buscar origem e destino
            $origem = $buscarMunicipioComCache($formatarMunicipio($origemM));
            $destino = $buscarMunicipioComCache($formatarMunicipio($destinoM));
        
            // Verificar múltiplas ocorrências
            $origemRepetidos = isset($origem['repetidos']) && count($origem['repetidos']) > 1;
            $destinoRepetidos = isset($destino['repetidos']) && count($destino['repetidos']) > 1;
        
            if ($origemRepetidos && $destinoRepetidos) {
                return [
                    'origemUf' => $ufPlanta,
                    'destinoUf' => $ufPlanta,
                ];
            }
//          Se a origem for repetida e o destino não, retorna o estado do destino        
            if ($origemRepetidos && !empty($destino['estado'])) {
                return [
                    'origem' => $destino['id'],
                    'destino' => $destino['id'],
                    'origemUf' => $destino['estado'],
                    'destinoUf' => $destino['estado'],
                ];
            }
//          Se o destino for repetido e a origem não, retorna o estado da origem       

            if ($destinoRepetidos && !empty($origem['estado'])) {
                return [
                    'origem' => $origem['id'],
                    'destino' => $origem['id'],
                    'origemUf' => $origem['estado'],
                    'destinoUf' => $origem['estado'],
                ];
            }
     
            // Retorno padrão
            return [
                'origem' => $origem['id'] ?? 0,
                'destino' => $destino['id'] ?? 0,
                'origemUf' => $origem['estado'] ?? '',
                'destinoUf' => $destino['estado'] ?? '',
            ];
        }
        
    public function definirMunicipio(string $origemM, string $destinoM, ?string $uf, string $idNegocio): array
        {
        // Validar se a origem ou destino é "EX"
        if ($origemM === 'EX' || $destinoM === 'EX') {
            return [
                "origem" => 0,
                "destino" => 0,
            ];
            }

        // Resolver UF considerando regras de múltiplas ocorrências
        $ufs = $this->resolverMunicipio($origemM, $destinoM, $uf);

        // Retornar IDs e UFs resolvidos
        return [
            'origem' => $ufs['origem'],
            'destino' => $ufs['destino'],
            'origemUf' => $ufs['origemUf'],
            'destinoUf' => $ufs['destinoUf'],
        ];
        }



    }