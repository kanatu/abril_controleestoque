<?php
require_once("inc/class.controleestoque.php");

function controleestoque_cliente_create() {

    $nome = $_POST["txtNome"];
    $email = $_POST["txtEmail"];
    $telefone = $_POST["txtTelefone"];

    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "clientes";
        $wpdb->show_errors = true;

        $result = $wpdb->insert(
                $table_name, 
                array('nome' => $nome, 'email'=> $email, 'telefone' => $telefone), 
                array('%s', '%s', '%s')
        );

        if ($result === false)
            $message="Erro ao inserir ao registro.";
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Adicionar Novo Cliente</h2>
        <?php if ($message == "" && isset($_POST['insert'])) { ?>
        <div class="updated"><p>Registro Inserido com sucesso!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_cliente_list') ?>">&laquo; Voltar para a lista de clientes</a>
        <?php } else { ?>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmCliente">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <td class="ss-th-width">Nome</td>
                    <td><input type="text" name="txtNome" id="txtNome" value="<?php echo $nome; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <td class="ss-th-width">Email</td>
                    <td><input type="text" name="txtEmail" id="txtEmail" class="ss-field-width" value="<?php echo $email; ?>"/></td>
                </tr>
                <tr>
                    <td class="ss-th-width">Telefone</td>
                    <td><input type="text" name="txtTelefone" id="txtTelefone" value="<?php echo $preco; ?>" class="ss-field-width" /></td>
                </tr>
            </table>
            <input type="submit" name="insert" value="Salvar" class="button" />
        </form>

        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
        <script type="text/javascript">
            jQuery.validator.setDefaults({
                debug: false,
                success: "valid"
            });
            $( "#frmCliente" ).validate({
                rules: {
                    txtNome: {
                        required: true,
                        maxlength: 255
                    },
                    txtEmail: {
                        required: true,
                        maxlength: 255
                    },
                    txtTelefone: {
                        required: true,
                        maxlength: 11
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
