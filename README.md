<p align="center">
  <img src="https://github.com/Linavs18/FitTrack-sena/blob/dev/fittrack/public/img/Fittrack-logo.png" alt="FitTrack Logo" width="500px">
</p>

## 🛠️ FITTRACK

**FITTRACK** es una aplicación web desarrollada en **Laravel** con **MySQL**, permita a los usuarios llevar un registro de su actividad física diaria, visualizar su progreso y obtener reportes
simples.

---

### 💻 Tecnologías utilizadas

<p align="center"> 
  <img src="https://img.shields.io/badge/Java-ED8B00?style=for-the-badge&logo=java&logoColor=white" height="25">
  <img src="https://img.shields.io/badge/JavaScript-%23F7DF1E.svg?style=for-the-badge&logo=javascript&logoColor=black" height="25">
  <img src="https://img.shields.io/badge/PHP-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white" height="25">
  <img src="https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white" height="25">
  <img src="https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white" height="25">
</p>⚙️ Frameworks y Librerías

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" height="25">
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" height="25">
</p>

---

## 📋 Funcionalidades principales
<picture> <img align="right" src="https://certificadossena.net/wp-content/uploads/2022/10/logo-sena-verde-complementario-svg-2022.svg" width="250px"></picture>

- 📦 **Gestión de entidades**:
  - Actividad Fisica 
  - Usuarios 

- 📊 **Reportes y exportación**:
  - Generación de reportes en **PDF**
  - Estadísticas visuales

- 🧭 **Interfaz y usabilidad**:
  - Menú de navegación intuitivo
  - Vistas responsivas con **Bootstrap**
  - Formularios validados y adaptados

---

## 🚀 Comenzando

### Requisitos previos

- PHP 8.1 o superior  
- Composer  
- MySQL 8.x
- Node.js y npm (para compilar assets con Vite)  

### Instalación

```bash
# 1. Clona el repositorio
git clone https://github.com/Linavs18/FitTrack-sena.git
cd FitTrack-sena/fittrack

# 2. Instala dependencias
composer install

# 3. Configura el archivo .env
cp .env.example .env

# 4. Importa la base de datos
BD/fittrack_db.sql

# 5. Genera la clave de aplicación
php artisan key:generate

# 6. Ejecuta el servidor
php artisan serve

```
