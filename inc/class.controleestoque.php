<?php
function buscaQuantidadeEstoque($idProduto){
    global $wpdb;
die($idProduto);

    $sql = "SELECT (qtd - saldo) estoque FROM $wpdb->produtos WHERE id = %s";
    $produtos_estoque = $wpdb->get_var($wpdb->prepare($sql, $idProduto));

    return $produtos_estoque;
}

function verificaEstoque($idProduto){
    global $wpdb;

    $sql = "SELECT (qtd - saldo) estoque FROM $wpdb->produtos WHERE id = %s";
    $produtos_estoque = $wpdb->get_var($wpdb->prepare($sql, $idProduto));

    return $produtos_estoque > 0;
}

?>