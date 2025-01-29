<?php

namespace Src\Dominio\Negociacao\Entidades;

class Municipio
    {

    private string $id;
    private string $nome;
    private string $estado;
    private string $pais;
    private string $pais_alpha2;
    private string $pais_num;
    private string $slug;

    private static array $municipiosCache = []; // Armazena os municípios em memória

    public function __construct(
        string $id,
        string $nome,
        string $estado,
        string $pais,
        string $pais_alpha2,
        string $pais_num,
        string $slug,
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->estado = $estado;
        $this->pais = $pais;
        $this->pais_alpha2 = $pais_alpha2;
        $this->pais_num = $pais_num;
        $this->slug = $slug;
        }

    public static function carregarMunicipiosDaApi(): bool
        {
        $url = "https://precos.api.datagro.com/basics/municipios.php?aliases=true&";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos

        $resposta = curl_exec($ch);
        $data = json_decode($resposta, true);
        $municipios = [];

        if (isset($data['municipios'])) {
            $municipios = $data['municipios'];
            }

        if (curl_errno($ch)) {
            echo 'Erro cURL: ' . curl_error($ch);
            return false;
            }

        curl_close($ch);

        // Armazenar os dados em memória
        self::$municipiosCache = array_map(function ($item) {
            return new self(
                $item['id'],
                $item['nome'],
                $item['estado'],
                $item['pais'],
                $item['pais_alpha2'],
                $item['pais_num'],
                $item['slug'],
            );
            }, $municipios);

        return true;
        }

    public static function formatarNome(string $nome): string
        {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $nomeSemEstado = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeSemEstado = explode(' / ', $nomeSemEstado)[0];
        $nomeSemEspacosETraços = str_replace([' ', '-'], '', $nomeSemEstado); // Remove espaços e traços

        return strtolower($nomeSemEspacosETraços); // Retorna em minúsculas
        }
    public static function formatarNomeSemEstado(string $nome): array
        {
        // Remove tudo após traço ou barra
        $nomeSemEstado = explode(' - ', $nome)[0];
        $nomeSemEstado = explode('-', $nomeSemEstado)[0];
        $nomeSemEstado = explode(' -', $nomeSemEstado)[0];
        $nomeSemEstado = explode(' / ', $nomeSemEstado)[0];

        // Remove a UF se estiver separada por espaço
        $nomeSemEstado = preg_replace('/ [A-Z]{2}$/', '', $nomeSemEstado);

        // Remove acentos
        $nomeSemAcentos = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeSemEstado);

        // Remove todos os caracteres que não sejam letras ou números
        $nomeSemCaracteresEspeciais = preg_replace('/[^a-zA-Z0-9 ]/', '', $nomeSemAcentos);
        $tratamentoFinal = strtolower(trim(str_replace(' ', '', $nomeSemCaracteresEspeciais)));

        $estado = self::extrairEstado($nome);
        // Remove espaços extras e converte para letras minúsculas
        return ['nome' => $tratamentoFinal, 'estado' => $estado];
        }

    public static function validarEstado(Municipio $municipio, string $estado): bool
        {
        if ($municipio->getEstado() === $estado) {
            return true;
            }
        return false;
        }
    public static function extrairEstado(string $nome): string
        {
        $estado = '';
        if (preg_match('/[A-Z]{2}$/', $nome, $matches)) {
            $estado = $matches[0];
            }
        // Retorna o estado
        return $estado;
        }

    public static function buscarPorNome(string $nome, ?string $estado): ?array
        {
        foreach (self::$municipiosCache as $municipio) {
            $validarEstado = !empty($estado) ? self::validarEstado($municipio, $estado) : null;
            if (strcasecmp($municipio->getNome(), $nome) === 0 && $validarEstado) {
                return ['id' => $municipio->getId(), 'estado' => $municipio->getEstado()];
                }
            }
        var_dump('NOME');

        return null;
        }


    public static function buscarPorEstado(?string $estado): ?array
        {
        if (!empty($estado)) {
            foreach (self::$municipiosCache as $municipio) {
                if (strcasecmp($municipio->getEstado(), $estado) === 0) {
                    return ['id' => $municipio->getId(), 'estado' => $municipio->getEstado()];
                    }
                }
            }
        return null;
        }


    public static function gerarSlug($nome)
        {
        // Remove acentos
        $nome = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
        // Converte para minúsculas
        $nome = mb_strtolower($nome);
        // Remove palavras irrelevantes mesmo quando concatenadas
        $nome = preg_replace('/[^a-z0-9]/', '', $nome);

        return $nome;
        }

    public static function buscarPorSlug(string $slug, ?string $estado): ?array
        {
        var_dump('SLUG');

        $municipiosEncontrados = array_filter(
            self::$municipiosCache,
            fn($municipio) =>
            strcasecmp($municipio->getSlug(), $slug) === 0
        );

        if (count($municipiosEncontrados) === 1) {
            $municipio = reset($municipiosEncontrados);
            return ['id' => $municipio->getId(), 'estado' => $municipio->getEstado()];
            }

        if (count($municipiosEncontrados) > 1) {
            return ['repetidos' => $municipiosEncontrados];
            }
        return null;
        }


    public static function buscarPorSimilaridade(string $nome, ?string $estado): ?array
        {
        var_dump('SIMILARIDADE');

        $melhorSimilaridade = 0; // Armazena a maior similaridade encontrada
        $melhorMunicipio = null; // Armazena o melhor município encontrado

        // Transformar o nome em slug para comparação
        $slugBusca = self::gerarSlug($nome);

        foreach (self::$municipiosCache as $municipio) {
            // Transformar o nome do município em slug
            $slugMunicipio = self::gerarSlug($municipio->getNome());

            // Calcular a distância de Levenshtein entre os slugs
            $distancia = levenshtein($slugBusca, $slugMunicipio);
            $comprimentoMax = max(strlen($slugBusca), strlen($slugMunicipio));

            // Calcular a similaridade percentual
            $percentualSimilaridade = (1 - $distancia / $comprimentoMax) * 100;

            // Verificar se a similaridade é maior que a melhor encontrada e acima do limite
            if ($percentualSimilaridade > $melhorSimilaridade && $percentualSimilaridade >= 70) {
                // Validar o estado, se necessário
                if (!empty($estado)) {
                    if (self::validarEstado($municipio, $estado)) {
                        $melhorSimilaridade = $percentualSimilaridade;
                        $melhorMunicipio = $municipio;
                        }
                    } else {
                    // Sem estado, aceitar o município
                    $melhorSimilaridade = $percentualSimilaridade;
                    $melhorMunicipio = $municipio;
                    }
                }
            }

        // Retornar o município encontrado ou um padrão se nenhum foi encontrado
        if ($melhorMunicipio !== null) {
            return [
                'id' => $melhorMunicipio->getId(),
                'estado' => $melhorMunicipio->getEstado(),
            ];
            }

        // Caso não encontre nenhuma similaridade acima do limite
        return ['id' => 0, 'estado' => null];
        }


    public function getId(): string
        {
        return $this->id;
        }

    public function getNome(): string
        {
        return $this->nome;
        }

    public function getEstado(): string
        {
        return $this->estado;
        }

    public function getPais(): string
        {
        return $this->pais;
        }

    public function getPaisAlpha2(): string
        {
        return $this->pais_alpha2;
        }

    public function getPaisNum(): string
        {
        return $this->pais_num;
        }

    public function getSlug(): string
        {
        return $this->slug;
        }

    }
