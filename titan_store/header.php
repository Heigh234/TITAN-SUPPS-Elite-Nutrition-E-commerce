<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TITAN SUPPS | Elite Nutrition</title>
    <link rel="icon" type="image/webp" href="favicon.webp">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Personalización Neon */
        .neon-text { text-shadow: 0 0 10px #a3e635; }
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-950 text-slate-200 font-sans antialiased">
    <nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-3xl font-black text-white tracking-tighter">TITAN<span class="text-lime-400 neon-text">SUPPS</span></a>
            <div class="space-x-6 font-semibold">
                <a href="index.php" class="hover:text-lime-400 transition">Tienda</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="cart.php" class="hover:text-lime-400 transition">Carrito (<?= count($_SESSION['cart'] ?? []) ?>)</a>
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="my_orders.php" class="hover:text-lime-400 transition">Mis Pedidos</a> <?php endif; ?>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="text-orange-500 hover:text-orange-400">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" class="text-red-500 hover:text-red-400">Salir</a>
                <?php else: ?>
                    <a href="login.php" class="text-lime-400 border border-lime-400 px-4 py-2 rounded hover:bg-lime-400 hover:text-black transition">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="min-h-screen pb-10">
<?php if (isset($_SESSION['swal'])): ?>
    <script>
        Swal.fire({
            title: '<?= $_SESSION['swal']['title'] ?>',
            text: '<?= $_SESSION['swal']['text'] ?>',
            icon: '<?= $_SESSION['swal']['type'] ?>',
            background: '#1e293b', // Color Slate-800
            color: '#fff',
            confirmButtonColor: '#a3e635', // Tu Verde Neón
            confirmButtonText: 'OK',
            customClass: {
                popup: 'border border-slate-700 shadow-xl'
            }
        });
    </script>
    <?php unset($_SESSION['swal']); // Borrar mensaje después de mostrarlo ?>
<?php endif; ?>