<?php
require 'config.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_POST['add'])) {
    $id = $_POST['product_id'];
    
    // Lógica del carrito
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    // ¡AQUÍ ESTÁ LA MAGIA! Guardamos el mensaje para SweetAlert
    $_SESSION['swal'] = [
        'title' => '¡Buen trabajo!',
        'text' => 'Suplemento añadido al carrito.',
        'type' => 'success'
    ];
} 

elseif (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    
    $_SESSION['swal'] = [
        'title' => 'Eliminado',
        'text' => 'Producto fuera del carrito.',
        'type' => 'info'
    ];
} 

elseif (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
}

// Redireccionar
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;