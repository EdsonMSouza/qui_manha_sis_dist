<?php
include "Bot.php";
$bot = new Bot;

include "Cep.php";
include "Cotacao.php";

$questions = [
    "php" => "É uma linguagem de programação server side.",
    "linux" => "É um sistema operacional criado por Linus Torvald.",
    "qual o seu nome" => "Meu nome é " . $bot->getName()
];

# recebe uma mensagem enviada pelo usuário
if (isset($_GET['msg'])) {
    $msg = strtolower($_GET['msg']);
    $bot->hears($msg, function (Bot $botty) {
        global $msg;
        global $questions;

        # tratamento da solicitação de um CEP
        # cep 01512000
        if (preg_match('/cep ?(\d{5}.{0,1}\d{3})/', $msg) == 1) {
            preg_match('/\d{5}.{0,1}\d{3}/', $msg, $matches);

            $cep = new Cep($matches[0]);
            $data = $cep->getAddress();

            $tmp  = $data['address'] . '<br>';
            $tmp .= $data['district'] . '<br>';
            $tmp .= $data['city'] . '/' . $data['state'] . '<br>';

            $distancia = $cep->getDistance(-23.5448068, -46.4827709, $data['lat'], $data['lng']);

            if ($distancia > 20) { // em km
                $tmp  = "Infelizmente não entregamos neste endereço. ";
                $tmp .= "Nosso raio de entrega é de até 20 Km e você está a " . $distancia . ' km.';
            } else {
                $tmp .= "Que legal, você está a " . $distancia . ' km';
                $tmp .= "Sua entrega chegará em aproximadamente ";
                $tmp .= round((($distancia * 1.61) / 50) * 60 + 15) . ' minutos.';
            }

            $botty->reply($tmp);
            die();
        }

        # cotação de moedas
        $moedas = [
            'dolar' => 'USD-BRL',
            'euro' => 'EUR-BRL',
            'bitcoin' => 'BTC-BRL'
        ];

        if (preg_match('/cotação/', $msg) == 1) {
            preg_match('/[^cotação].*/', $msg, $matches);
            $cotacao = new Cotacao($botty->ask($matches[0], $moedas));
            $data = $cotacao->getValues();
            $tmp  = 'Data: ' . $data[0] . '<br>';
            $tmp .= 'Máx: ' . $data[1] . '<br>';
            $tmp .= 'Min: ' . $data[2] . '<br>';
            $botty->reply($tmp);
            die();
        }

        $generics = ['oi', 'oie', 'ola', 'olá', 'bom dia', 'boa tarde', 'boa noite'];
        if (in_array($msg, $generics)) {
            $botty->reply('Olá. Em que posso ajudar?');
        } elseif ($botty->ask($msg, $questions) == "") {
            $botty->reply("Desculpe, não entendi.");
        } else {
            $botty->reply($botty->ask($msg, $questions));
        }
    });
}
