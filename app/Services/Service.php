<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request as RequestFacade;

/**
 * Class service
 * @package App\Services
 */
class Service
{
    /**
     * @param string $url
     * @param string $method
     * @return array
     */
    protected static function runApi(string $url, string $method = 'GET')
    {
        try {
            $header = [
                'Content-Type' => ' application/json',
            ];
            $body = [
                'connect_timeout' => 5,
                'timeout' => 10,
                'http_errors' => false,
            ];
            $request = new Request($method, $url, $header);
            $client = new Client();
            // Executa a requisição
            /** @var Response $response */
            $response = $client->send($request, $body);
            // Recupera os dados do retorno da requisição
            $content['data'] = json_decode($response->getBody()->getContents());
            // Recupera a mensagem do retorno da requisição
            $content['message'] = $response->getReasonPhrase();
            // Recupera a código http do retorno da requisição
            $content['http_code'] = $response->getStatusCode();
            // Retorna o conteúdo da requisição
            return $content;
        } catch (GuzzleException | Exception $ex) {
            report($ex);

            return [
                'http_code' => 500,
                'message' => 'internal server error'
            ];
        }
    }

    /**
     * @param array $chars
     * @param int $page
     * @param int $limitPerPage
     * @param int $total
     * @return LengthAwarePaginator|null
     */
    protected static function createPaginate(array $chars, int $page, int $limitPerPage, int $total)
    {
        try {
            return new LengthAwarePaginator(
                collect($chars),
                $total,
                $limitPerPage,
                $page,
                [
                    'path' => RequestFacade::url(),
                    'query' => RequestFacade::query(),
                    'onEachSide' => 1,
                ]
            );
        } catch
        (Exception $ex) {
            report($ex);
            return null;
        }
    }
}
