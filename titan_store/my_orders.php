<?php
require 'config.php';
require 'header.php';

// Seguridad: Solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener pedidos del usuario
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-4xl font-black text-white mb-8 uppercase">Historial de <span class="text-lime-400">Compras</span></h1>

    <?php if (empty($orders)): ?>
        <p class="text-slate-400 text-xl">Aún no has realizado pedidos.</p>
        <a href="index.php" class="inline-block mt-4 text-lime-400 border border-lime-400 px-6 py-2 rounded hover:bg-lime-400 hover:text-black transition font-bold">Ir a la Tienda</a>
    <?php else: ?>
        
        <div class="space-y-6">
            <?php foreach($orders as $order): ?>
                <div class="bg-slate-900 border border-slate-800 rounded-lg overflow-hidden shadow-lg">
                    <div class="bg-slate-800 p-4 flex justify-between items-center">
                        <div>
                            <span class="text-slate-400 text-sm">Pedido #<?= $order['id'] ?></span>
                            <p class="text-white font-bold"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        </div>
                        <div class="text-right">
                            <span class="block text-lime-400 font-black text-xl">$<?= number_format($order['total'], 2) ?></span>
                            <span class="text-xs bg-green-900 text-green-300 px-2 py-1 rounded uppercase font-bold tracking-wider">Completado</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <table class="w-full text-left text-slate-300">
                            <tbody>
                                <?php 
                                $stmt2 = $pdo->prepare("
                                    SELECT p.name, d.quantity, d.price 
                                    FROM order_details d 
                                    JOIN products p ON d.product_id = p.id 
                                    WHERE d.order_id = ?
                                ");
                                $stmt2->execute([$order['id']]);
                                while($item = $stmt2->fetch()):
                                ?>
                                <tr class="border-b border-slate-800 last:border-0">
                                    <td class="py-2"><?= $item['name'] ?></td>
                                    <td class="py-2 text-center">x<?= $item['quantity'] ?></td>
                                    <td class="py-2 text-right">$<?= number_format($item['price'], 2) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>