<?php
require 'config.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_name'] = $user['name']; // Guardamos el nombre también
        header("Location: index.php");
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<div class="max-w-md mx-auto mt-20 p-8 bg-slate-900 rounded-xl border border-slate-800 shadow-2xl">
    <h2 class="text-3xl font-black mb-6 text-center text-white uppercase tracking-wider">Acceso <span class="text-lime-400">Miembros</span></h2>
    
    <?php if(isset($error)) echo "<p class='bg-red-500/20 text-red-500 p-3 rounded text-center mb-4 border border-red-500/50'>$error</p>"; ?>
    
    <form method="POST" class="space-y-5">
        <div>
            <label class="block text-slate-400 mb-1 text-sm">Email</label>
            <input type="email" name="email" class="w-full p-3 bg-slate-800 rounded border border-slate-700 text-white focus:border-lime-400 focus:ring-1 focus:ring-lime-400 outline-none transition" required>
        </div>
        
        <div>
            <label class="block text-slate-400 mb-1 text-sm">Contraseña</label>
            <input type="password" name="password" class="w-full p-3 bg-slate-800 rounded border border-slate-700 text-white focus:border-lime-400 focus:ring-1 focus:ring-lime-400 outline-none transition" required>
        </div>

        <button type="submit" class="w-full bg-lime-400 text-black font-bold p-3 rounded hover:bg-lime-300 transition uppercase tracking-wide shadow-lg shadow-lime-400/20">ENTRAR</button>
    </form>

    <div class="mt-6 text-center text-slate-400 text-sm">
        ¿Eres nuevo en TITAN? 
        <a href="register.php" class="text-lime-400 font-bold hover:underline">Crea tu cuenta gratis</a>
    </div>
</div>