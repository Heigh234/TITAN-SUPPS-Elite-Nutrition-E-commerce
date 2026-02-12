<?php
require 'config.php';
require 'header.php';

// 1. SEGURIDAD: Solo admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("<div class='min-h-screen flex items-center justify-center bg-slate-950'><h1 class='text-4xl font-black text-red-500 border border-red-500 p-10 rounded-xl'>⛔ ACCESO DENEGADO</h1></div>");
}

// 2. LÓGICA: Subir Producto
if (isset($_POST['save_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Manejo de Imagen
    $imgName = 'default.jpg';
    if (!empty($_FILES['image']['name'])) {
        $imgName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imgName);
    }

    $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $desc, $price, $stock, $imgName]);
    
    // Alerta SweetAlert
    $_SESSION['swal'] = [
        'title' => 'Producto Creado',
        'text' => 'El catálogo ha sido actualizado.',
        'type' => 'success'
    ];
    header("Location: admin.php");
    exit;
}

// 3. LÓGICA: Borrar Producto
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    
    $_SESSION['swal'] = [
        'title' => 'Eliminado',
        'text' => 'Producto borrado permanentemente.',
        'type' => 'warning'
    ];
    header("Location: admin.php");
    exit;
}

// 4. LÓGICA: Calcular Métricas (EL CEREBRO DEL NEGOCIO)
// Ingresos Totales
$stmt = $pdo->query("SELECT SUM(total) as total FROM orders");
$income = $stmt->fetch()['total'] ?? 0;

// Total Pedidos
$stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
$orders_count = $stmt->fetch()['count'];

// Alerta de Stock Bajo (< 10 unidades)
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock < 10");
$low_stock = $stmt->fetch()['count'];

// Obtener productos para la lista
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex justify-between items-end mb-8">
        <h1 class="text-4xl font-black text-white uppercase tracking-wider">Dashboard <span class="text-orange-500">Admin</span></h1>
        <span class="text-slate-500 text-sm">Bienvenido, Jefe.</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl flex items-center gap-4 shadow-lg hover:border-lime-400 transition group">
            <div class="p-4 bg-lime-400/10 rounded-full text-lime-400 group-hover:bg-lime-400 group-hover:text-black transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold tracking-wider">Ingresos Totales</p>
                <h3 class="text-3xl font-black text-white">$<?= number_format($income, 2) ?></h3>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl flex items-center gap-4 shadow-lg hover:border-blue-500 transition group">
            <div class="p-4 bg-blue-500/10 rounded-full text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold tracking-wider">Pedidos Realizados</p>
                <h3 class="text-3xl font-black text-white"><?= $orders_count ?></h3>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl flex items-center gap-4 shadow-lg hover:border-red-500 transition group">
            <div class="p-4 bg-red-500/10 rounded-full text-red-500 group-hover:bg-red-500 group-hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold tracking-wider">Stock Crítico</p>
                <h3 class="text-3xl font-black text-white"><?= $low_stock ?> <span class="text-sm font-normal text-slate-500">productos</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 p-8 rounded-xl mb-12 border border-slate-800 shadow-2xl">
        <h3 class="text-2xl font-bold mb-6 text-white flex items-center gap-2">
            <span class="text-orange-500 text-3xl">+</span> Nuevo Producto
        </h3>
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="text" name="name" placeholder="Nombre del Producto" class="p-4 bg-slate-800 rounded-lg border border-slate-700 text-white focus:border-orange-500 outline-none transition" required>
            <div class="grid grid-cols-2 gap-4">
                <input type="number" step="0.01" name="price" placeholder="Precio ($)" class="p-4 bg-slate-800 rounded-lg border border-slate-700 text-white focus:border-orange-500 outline-none transition" required>
                <input type="number" name="stock" placeholder="Stock Cantidad" class="p-4 bg-slate-800 rounded-lg border border-slate-700 text-white focus:border-orange-500 outline-none transition" required>
            </div>
            <input type="file" name="image" class="p-3 bg-slate-800 rounded-lg border border-slate-700 text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 transition">
            <textarea name="description" placeholder="Descripción atractiva..." class="col-span-1 md:col-span-2 p-4 bg-slate-800 rounded-lg border border-slate-700 text-white h-24 focus:border-orange-500 outline-none transition"></textarea>
            
            <button type="submit" name="save_product" class="col-span-1 md:col-span-2 bg-gradient-to-r from-orange-500 to-red-600 text-white font-black py-4 rounded-lg hover:from-orange-600 hover:to-red-700 transition transform hover:scale-[1.01] shadow-lg">
                PUBLICAR PRODUCTO
            </button>
        </form>
    </div>

    <h3 class="text-2xl font-bold text-slate-400 mb-6 uppercase tracking-wider">Inventario Actual</h3>
    <div class="overflow-x-auto rounded-xl border border-slate-800 shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-950 text-slate-400 text-sm uppercase tracking-wider">
                    <th class="p-4">ID</th>
                    <th class="p-4">Img</th>
                    <th class="p-4">Nombre</th>
                    <th class="p-4 text-center">Stock</th>
                    <th class="p-4 text-right">Precio</th>
                    <th class="p-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-slate-900 divide-y divide-slate-800">
                <?php foreach($products as $p): ?>
                <tr class="hover:bg-slate-800/50 transition duration-150">
                    <td class="p-4 text-slate-500">#<?= $p['id'] ?></td>
                    <td class="p-4">
                        <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="w-12 h-12 object-cover rounded-lg border border-slate-700">
                    </td>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($p['name']) ?></td>
                    <td class="p-4 text-center">
                        <?php if($p['stock'] < 10): ?>
                            <span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                                <?= $p['stock'] ?> (Bajo)
                            </span>
                        <?php else: ?>
                            <span class="text-lime-400 font-bold"><?= $p['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-right font-mono text-slate-300">$<?= number_format($p['price'], 2) ?></td>
                    <td class="p-4 text-center">
                        <a href="admin.php?delete=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar este producto?')" class="text-red-500 hover:text-white hover:bg-red-600 px-3 py-1 rounded transition text-xs font-bold uppercase tracking-wider border border-red-500/30">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>