<?php
require_once("inc/class.controleestoque.php");

function controleestoque_produto_create() {
    $nome = $_POST["txtNome"];
    $descricao = $_POST["txtDescricao"];
    $preco = $_POST["txtPreco"];
    $qtd = $_POST["txtQtd"];
    $saldo = $_POST["txtSaldo"];

    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "produtos";
        $wpdb->show_errors = true;
        
        $result = $wpdb->insert(
                $table_name, 
                array('nome' => $nome, 'descricao'=> $descricao, 'preco' => $preco, 'qtd' => $qtd, 'saldo'=> $saldo), 
                array('%s', '%s', '%f', '%d', '%d')
        );

        if ($result === false)
            $message="Erro ao inserir ao registro.";
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Adicionar Novo Produto</h2>
        <?php if ($message == "" && isset($_POST['insert'])) { ?>
        <div class="updated"><p>Registro Inserido com sucesso!</p></div>
        <a href="<?php echo admin_url('admin.php?page=controleestoque_produto_list') ?>">&laquo; Voltar para a lista de produtos</a>
        <?php } else { ?>

        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmProduto"> 
            <table class='wp-list-table widefat fixed'>
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
                    <td><input type="text" name="txtQtd" id="txtQtd" value="<?php echo $qtd; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <td class="ss-th-width">Saldo</td>
                    <td><input type="text" name="txtSaldo" id="txtSaldo" value="<?php echo $saldo; ?>" class="ss-field-width" /></td>
                </tr>
                <!-- TODO: colocar validação somente para numero -->
            </table>
            <input type='submit' name="insert" value='Salvar' class='button'>
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
    </div>
    <?php
    }
} ?>