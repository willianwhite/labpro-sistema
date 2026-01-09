<?php
// Teste simples de formulário
header('Content-Type: text/plain');

echo "=== TESTE DE FORMULÁRIO ===\n";
echo "Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "Data: " . date('Y-m-d H:i:s') . "\n";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Dados POST recebidos:\n";
    print_r($_POST);
} else {
    echo "Aguardando POST...\n";
}

echo "\nPHP está funcionando!\n";
?>
