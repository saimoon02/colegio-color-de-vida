# 🎓 Sistema Escolar - Colegio Color de Vida

Sistema de gestión escolar completo desarrollado en PHP y MySQL para el Colegio Color de Vida.

## ✨ Características

- **Dashboard** con estadísticas en tiempo real
- **Gestión de Estudiantes** (CRUD completo con búsqueda y filtros)
- **Gestión de Profesores** (CRUD con especialidades)
- **Gestión de Cursos** (por grados y niveles)
- **Gestión de Materias** (áreas e intensidad horaria)
- **Sistema de Calificaciones** (3 notas con definitiva ponderada)
- **Sistema de Matrículas** (con estados: Activa, Suspendida, Cancelada)
- **Sistema de Usuarios** con roles (Admin, Docente, Estudiante, Acudiente)
- **Interfaz responsive** con diseño moderno

## 🛠️ Tecnologías

- PHP 8.4
- MySQL / MariaDB
- Apache 2
- CSS3 (Custom properties, Grid, Flexbox)
- JavaScript (Vanilla)
- Font Awesome 6

## 📋 Requisitos

- PHP >= 7.4
- MySQL >= 5.7 o MariaDB >= 10.3
- Apache con mod_rewrite habilitado

## 🚀 Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/usuario/colegio-color-de-vida.git
```

2. Importar la base de datos:
```bash
mysql -u root -p color_de_vida < database.sql
```

3. Configurar credenciales en `includes/db.php`

4. Apuntar el servidor web al directorio del proyecto

5. Acceder con:
   - **Usuario:** admin
   - **Contraseña:** password

## 📊 Estructura de la Base de Datos

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Usuarios del sistema con roles |
| `roles` | Roles: admin, docente, estudiante, acudiente |
| `estudiantes` | Información de estudiantes |
| `profesores` | Información de profesores |
| `acudientes` | Padres o acudientes |
| `grados` | Niveles: Preescolar, Primaria, Secundaria, Media |
| `cursos` | Cursos por grado y año |
| `materias` | Materias/áreas del currículo |
| `curso_materia` | Materias asignadas a cada curso con profesor |
| `periodos` | Periodos académicos |
| `calificaciones` | Notas de estudiantes por materia y periodo |
| `matriculas` | Registros de matrícula |

## 📝 Licencia

Este proyecto es de uso libre para fines educativos.

---

© <?= date('Y') ?> Colegio Color de Vida
