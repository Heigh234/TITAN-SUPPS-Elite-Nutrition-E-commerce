<?php 
require 'config.php'; 
require 'header.php';

// Lógica del Buscador
$search = $_GET['q'] ?? null;
$products = [];

if ($search) {
    // Si hay búsqueda, filtramos por nombre O descripción
    $sql = "SELECT * FROM products WHERE stock > 0 AND (name LIKE :q OR description LIKE :q)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['q' => "%$search%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no, mostramos todo
    $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="max-w-7xl mx-auto px-4 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
        <h1 class="text-5xl font-black uppercase tracking-widest text-white">
            Catálogo <span class="text-lime-400">Elite</span>
        </h1>

        <form action="index.php" method="GET" class="w-full md:w-1/3 flex gap-2">
            <div class="relative w-full">
                <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Buscar suplemento..." 
                       class="w-full bg-slate-800 text-white border border-slate-700 rounded-lg py-3 px-4 focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 transition placeholder-slate-500">
                
                <?php if($search): ?>
                    <a href="index.php" class="absolute right-3 top-3 text-slate-400 hover:text-red-500 font-bold" title="Borrar búsqueda">✕</a>
                <?php endif; ?>
            </div>
            <button type="submit" class="bg-lime-400 text-black font-bold px-6 py-3 rounded-lg hover:bg-lime-300 transition uppercase">
                🔍
            </button>
        </form>
    </div>

    <?php if($search): ?>
        <p class="text-slate-400 mb-8 text-xl">
            Mostrando resultados para: <span class="text-white font-bold">"<?= htmlspecialchars($search) ?>"</span>
        </p>
    <?php endif; ?>
    
    <?php if (empty($products)): ?>
        <div class="text-center py-20 bg-slate-900 rounded-xl border border-slate-800 border-dashed">
            <p class="text-6xl mb-4">😕</p>
            <h3 class="text-2xl font-bold text-white">No encontramos nada...</h3>
            <p class="text-slate-400 mt-2">Intenta buscar "Proteína", "Creatina" o revisa si escribiste bien.</p>
            <a href="index.php" class="inline-block mt-6 text-lime-400 hover:underline">Ver todo el catálogo</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach($products as $p): ?>
            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-xl hover:shadow-lime-400/20 transition duration-300 group flex flex-col h-full">
                <div class="h-64 overflow-hidden relative">
                    <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    
                    <?php if($p['stock'] < 10): ?>
                        <div class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 font-bold rounded text-xs uppercase tracking-wider animate-pulse">¡Últimas Unidades!</div>
                    <?php else: ?>
                        <div class="absolute top-2 right-2 bg-black/70 text-lime-400 px-3 py-1 font-bold rounded text-xs">Stock: <?= $p['stock'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-white mb-2"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="text-slate-400 mb-4 h-12 overflow-hidden text-sm"><?= htmlspecialchars($p['description']) ?></p>
                    
                    <div class="mt-auto flex justify-between items-center pt-4 border-t border-slate-800">
                        <span class="text-3xl font-black text-white">$<?= number_format($p['price'], 2) ?></span>
                        <form action="cart_action.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <button type="submit" name="add" class="bg-lime-400 text-black font-bold px-6 py-2 rounded hover:bg-lime-300 transition uppercase tracking-wide text-sm shadow-lg shadow-lime-400/20">
                                Añadir 🛒
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body></html>