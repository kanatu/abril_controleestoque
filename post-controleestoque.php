<?php
add_action( 'admin_post_BuscaQuanEst', 'postBuscaQuantidadeEstoque' );

function postBuscaQuantidadeEstoque(){
    global $wpdb;

	$idProduto = ISSET($_POST['post_id']) ? $_POST['post_id']: $_GET['post_id'];
echo ">>>>".$idProduto;
die;	
    $sql = "SELECT (qtd - saldo) estoque FROM $wpdb->produtos WHERE id = %s";
    $produtos_estoque = $wpdb->get_var($wpdb->prepare($sql, $idProduto));

    echo $produtos_estoque;
    die();
}
?>