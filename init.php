<?php
/*
Plugin Name: ControleEstoque
Description:
Version: 1
Author: Danilo Canato
*/

function ss_options_install() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$wpdb->show_errors = true;

	/*********** Produto */
    $table_name = $wpdb->prefix . "produtos";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
				id MEDIUMINT NOT NULL AUTO_INCREMENT,
				nome VARCHAR(50) NOT NULL,
				descricao VARCHAR(4000) NOT NULL,
				preco DECIMAL NOT NULL,
				qtd int NOT NULL,
				saldo int NOT NULL,
				PRIMARY KEY (`id`)
			) $charset_collate; ";

    dbDelta($sql);
    /*********** Produto */

    /*********** Cliente */
    $table_name = $wpdb->prefix . "clientes";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
				id MEDIUMINT NOT NULL AUTO_INCREMENT,
				nome VARCHAR(255) NOT NULL,
				email VARCHAR(255) NOT NULL,
				telefone VARCHAR(11) NOT NULL,
				PRIMARY KEY (`id`)
			) $charset_collate; ";

    dbDelta($sql);
    /*********** Cliente */

    /*********** Pedido */
    $table_name = $wpdb->prefix . "pedidos";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	            id MEDIUMINT NOT NULL AUTO_INCREMENT,
	            id_produto MEDIUMINT NOT NULL,
				id_cliente MEDIUMINT NOT NULL,
				qtd int NOT NULL DEFAULT 0,
				dtCriacao TIMESTAMP NOT NULL DEFAULT NOW(),
				PRIMARY KEY (`id`),
				FOREIGN KEY (id_produto) REFERENCES " .$wpdb->prefix . "produtos(id),
				FOREIGN KEY (id_cliente) REFERENCES " .$wpdb->prefix . "clientes(id)
	        ) $charset_collate; ";

	dbDelta($sql);
			
	/*$sql = "CREATE TRIGGER TGI_" . $table_name . "_atualizaestoque
			BEFORE INSERT ON ".$table_name."
			FOR EACH ROW BEGIN
				UPDATE " .$wpdb->prefix . "produtos SET saldo = saldo + NEW.qtd WHERE id = NEW.id_produto;
			END;";
	dbDelta($sql);

	$sql = "CCREATE TRIGGER TGU_" . $table_name . "_atualizaestoque
			BEFORE UPDATE ON ".$table_name."
			FOR EACH ROW BEGIN
				UPDATE " .$wpdb->prefix . "produtos SET saldo = saldo + NEW.qtd WHERE id = NEW.id_produto;
			END;";

    dbDelta($sql);*/
    /*********** Pedido */
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//AJAX
add_action( 'wp_enqueue_scripts', 'controle_estoque_enqueue_scripts' );
function controle_estoque_enqueue_scripts() {
	wp_localize_script( 'verificaestoque', 'controleestoque', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));

}

add_action( 'wp_ajax_nopriv_postBuscaQuantidadeEstoque', 'postBuscaQuantidadeEstoque' );
add_action( 'wp_ajax_postBuscaQuantidadeEstoque', 'postBuscaQuantidadeEstoque' );

function postBuscaQuantidadeEstoque(){
    /*global $wpdb;

	$idProduto = ISSET($_POST['post_id']) ? $_POST['post_id']: $_GET['post_id'];

    $sql = "SELECT (qtd - saldo) estoque FROM $wpdb->produtos WHERE id = %s";
    $produtos_estoque = $wpdb->get_var($wpdb->prepare($sql, $idProduto));

    echo $produtos_estoque;
    die();*/

    $controle = get_post_meta( $_REQUEST['post_id'], 'controle_estoque', true );
	$controle++;
	update_post_meta( $_REQUEST['post_id'], 'controle_estoque', $controle );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo $controle;
		die();
	}
	else {
		global $wpdb;

		$idProduto = $_REQUEST['post_id'];

	    $sql = "SELECT (qtd - saldo) estoque FROM $wpdb->produtos WHERE id = %s";
	    $produtos_estoque = $wpdb->get_var($wpdb->prepare($sql, $idProduto));

	    echo $produtos_estoque;
		exit();
	}
}

//menu items
add_action('admin_menu','controleestoque_modifymenu');

function controleestoque_modifymenu() {
	
	add_menu_page('Controle Estoque', //page title
	'Controle Estoque', //menu title
	'manage_options', //capabilities
	'controleestoque', //menu slug
	'controleestoque_pedido_list', //function
	plugins_url('controle_estoque/images/icon_estoque.png') //icon
	);
	
	/* Produto */
	add_submenu_page('controleestoque', //parent slug
	'Todos os Produtos', //page title
	'Todos os Produtos', //menu title
	'manage_options', //capability
	'controleestoque_produto_list', //menu slug
	'controleestoque_produto_list'); //function

	add_submenu_page('controleestoque_produto_list', //parent slug
	'Adicionar Produto', //page title
	'Adicionar Produto', //menu title
	'manage_options', //capability
	'controleestoque_produto_create', //menu slug
	'controleestoque_produto_create'); //function
	
	add_submenu_page(null, //parent slug
	'Atualiza Produto', //page title
	'Atualiza Produto', //menu title
	'manage_options', //capability
	'controleestoque_produto_update', //menu slug
	'controleestoque_produto_update'); //function
	/* Produto */

	/* Cliente */
	add_submenu_page('controleestoque', //parent slug
	'Todos os Clientes', //page title
	'Todos os Clientes', //menu title
	'manage_options', //capability
	'controleestoque_cliente_list', //menu slug
	'controleestoque_cliente_list'); //function

	add_submenu_page('controleestoque_clientes_list', //parent slug
	'Adicionar Cliente', //page title
	'Adicionar Cliente', //menu title
	'manage_options', //capability
	'controleestoque_cliente_create', //menu slug
	'controleestoque_cliente_create'); //function
	
	add_submenu_page(null, //parent slug
	'Atualiza Cliente', //page title
	'Atualiza Cliente', //menu title
	'manage_options', //capability
	'controleestoque_cliente_update', //menu slug
	'controleestoque_cliente_update'); //function
	/* Cliente */

	/* Pedido */
	add_submenu_page('controleestoque', //parent slug
	'Todos os Pedidos', //page title
	'Todos os Pedidos', //menu title
	'manage_options', //capability
	'controleestoque_pedido_list', //menu slug
	'controleestoque_pedido_list'); //function

	add_submenu_page('controleestoque_pedido_list', //parent slug
	'Adicionar pedido', //page title
	'Adicionar Pedido', //menu title
	'manage_options', //capability
	'controleestoque_pedido_create', //menu slug
	'controleestoque_pedido_create'); //function
	
	add_submenu_page(null, //parent slug
	'Atualiza pedido', //page title
	'Atualiza Pedido', //menu title
	'manage_options', //capability
	'controleestoque_pedido_update', //menu slug
	'controleestoque_pedido_update'); //function
	/* Pedido */
}
define('ROOTDIR', plugin_dir_path(__FILE__));
//Produto
require_once(ROOTDIR . 'produto-list.php');
require_once(ROOTDIR . 'produto-create.php');
require_once(ROOTDIR . 'produto-update.php');
//Produto

//Cliente
require_once(ROOTDIR . 'cliente-list.php');
require_once(ROOTDIR . 'cliente-create.php');
require_once(ROOTDIR . 'cliente-update.php');
//Cliente

//Pedido
require_once(ROOTDIR . 'pedido-list.php');
require_once(ROOTDIR . 'pedido-create.php');
require_once(ROOTDIR . 'pedido-update.php');
//Pedido

?>