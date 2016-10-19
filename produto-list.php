<?php
require_once("inc/class.controleestoque.php");

function controleestoque_produto_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Produtos</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=controleestoque_produto_create'); ?>">Adicionar</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "produtos";

        $rows = $wpdb->get_results("SELECT id, nome, preco, qtd, saldo from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Nome</th>
                <th class="manage-column ss-list-width">Pre&ccedil;o</th>
                <th class="manage-column ss-list-width">Quantidade</th>
                <th class="manage-column ss-list-width">Saldo</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->nome; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->preco; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->qtd; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->saldo; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=controleestoque_produto_update&id=' . $row->id); ?>">Atualizar</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
} ?>