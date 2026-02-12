# 🏋️‍♂️ TITAN SUPPS | Elite Nutrition E-commerce

![Versión](https://img.shields.io/badge/version-1.0.0-lime)
![PHP](https://img.shields.io/badge/PHP-8.2-777bb4)
![MySQL](https://img.shields.io/badge/MySQL-PDO-4479a1)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38b2ac)

**TITAN SUPPS** es una plataforma de comercio electrónico de alto rendimiento diseñada para la industria del fitness. Este proyecto demuestra la capacidad de integrar una interfaz de usuario moderna con un sistema de gestión de datos dinámico y seguro.

---

## 🚀 Funcionalidades Destacadas

### 🛒 Experiencia de Compra Dinámica
* **Gestión de Carrito:** Implementación de lógica de persistencia mediante sesiones de PHP para una compra fluida.
* **Seguridad en Transacciones:** Uso de **transacciones SQL** para garantizar que la actualización del inventario y el registro del pedido ocurran de forma atómica y segura.
* **Historial Personalizado:** Los usuarios autenticados pueden acceder a un registro detallado de sus pedidos previos.

### 📊 Panel Administrativo (CMS Propio)
* **Control de Métricas:** Dashboard con visualización de ingresos totales, contador de pedidos y alertas de stock.
* **Gestión de Catálogo:** Interfaz intuitiva para el alta, baja y modificación de productos con soporte para imágenes dinámicas.
* **Monitoreo de Inventario:** Sistema preventivo que resalta automáticamente productos con existencias críticas (<10 unidades).

### 🔍 Optimización de Usuario (UX)
* **Filtrado Avanzado:** Buscador optimizado con consultas SQL preparadas para proteger contra inyecciones y mejorar la velocidad de respuesta.
* **Feedback Visual:** Integración de **SweetAlert2** para proporcionar una experiencia de usuario más profesional y reactiva que las alertas estándar del sistema.

---

## 🛠️ Stack Tecnológico

* **Backend:** PHP 8.x con PDO (PHP Data Objects).
* **Base de Datos:** MySQL con diseño de integridad referencial.
* **Frontend:** HTML5, Tailwind CSS, JavaScript (ES6+).
* **Seguridad:** Hashing de contraseñas mediante `password_hash` y protección contra ataques XSS y SQLi.

---

## 🏗️ Arquitectura del Sistema

El sistema sigue un modelo de desarrollo modular:
1. **Capa de Datos:** Base de datos normalizada para evitar redundancias en pedidos y productos.
2. **Lógica de Negocio:** Scripts de procesamiento dedicados para el manejo del carrito y validaciones de usuario.
3. **Capa de Presentación:** Componentes reutilizables (Headers/Footers) y estilos modernos basados en utilidades.

---

## 📧 Contacto y Demo

Este proyecto ha sido desarrollado por **Darwin Villalobos** como parte de su portafolio profesional de desarrollo web freelance.

* **Live Demo:** [Enlace a tu sitio en InfinityFree]
* **Correo Profesional:** darwinvillalobos0201@gmail.com
