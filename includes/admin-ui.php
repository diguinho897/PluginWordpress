<?php
// Adiciona página ao menu do admin
add_action('admin_menu', function() {
    add_menu_page(
        'Importar Livros',
        'Importar Livros',
        'manage_options',
        'catalogo-livros',
        'catalogo_livros_admin_page',
        'dashicons-book',
        20
    );
});

// Renderiza a página de busca e importação
function catalogo_livros_admin_page() {
    ?>
    <div class="wrap">
        <h1>Importar Livros do Google Books</h1>
        
        <?php
        // Processa a importação primeiro
        if (isset($_POST['catalogo_livros_importar_btn']) && isset($_POST['catalogo_livros_importar'])) {
            $id = sanitize_text_field($_POST['catalogo_livros_importar']);
            $titulo = sanitize_text_field($_POST['livro_' . $id . '_titulo']);
            $autor = sanitize_text_field($_POST['livro_' . $id . '_autor']);
            $descricao = sanitize_textarea_field($_POST['livro_' . $id . '_descricao']);
            $capa = esc_url_raw($_POST['livro_' . $id . '_capa']);
            
            $post_id = wp_insert_post([
                'post_type' => 'livro',
                'post_title' => $titulo,
                'post_content' => $descricao,
                'post_status' => 'publish',
            ]);
            
            if ($post_id) {
                update_field('autor', $autor, $post_id);
                update_field('capa', $capa, $post_id);
                echo '<div class="updated notice"><p>Livro importado com sucesso!</p></div>';
            } else {
                echo '<div class="error notice"><p>Ocorreu um erro ao importar o livro. Por favor, tente novamente.</p></div>';
            }
        }
        ?>

        <!-- Formulário de busca -->
        <form method="post" action="">
            <input type="text" name="catalogo_livros_termo" placeholder="Digite o nome do livro" required style="width:300px;" 
                   value="<?php echo isset($_POST['catalogo_livros_termo']) ? esc_attr($_POST['catalogo_livros_termo']) : ''; ?>" />
            <button type="submit" class="button button-primary">Buscar</button>
        </form>
        <br />

        <?php
        // Exibe resultados da busca
        if (!empty($_POST['catalogo_livros_termo'])) {
            $termo = sanitize_text_field($_POST['catalogo_livros_termo']);
            $livros = catalogo_livros_buscar_google_books($termo);
            
            if ($livros) {
                ?>
                <form method="post" action="">
                    <input type="hidden" name="catalogo_livros_termo" value="<?php echo esc_attr($termo); ?>" />
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Capa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($livros as $livro) {
                                $id = esc_attr($livro['id']);
                                $info = $livro['volumeInfo'];
                                $titulo = esc_html($info['title'] ?? '');
                                $autor = esc_html(implode(', ', $info['authors'] ?? []));
                                $descricao = esc_html($info['description'] ?? '');
                                $capa = $info['imageLinks']['thumbnail'] ?? '';
                                ?>
                                <tr>
                                    <td>
                                        <input type="radio" name="catalogo_livros_importar" value="<?php echo $id; ?>" required>
                                    </td>
                                    <td><?php echo $titulo; ?></td>
                                    <td><?php echo $autor; ?></td>
                                    <td>
                                        <?php if ($capa): ?>
                                            <img src="<?php echo esc_url($capa); ?>" style="height:80px;">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <input type="hidden" name="livro_<?php echo $id; ?>_titulo" value="<?php echo esc_attr($titulo); ?>">
                                <input type="hidden" name="livro_<?php echo $id; ?>_autor" value="<?php echo esc_attr($autor); ?>">
                                <input type="hidden" name="livro_<?php echo $id; ?>_descricao" value="<?php echo esc_attr($descricao); ?>">
                                <input type="hidden" name="livro_<?php echo $id; ?>_capa" value="<?php echo esc_url($capa); ?>">
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit" name="catalogo_livros_importar_btn" class="button button-primary">Importar Livro Selecionado</button>
                </form>
                <?php
            } else {
                echo '<p>Nenhum livro encontrado na busca.</p>';
            }
        }
        ?>
    </div>
    <?php
} 