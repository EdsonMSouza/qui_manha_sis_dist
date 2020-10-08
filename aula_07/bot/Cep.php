<?php

class Cep
{
    private $data = array();

    public function __construct($cep)
    {
        # recuperar os dados do endereço via API
        $url = "https://cep.awesomeapi.com.br/{$cep}";
        $obj = json_decode(file_get_contents($url), True);

        $this->data = $obj;
    }

    # retorna os dados recuperados
    public function getAddress()
    {
        return $this->data;
    }

    # calcula a distância entre pontos em "linha reta" 
    # com base na latitude e longitude
    public function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        # Fórmula de Haversine 
        #(https://www.ehow.com.br/calcular-distancia-entre-pontos-latitude-longitude-como_71372/)
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $dist = (6371 * acos(cos($lat1) * cos($lat2) * cos($lon2 - $lon1) + sin($lat1) * sin($lat2)));
        $dist = number_format($dist, 2, '.', '');
        return $dist;
    }
}
