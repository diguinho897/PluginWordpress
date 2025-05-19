<?php
// Função para buscar livros na API Google Books
function catalogo_livros_buscar_google_books($termo) {
    $url = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($termo);
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return [];
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (empty($data['items'])) {
        return [];
    }
    return $data['items'];
} 