<?php
require_once("inc/class.controleestoque.php");

function controleestoque_cliente_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Clientes</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=controleestoque_cliente_create'); ?>">Adicionar</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "clientes";

        $rows = $wpdb->get_results("SELECT id, nome, email, telefone from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Nome</th>
                <th class="manage-column ss-list-width">Email</th>
                <th class="manage-column ss-list-width">Telefone</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->nome; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->email; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->telefone; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=controleestoque_cliente_update&id=' . $row->id); ?>">Atualizar</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
} ?>