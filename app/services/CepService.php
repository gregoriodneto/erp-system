<?php

namespace App\Services;

use Exception;

class CepService
{
    protected string $viaCepUrl;

    public function __construct()
    {
        $this->viaCepUrl = $_ENV['VIA_CEP'];
    }

    public function searchAddress(string $cep)
    {        
        $cep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cep) !== 8)
        {
            throw new Exception("Cep Inválido.");
        }

        $url = "{$this->viaCepUrl}/ws/{$cep}/json/";
        $response = file_get_contents($url);
        if (!$response)
        {
            throw new Exception("Erro ao consultar serviço do ViaCep.");
        }

        $data = json_decode($response, true);
        if (isset($data["erro"]) && $data["erro"] === true)
        {
            throw new Exception("CEP inválido ou não encontrado.");
        }

        return $data;
    }
}