<?php
require_once("inc/class.controleestoque.php");

function controleestoque_pedido_create() {

    $idCliente = $_POST['cboCliente'];
    $idProduto = $_POST['cboProduto'];
    $qtd = $_POST['cboQtd'];

    global $wpdb;
    $sql = "SELECT cli.id, cli.nome nomeCliente".
           " FROM ". $wpdb->prefix . "clientes cli"; 

    $rowsCliente = $wpdb->get_results($sql);

    $sql = "SELECT prod.id, prod.nome nomeProduto".
           " FROM ". $wpdb->prefix . "produtos prod" .
           " WHERE 1 = 1 ".
           "   AND (prod.qtd - prod.saldo) > 0"; 

    $rowsProdutos = $wpdb->get_results($sql);

    //insert
    if (isset($_POST['insert'])) {
        $table_name = $wpdb->prefix . "pedidos";
        $wpdb->show_errors = true;

        $result = $wpdb->insert(
                $table_name, 
                array('id_cliente' => $idCliente, 'id_produto'=> $idProduto), 
                array('%d', '%d')
        );

        if ($result === false)
            $message="Erro ao inserir ao registro.";

    }

    if (!isset($_POST['quantidadeproduto'])) {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Adicionar Novo Pedido</h2>
        <?php if ($message == "" && isset($_POST['insert'])) { ?>
        <div class="updated"><p>Registro Inserido com sucesso!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_list') ?>">&laquo; Voltar para a lista de pedidos</a>
        <?php } else { ?>

        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmPedido">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <td class="ss-th-width">Cliente</td>
                    <td>
                        <select id="cboCliente" name="cboCliente" class="ss-field-width" >
                            <option>Selecione Cliente</option>
                        <?php foreach ($rowsCliente as $row) { ?>
                            <option value="<?php echo $row->id; ?>" <?php echo $idCliente == $row->id ? "selected": ""; ?>><?php echo $row->nomeCliente; ?></option>
                        <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="ss-th-width">Produto</td>
                    <td>
                        <select id="cboProduto" name="cboProduto" class="ss-field-width" >
                            <option>Selecione Produto</option>
                        <?php foreach ($rowsProdutos as $row) { ?>
                            <option value="<?php echo $row->id; ?>" <?php echo $idProduto == $row->id ? "selected": ""; ?>><?php echo $row->nomeProduto; ?></option>
                        <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="ss-th-width">Quantidade</td>
                    <td>
                        <select id="cboQtdade" name="cboQtdade" class="ss-field-width" >
                            <option>Selecione um produto</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" name="insert" value="Salvar" class="button" />
        </form>

        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
        <script type="text/javascript">
            $( "#cboProduto" ).change(function(){
                $.ajax({
                    method: "POST",
                    url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                    data: { action: "postBuscaQuantidadeEstoque", post_id: $("#cboProduto").find('option:selected').val() },
                    beforeSend: function( xhr ) {
                        $( "#cboProduto" ).prop("disabled", true);
                    }
                })
                .fail(function() {
                    alert( "Erro ao buscar a Quantidade do produto" );
                })
                .done(function( retorno ) {
                    $( "#cboQtdade" ).empty();

                    for (var i = 0; i <= retorno; i++) {
                        $('#cboQtdade').append( new Option(i,i) );  
                    };

                    $( "#cboProduto" ).prop("disabled", false);
                });
            });

            jQuery.validator.setDefaults({
                debug: false,
                success: "valid"
            });

            $( "#frmPedido" ).validate({
                rules: {
                    cboCliente: {
                        required: true
                    },
                    cboProduto: {
                        required: true
                    },
                    cboQtdade: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        </script>
    </div>
    <?php 
        }
    }
} ?>

