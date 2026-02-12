<?php 
require 'config.php';
require 'header.php';

// PROCESAR COMPRA (CHECKOUT)
if (isset($_POST['checkout']) && isset($_SESSION['user_id'])) {
    try {
        $pdo->beginTransaction(); // Iniciar transacción segura
        
        // 1. Crear Orden
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $_POST['total']]);
        $order_id = $pdo->lastInsertId();

        // 2. Insertar detalles y restar stock
        foreach ($_SESSION['cart'] as $pid => $qty) {
            // Verificar stock actual antes de restar
            $prod = $pdo->prepare("SELECT price, stock FROM products WHERE id = ?");
            $prod->execute([$pid]);
            $item = $prod->fetch();

            if ($item['stock'] < $qty) {
                throw new Exception("Stock insuficiente para el producto ID: $pid");
            }

            // Insertar detalle
            $stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $pid, $qty, $item['price']]);

            // Restar inventario
            $update = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $update->execute([$qty, $pid]);
        }

        $pdo->commit(); // Confirmar cambios si todo salió bien
        
        // Limpiar carrito
        unset($_SESSION['cart']);

        // PREPARAR ALERTA DE ÉXITO (SweetAlert)
        $_SESSION['swal'] = [
            'title' => '¡COMPRA EXITOSA!',
            'text' => 'Tu pedido ha sido procesado. ¡A ganar masa muscular!',
            'type' => 'success'
        ];
        
        // Redirigir al inicio para ver la alerta
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack(); // Deshacer cambios si algo falló
        
        // PREPARAR ALERTA DE ERROR
        $_SESSION['swal'] = [
            'title' => 'Error en la compra',
            'text' => $e->getMessage(),
            'type' => 'error'
        ];
    }
}
?>

<div class="max-w-4xl mx-auto mt-10 p-6 bg-slate-900 rounded-lg shadow-xl border border-slate-800">
    <h2 class="text-3xl font-bold mb-6 text-lime-400 uppercase tracking-wide">Tu Carrito <span class="text-white">Anabólico</span></h2>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="text-center py-10">
            <p class="text-slate-400 text-xl mb-4">El carrito está vacío.</p>
            <a href="index.php" class="inline-block bg-slate-800 text-lime-400 border border-lime-400 px-6 py-2 rounded hover:bg-lime-400 hover:text-black transition font-bold">Volver a la Tienda</a>
        </div>
    <?php else: ?>
        <table class="w-full text-left mb-6">
            <thead>
                <tr class="border-b border-slate-700 text-slate-400">
                    <th class="py-2">Producto</th>
                    <th class="text-center">Cant</th>
                    <th class="text-right">Precio</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                // Obtenemos los productos que están en el carrito
                $ids = implode(',', array_keys($_SESSION['cart']));
                $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
                
                while($row = $stmt->fetch()):
                    $qty = $_SESSION['cart'][$row['id']];
                    $sub = $row['price'] * $qty;
                    $total += $sub;
                ?>
                <tr class="border-b border-slate-800 last:border-0">
                    <td class="py-4 font-bold text-white"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center text-slate-300"><?= $qty ?></td>
                    <td class="text-right text-slate-300">$<?= number_format($row['price'], 2) ?></td>
                    <td class="text-right text-lime-400 font-bold">$<?= number_format($sub, 2) ?></td>
                    <td class="text-center">
                        <a href="cart_action.php?remove=<?= $row['id'] ?>" class="text-red-500 hover:text-red-400 font-bold px-2 py-1 rounded hover:bg-red-500/10 transition" title="Eliminar">✕</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="flex flex-col md:flex-row justify-between items-center border-t border-slate-700 pt-6 gap-4">
            <a href="cart_action.php?clear=true" class="text-slate-500 hover:text-red-400 text-sm underline">Vaciar Carrito</a>
            
            <div class="flex items-center gap-6">
                <h3 class="text-3xl font-black text-white">Total: <span class="text-lime-400">$<?= number_format($total, 2) ?></span></h3>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form method="POST">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        <button type="submit" name="checkout" class="bg-lime-400 text-black px-8 py-3 font-bold rounded hover:bg-lime-300 transition text-lg shadow-lg shadow-lime-400/20 uppercase tracking-wide">
                            Pagar Ahora
                        </button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="inline-block bg-orange-500 text-white px-6 py-2 rounded font-bold hover:bg-orange-600 transition">
                        Inicia sesión para pagar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>