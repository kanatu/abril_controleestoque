<?php
require_once("inc/class.controleestoque.php");

function controleestoque_pedido_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "pedidos";
    $erro = false;

    $id = $_GET["id"];
    $idProduto = $_POST["cboIdProduto"];

//update
    if (isset($_POST['update'])) {
        if(verificaEstoque($idProduto)){
            $wpdb->update(
                $table_name, //table
                array('idProduto'=> $idProduto), //data
                array('id' => $id), //where
                array('%d'), //data format
                array('%s') //where format
            );
        } else {
            $erroEstoque = true;
        }
    }
//delete
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else if (isset($_POST['quantidadeproduto'])) {
        echo buscaQuantidadeEstoque($idProduto);
    } else {
        //Produtos
        $sql = "SELECT prod.id, prod.nome + '(' + prod.qtd - prod.saldo + ')' nomeProduto".
               " FROM ". $wpdb->prefix . "produtos prod" .
               " WHERE 1 = 1 "; 

        $rowsProdutos = $wpdb->get_results($sql);
        //Produtos

        $sql = "SELECT ped.id_cliente, cli.nome nomeCliente, ped.id_produto, ped.dtCriacao " . 
               " FROM $table_name ped " . 
               " INNER JOIN " . $wpdb->prefix . "clientes cli ON ped.id_cliente = cli.id " . 
               " WHERE id = %s";

        $produtos = $wpdb->get_results($wpdb->prepare($sql, $id));
        foreach ($produtos as $s) {
            $idCliente = $s->id_cliente;
            $nomeCliente = $s->nomeCliente;
            $idProduto = $s->id_produto;
            $dtCriacao = $s->dtCriacao;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Editar Pedido - <?php echo $id; ?></h2>

        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Pedido apagado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_list') ?>">&laquo; Voltar para a lista de pedidos</a>

        <?php } else if ($_POST['update']) { 
                if (!$erroEstoque) { ?>
            <div class="updated"><p>Pedido Atualizado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_list') ?>">&laquo; Voltar para a lista de pedidos</a>

        <?php   } else { ?>
            <div class="updated"><p>Produto sem estoque, favor selecionar outro!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_pedido_update&id=' . $id) ?>">&laquo; Voltar para ao pedido</a>
        <?php
                }
              } else if (!$_POST['quantidadeproduto']){ ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmPedido">
                <?php if (isset($message)): ?><div class="updated"><p><?php echo $message ?></p></div><?php endif; ?>
                <table class='wp-list-table widefat fixed'>
                    <!--tr><th>Name</th><td><input type="text" name="name" value="<?php //echo $name; ?>"/></td></tr-->

                    <tr>
                        <td class="ss-th-width">N&uacute;mero Pedido</td>
                        <td>
                            <?php echo $id; ?>
                            <input type="hidden" value="<?php echo $id; ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td class="ss-th-width">Data Pedido</td>
                        <td>
                            <?php echo $dtCriacao; ?>
                            <input type="hidden" value="<?php echo $dtCriacao; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Cliente</td>
                        <td>
                            <?php echo $row->nomeCliente ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Produto</td>
                        <td>
                            <select id="cboProduto" name="cboProduto" class="ss-field-width" >
                            <?php foreach ($rows as $row) { ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->nomeProduto; ?></option>
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
                <input type="submit" name="update" value="Save" class="button" /> &nbsp;&nbsp;
                <input type="submit" name="delete" value="Delete" class="button" onclick="return confirm('Deseja apagar esse registro?')" />
            </form>

            <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
            <script type="text/javascript">
                $( "#cboProduto" ).change(function(){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo $_SERVER['REQUEST_URI']; ?>",
                        data: { quantidadeproduto: "1", cboProduto: $("#cboProduto").find('option:selected').val() },
                        beforeSend: function( xhr ) {
                            $( "#cboProduto" ).prop("disabled", true);
                        }
                    })
                    .fail(function() {
                        alert( "Erro ao buscar a Quantidade do produto" );
                    })
                    .done(function( retorno ) {
                        $( "#cboProduto" ).empty();

                        for (var i = 0; i <= retorno; i++) {
                            $('#cboProduto').append( new Option(i,i) );  
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
        <?php } ?>

    </div>
    <?php
} ?>