<?php
require_once("inc/class.controleestoque.php");

function controleestoque_cliente_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "clientes";

    $id = $_GET["id"];
    $nome = $_POST["txtNome"];
    $email = $_POST["txtEmail"];
    $telefone = $_POST["txtTelefone"];

//update
    if (isset($_POST['update'])) {
        $result = $wpdb->update(
                $table_name, //table
                array('nome' => $nome, 'email'=> $email, 'telefone' => $telefone), //data
                array('ID' => $id), //where
                array('%s', '%s', '%s'), //data format
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
        $produtos = $wpdb->get_results($wpdb->prepare("SELECT id, nome, email, telefone from $table_name where id=%s", $id));
        foreach ($produtos as $s) {
            $nome = $s->nome;;
            $email = $s->email;
            $telefone = $s->telefone;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/controle_estoque/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Editar Cliente</h2>

        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Cliente apagado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_cliente_list') ?>">&laquo; Voltar para a lista de clientes</a>

        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>Cliente Atualizado!</p></div>
            <a href="<?php echo admin_url('admin.php?page=controleestoque_cliente_list') ?>">&laquo; Voltar para a lista de clientes</a>

        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="frmCliente">
                <?php if (isset($message)): ?><div class="updated"><p><?php echo $message ?></p></div><?php endif; ?>
                <table class='wp-list-table widefat fixed'>
                    <!--tr><th>Name</th><td><input type="text" name="name" value="<?php //echo $name; ?>"/></td></tr-->

                    <tr>
                        <td class="ss-th-width">Nome</td>
                        <td><input type="text" name="txtNome" id="txtNome" value="<?php echo $nome; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Email</td>
                        <td><input type="text" name="txtEmail" id="txtEmail" class="ss-field-width" value="<?php echo $email; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="ss-th-width">Telefone</td>
                        <td><input type="text" name="txtTelefone" id="txtTelefone" value="<?php echo $telefone; ?>" class="ss-field-width" /></td>
                    </tr>
                </table>
                <input type="submit" name="update" value="Save" class="button" /> &nbsp;&nbsp;
                <input type="submit" name="delete" value="Delete" class="button" onclick="return confirm('Deseja apagar esse registro?')" />
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
                        nome: {
                            required: true,
                            maxlength: 255
                        },
                        email: {
                            required: true,
                            maxlength: 255
                        },
                        telefone: {
                            required: true,
                            maxlength: 11
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