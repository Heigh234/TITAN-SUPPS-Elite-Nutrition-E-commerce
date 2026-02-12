<?php
require 'config.php';
require 'header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $message = "<p class='text-red-500 text-center font-bold mb-4 bg-red-500/10 p-2 rounded border border-red-500/50'>¡Ese correo ya está registrado!</p>";
    } else {
        // 2. Encriptar contraseña y guardar
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$name, $email, $hash])) {
            // AQUÍ ESTÁ LA MAGIA DE SWEETALERT
            $_SESSION['swal'] = [
                'title' => '¡Bienvenido al Equipo!',
                'text' => 'Tu cuenta ha sido creada exitosamente. Ahora inicia sesión.',
                'type' => 'success'
            ];
            
            // Redirigimos al login para que vea la alerta allá
            header("Location: login.php");
            exit;
        } else {
            $message = "<p class='text-red-500 text-center'>Ocurrió un error al registrar.</p>";
        }
    }
}
?>

<div class="max-w-md mx-auto mt-16 p-8 bg-slate-900 rounded-xl border border-slate-800 shadow-2xl">
    <h2 class="text-3xl font-black mb-6 text-center text-white uppercase tracking-wider">Únete al <span class="text-lime-400">Equipo</span></h2>
    
    <?= $message ?>

    <form method="POST" class="space-y-5">
        <div>
            <label class="block text-slate-400 mb-1 text-sm">Nombre Completo</label>
            <input type="text" name="name" class="w-full p-3 bg-slate-800 rounded border border-slate-700 text-white focus:border-lime-400 focus:ring-1 focus:ring-lime-400 outline-none transition" required>
        </div>
        
        <div>
            <label class="block text-slate-400 mb-1 text-sm">Correo Electrónico</label>
            <input type="email" name="email" class="w-full p-3 bg-slate-800 rounded border border-slate-700 text-white focus:border-lime-400 focus:ring-1 focus:ring-lime-400 outline-none transition" required>
        </div>

        <div>
            <label class="block text-slate-400 mb-1 text-sm">Contraseña</label>
            <input type="password" name="password" class="w-full p-3 bg-slate-800 rounded border border-slate-700 text-white focus:border-lime-400 focus:ring-1 focus:ring-lime-400 outline-none transition" required>
        </div>

        <button type="submit" class="w-full bg-lime-400 text-black font-bold p-3 rounded hover:bg-lime-300 transition uppercase tracking-wide shadow-lg shadow-lime-400/20">
            Crear Cuenta
        </button>
    </form>

    <div class="mt-6 text-center text-slate-400 text-sm">
        ¿Ya tienes cuenta? 
        <a href="login.php" class="text-lime-400 font-bold hover:underline">Inicia Sesión aquí</a>
    </div>
</div>