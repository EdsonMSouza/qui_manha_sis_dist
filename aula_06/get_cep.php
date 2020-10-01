<?php

$obj = json_decode(file_get_contents('https://economia.awesomeapi.com.br/all/USD-BRL'), True);

$data = array();

foreach ($obj as $values) {
    foreach ($values as $key => $value) {
        $data[$key] = $value;
    }
}
print('<pre>');
print_r($data);
print('</pre>');
print($data['high']);
print($data['low']);


# faz uma requisição de um arquivo local ou remoto
$obj = json_decode(file_get_contents('https://api.postmon.com.br/v1/cep/03590070'), True);
print('<pre>');
print_r($obj);
print('</pre>');

$logradouro = $obj['logradouro'];
$cidade = $obj['cidade'];
$estado = $obj['estado'];
print($logradouro . '<br>');
print($cidade . '<br>');
print($estado . '<br>');