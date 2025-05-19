<?php
/*
Plugin Name: Catálogo de Livros
Description: Busca livros na API Google Books e importa como tipo de conteúdo personalizado com campos ACF.
Version: 1.0
Author: Seu Nome
Text Domain: catalogo-livros
Domain Path: /languages
*/

// Impede acesso direto
if (!defined('ABSPATH')) exit;

// Inclui arquivos necessários
define('CATALOGO_LIVROS_PATH', plugin_dir_path(__FILE__));

require_once CATALOGO_LIVROS_PATH . 'includes/cpt-livros.php';
require_once CATALOGO_LIVROS_PATH . 'includes/google-books-api.php';
require_once CATALOGO_LIVROS_PATH . 'includes/admin-ui.php';

// Shortcode para exibir livros
require_once CATALOGO_LIVROS_PATH . 'includes/shortcode.php';

// Carrega o CSS do plugin no front-end
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('catalogo-livros-style', plugins_url('assets/style.css', __FILE__));
}); 