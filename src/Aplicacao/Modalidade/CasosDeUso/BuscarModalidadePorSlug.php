<?php
namespace Src\Aplicacao\Modalidade\CasosDeUso;

use Exception;
use Src\Aplicacao\Modalidade\CasosDeUso\CriarModalidade;
use Src\Dominio\Negociacao\Servicos\ServicoModalidade;

class BuscarModalidadePorSlug
    {
    private ServicoModalidade $servicoModalidade;
    private CriarModalidade $criarModalidade;
    private AtualizarSlug $atualizarSlug;

    public function __construct()
        {
        $this->servicoModalidade = new ServicoModalidade();
        $this->criarModalidade = new CriarModalidade();
        $this->atualizarSlug = new AtualizarSlug();
        }
        public function executar(string $nome): string | array
        {
            $time = microtime();
            try {
                $retorno = 0;
        
                // Verificar se o primeiro caractere do nome é 'b'
                $primeiroChar = substr($nome, 0, 1);
                $enum = (strtoupper($primeiroChar) === 'B') ? strtoupper($primeiroChar) : 'T';
        
                // Formatar o nome uma única vez
                $formatarNome = $this->servicoModalidade->formatarNome($nome);
        
                // Buscar modalidade pelo slug formatado
                $modalidade = $this->servicoModalidade->buscarModalidadePorSlug($formatarNome);
                
                $slugs = [];
                $idModalidade = null;
        
                if (empty($modalidade) && !empty($nome)) {
                    // Buscar modalidade similar
                    $similar = $this->servicoModalidade->buscarPorSimilaridade($nome);
        
                    if ($similar) {
                        // Adicionar slug ao conjunto de slugs da modalidade similar
                        $slugs = $similar->getSlug();
                        array_push($slugs, $formatarNome);
                        $idModalidade = $similar->getId();
                        $enum = $similar->getEnum(); // Atualizar enum baseado no similar
                    } else {
                        // Criar nova modalidade caso nenhuma similar seja encontrada
                        $retorno = $this->criarModalidade->executar($nome, $formatarNome, $enum);
                    }
                } elseif (!empty($modalidade)) {
                    // Adicionar slug ao conjunto de slugs da modalidade existente, se necessário
                    $slugs = $modalidade->getSlug();
                    if (!in_array($formatarNome, $slugs)) {
                        array_push($slugs, $formatarNome);
                        $idModalidade = $modalidade->getId();
                    }
                    $enum = $modalidade->getEnum(); // Atualizar enum baseado na modalidade existente
                }
        
                // Atualizar slugs uma única vez, se necessário
                if (!empty($slugs) && $idModalidade) {
                    $retorno = $this->atualizarSlug->executar($idModalidade, $slugs);
                }
        
                // Retornar enum se operação foi bem-sucedida
                if ($retorno == 1 || !empty($modalidade)) {
                    return $enum;
                }
        
                // Retornar nome como fallback
                return $nome;
            } catch (Exception $e) {
                // Retornar mensagem de erro em caso de exceção
                return ["mensagem" => $e->getMessage()];
            }
        }
        
    }