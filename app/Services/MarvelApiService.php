<?php

namespace App\Services;

use Exception;

/**
 * Class MarvelApiService
 * @package App\Services
 */
class MarvelApiService extends Service
{
    /**
     * @var mixed
     */
    protected $publicKey;
    /**
     * @var mixed
     */
    protected $privateKey;
    /**
     * @var int
     */
    protected $limitPerPage;
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var string[]
     */
    protected $routes;

    public function __construct()
    {
        //chave publica de acesso a api
        $this->publicKey = env('MARVEL_PUBLIC_KEY', null);
        //chave privada de acesso a api - salva no banco de dados
        $this->privateKey = env('MARVEL_PRIVATE_KEY', null);
        // dominio para acesso aos dados da api
        $this->domain = 'gateway.marvel.com';
        // limite de personagens por página
        $this->limitPerPage = 6;
        // rotas de acesso aos dados da api
        $this->routes = [
            'characters' => '/v1/public/characters',
            'stories' => '/v1/public/stories',
        ];
    }

    /**
     * @return string,
     *
     */
    protected function mountAccessUrl()
    {
        $ts = time();
        // monta a hash necessária para consultar os dados na api da marvel
        return "?ts=" . $ts . "&apikey=" . $this->publicKey . "&hash=" . md5($ts . $this->privateKey . $this->publicKey);
    }

    /**
     * @param int $page
     * @return string
     */
    protected function mountPageNumber(int $page)
    {
        // monta a paginação dos itens na api da marvel
        return "&offset=" . (($page - 1) * $this->limitPerPage);
    }

    /**
     * @param string|null $name
     * @return string
     */
    protected function mountNameToSearch(string $name = null)
    {
        // Caso receba o nome, monta a string de pesquisa
        return $name ? "&nameStartsWith=" . $name : '';
    }

    /**
     * @param string|null $path
     * @param string|null $extension
     * @return string
     */
    protected function mountImageUrl(string $path = null, string $extension = null)
    {
        // Verifica se recebeu o dados para montar a url da imagem
        if (!empty($path) && !empty($extension)) {
            // retorna a url completa da imagem do personagem
            return $path . "/landscape_xlarge." . $extension;
        }
        // retorna url de imagem nao encontrada
        return 'https://sdumont.lncc.br/images/projects/no-image.png';
    }

    /**
     * @param int $page
     * @param string|null $name
     * @return array
     * @throws Exception
     */
    public function characters(int $page, string $name = null)
    {
        try {
            // Monta o endpoint para a requisição
            $url = $this->domain . $this->routes['characters'] . $this->mountAccessUrl() . $this->mountNameToSearch($name) . $this->mountPageNumber($page) . "&limit=" . $this->limitPerPage;
            // Envia a requisição
            $response = $this->runApi($url);
            // Prepara os dados para a exibição
            return $this->prepareShowCharacters($response, $page);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @param array $response
     * @param int $page
     * @return array
     * @throws Exception
     */
    protected function prepareShowCharacters(array $response, int $page)
    {
        try {
            // recupera os dados recebidos da api
            $content = $response['data']->data ?? null;
            if (empty($content)) {
                throw new Exception('Erro ao recuperar os dados!');
            }
            // Recupera o total de personagens encontrados
            $total = $content->total;
            $characters = [];
            foreach ($content->results as $key => $value) {
                // monta os dados do personagem
                $characters[] = [
                    'id' => $value->id ?? null,
                    'name' => $value->name ?? null,
                    'description' => $value->description ?? null,
                    'modified' => date("D, d M Y", strtotime($value->modified ?? now())),
                    'image' => $this->mountImageUrl($value->thumbnail->path, $value->thumbnail->extension),
                ];
            }
            // cria a paginação dos personagens
            $paginate = $this->createPaginate($characters, $page, $this->limitPerPage, $total);
            return [$characters, $total, $paginate, $this->limitPerPage];
        } catch (Exception $ex) {
            report($ex);
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function character(int $id)
    {
        try {
            // Monta o endpoint para a requisição
            $url = $this->domain . $this->routes['characters'] . "/" . $id . $this->mountAccessUrl();
            // Envia a requisição
            $response = $this->runApi($url);
            // Prepara os dados para a exibição
            return $this->prepareShowCharacter($response);
        } catch (Exception $ex) {
            report($ex);
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * @param array $response
     * @return array
     * @throws Exception
     */
    protected function prepareShowCharacter(array $response)
    {
        try {
            // recupera os dados recebidos da api
            $content = $response['data']->data ?? null;
            if (!$content) {
                throw new Exception('Erro ao recuperar os dados!');
            }
            // recupera os dados do personagem
            $value = $content->results[0] ?? null;
            return [
                'id' => $value->id ?? null,
                'name' => $value->name,
                'description' => $value->description ?? null,
                'modified' => date("D, d M Y", strtotime($value->modified)),
                'image' => $this->mountImageUrl($value->thumbnail->path, $value->thumbnail->extension),
            ];
        } catch (Exception $ex) {
            report($ex);
            throw new Exception($ex->getMessage());
        }
    }
}
