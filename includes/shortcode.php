<?php
// Shortcode para exibir livros
add_shortcode('catalogo_livros', function($atts) {
    $args = array(
        'post_type' => 'livro',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $html = '<div class="catalogo-livros-lista">';
        while ($query->have_posts()) {
            $query->the_post();
            $titulo = get_the_title();
            $descricao = get_the_content();
            $autor = get_field('autor');
            $capa = get_field('capa');
            $html .= '<div class="catalogo-livro-item" style="margin-bottom:30px;">';
            if ($capa) {
                $html .= '<img src="' . esc_url($capa) . '" style="height:120px;float:left;margin-right:20px;">';
            }
            $html .= '<h3>' . esc_html($titulo) . '</h3>';
            $html .= '<p><strong>Autor:</strong> ' . esc_html($autor) . '</p>';
            $html .= '<div>' . wpautop($descricao) . '</div>';
            $html .= '<div style="clear:both;"></div>';
            $html .= '</div>';
        }
        wp_reset_postdata();
        $html .= '</div>';
    } else {
        $html = '<p>Nenhum livro encontrado no catálogo.</p>';
    }
    return $html;
}); 