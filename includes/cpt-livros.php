<?php
// Registro do Custom Post Type 'livro'
add_action('init', function() {
    $labels = array(
        'name' => 'Livros',
        'singular_name' => 'Livro',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Livro',
        'edit_item' => 'Editar Livro',
        'new_item' => 'Novo Livro',
        'view_item' => 'Ver Livro',
        'search_items' => 'Buscar Livros',
        'not_found' => 'Nenhum livro encontrado',
        'not_found_in_trash' => 'Nenhum livro na lixeira',
        'menu_name' => 'Livros',
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-book',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );
    register_post_type('livro', $args);
});

// Registra campos personalizados ACF via PHP
add_action('acf/init', function() {
    if( function_exists('acf_add_local_field_group') ) {
        acf_add_local_field_group(array(
            'key' => 'group_livro',
            'title' => 'Informações do Livro',
            'fields' => array(
                array(
                    'key' => 'field_autor',
                    'label' => 'Autor',
                    'name' => 'autor',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_capa',
                    'label' => 'Capa (URL da Imagem)',
                    'name' => 'capa',
                    'type' => 'url',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'livro',
                    ),
                ),
            ),
        ));
    }
}); 