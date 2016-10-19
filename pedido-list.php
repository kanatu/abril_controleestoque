<?php
require_once("inc/class.controleestoque.php");

function controleestoque_pedido_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Pedidos</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_create'); ?>">Novo Pedido</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $sql = "SELECT ped.id, cli.nome nomeCliente, prod.nome nomeProduto, ped.dtCriacao".
               " FROM " . $wpdb->prefix . "pedidos ped ".
               " INNER JOIN " . $wpdb->prefix . "clientes cli ON ped.id_cliente = cli.id " . 
               " INNER JOIN " . $wpdb->prefix . "produtos prod ON ped.id_produto = prod.id "; 
        // 

        $rows = $wpdb->get_results($sql);
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">Numero pedido</th>
                <th class="manage-column ss-list-width">Cliente</th>
                <th class="manage-column ss-list-width">Produto</th>
                <th class="manage-column ss-list-width">Data Pedido</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->nomeCliente; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->nomeProduto; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->dtCriacao; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_update&id=' . $row->id); ?>">Atualizar</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}?>