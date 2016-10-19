<?php
require_once("inc/class.controleestoque.php");

function controleestoque_produto_update() {
    global $wpdb;
    $wpdb->show_errors = true;

    $table_name = $wpdb->prefix . "produtos";

    $id = $_GET["id"];
    $nome = $_POST["txtNome"];
    $descricao = $_POST["txtDescricao"];
    $preco = $_POST["txtPreco"];
    $qtd = $_POST["txQtd"];
    $saldo = $_POST["txtSaldo"];

//update
    if (isset($_POST['update'])) {
        
        $result = $wpdb->update(
                $table_name, //table
                array('nome' => $nome, 'descricao'=> $descricao, 'preco' => $preco, 'qtd' => $qtd, 'saldo'=> $saldo), //data
                array('ID' => $id), //where
                array('%s', '%s', '%f', '%d', '%d'), //data format
                array('%s') //where format
        );

        if ($result === false)
            $message ="Erro na atualização do registro.";
    }
//delete
    else if (isset($_POST['delete'])) {
        $result = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));

        if ($result === false)
            $message ="Erro ao remover o registro.";

    } else {
        $produtos = $wpdb->get_results($wpdb->prepare("SELECT id, nome, descricao, preco, qtd, saldo from $table_name where id=%s", $id));
        foreach ($produtos as $s) {
            $nome = $s->nome;;
            $descricao = $s->descricao;
            $preco = $s->preco;
            $qtd = $s->qtd;
            $saldo = $s->saldo;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Editar Produto</h2>

        <?php if ($_POST['delete'] && $message == "") { ?>
            <div class="updated"><p>Produto apagado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_produto_list') ?>">&laquo; Voltar para a lista de produtos</a>

        <?php } else if ($_POST['update'] && $message == "") { ?>
            <div class="updated"><p>Produto Atualizado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_produto_list') ?>">&laquo; Voltar para a lista de produtos</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmProduto">
                <?php if (isset($message)): ?><div class="updated"><p><?php echo $message ?></p></div><?php endif; ?>

                <table class='wp-list-table widefat fixed'>
                    <!--tr><th>Name</th><td><input type="text" name="name" value="<?php //echo $name; ?>"/></td></tr-->

                    <tr>
                        <td class="ss-th-width">Nome</td>
                        <td><input type="text" name="txtNome" id="txtNome" value="<?php echo $nome; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Descri&ccedil;&atilde;o</td>
                        <td><textarea type="text" name="txtDescricao" id="txtDescricao" class="ss-field-width" ><?php echo $descricao; ?></textarea></td>
                    </tr>
                    <!-- TODO: colocar validação somente para numero -->
                    <tr>
                        <td class="ss-th-width">Pre&ccedil;o</td>
                        <td><input type="text" name="txtPreco" id="txtPreco" value="<?php echo $preco; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Quantidade</td>
                        <td><input type="text" name="txQtd" id="txtQtd" value="<?php echo $qtd; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Saldo</td>
                        <td><input type="text" name="txtSaldo" id="txtSaldo" value="<?php echo $saldo; ?>" class="ss-field-width" /></td>
                    </tr>
                    <!-- TODO: colocar validação somente para numero -->
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Deseja apagar esse registro?')">
            </form>

            <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
            <script type="text/javascript">
                jQuery.validator.setDefaults({
                  debug: false,
                  success: "valid"
                });

                $( "#frmProduto" ).validate({
                    rules: {
                        txtNome: {
                            required: true,
                            maxlength: 50
                        },
                        txtDescricao: {
                            required: true,
                            maxlength: 4000
                        },
                        txtPreco: {
                            required: true,
                            number: true,
                            min: 1
                        },
                        txtQtd: {
                            required: true,
                            number: true,
                            min: 1
                        },
                        txtSaldo: {
                            required: true,
                            number: true
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

