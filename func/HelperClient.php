<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class ClientHelper
{
    const string METHOD_GET = 'GET';

    const string REST = 'https://rest.elited.ru';

    private Client $client;

    private Response $response;

    private int $code;
    private ?string $data = null;
    private ?string $error = null;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function request(
        string $url,
        string $method = 'GET',
        ?array $param = null,
        bool $rewriteUrl = false,
    ): self
    {
        if (!is_null($param)) {
            $param = ($method == self::METHOD_GET ? [
                'query' => $param
            ] : [
                'json' => $param
            ]);
        }

        try {
            $this->setResponse(
                $this->client->request(
                    $method,
                    $rewriteUrl ? $url : self::REST . '/' . $url,
                    $param ?? []
                )
            );
    
            $this->setCode(
                $this->response->getStatusCode()
            );
    
            $this->setData(
                $this->response->getBody()
            );
        } catch(ClientException|ServerException $e) {
            $this->setCode(
                $e->getCode()
            )->setError(
                json_encode([
                    'status'    => $e->getCode(),
                    'reason'    => $e->getMessage()
                ])
            );
        }

        return $this;
    }

    public function toJson()
    {
        return json_decode($this->getError() ?? $this->getData());
    }

    public function toArray()
    {
        return json_decode($this->getError() ?? $this->getData(), true);
    }

    private function getResponse(): Response
    {
        return $this->response;
    }

    private function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    private function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    private function getData(): ?string
    {
        return $this->data;
    }

    private function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }
    private function getError(): ?string
    {
        return $this->error;
    }

    private function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }
}