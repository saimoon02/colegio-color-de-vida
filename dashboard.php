<?php
$pagina_titulo = 'Dashboard';
require_once 'includes/header.php';
require_once 'includes/db.php';
?>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-info">
            <h3><?= $pdo->query("SELECT COUNT(*) FROM estudiantes WHERE activo=1")->fetchColumn() ?></h3>
            <p>Estudiantes Activos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teachers"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-info">
            <h3><?= $pdo->query("SELECT COUNT(*) FROM profesores WHERE activo=1")->fetchColumn() ?></h3>
            <p>Profesores</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon courses"><i class="fas fa-school"></i></div>
        <div class="stat-info">
            <h3><?= $pdo->query("SELECT COUNT(*) FROM cursos WHERE activo=1")->fetchColumn() ?></h3>
            <p>Cursos Activos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon grades"><i class="fas fa-file-signature"></i></div>
        <div class="stat-info">
            <h3><?= $pdo->query("SELECT COUNT(*) FROM matriculas WHERE estado='Activa'")->fetchColumn() ?></h3>
            <p>Matrículas Activas</p>
        </div>
    </div>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
    <!-- Últimos estudiantes -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-user-plus"></i> Últimos Estudiantes</h2>
            <a href="estudiantes.php" class="btn btn-sm btn-info">Ver todos</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT e.codigo, e.nombre, e.apellido, c.nombre as curso_nombre FROM estudiantes e LEFT JOIN cursos c ON c.id = e.curso_id WHERE e.activo=1 ORDER BY e.creado_en DESC LIMIT 8");
                        while ($row = $stmt->fetch()):
                        ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= $row['codigo'] ?></span></td>
                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                            <td><?= htmlspecialchars($row['curso_nombre'] ?? 'Sin curso') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Últimos profesores -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-chalkboard-teacher"></i> Profesores</h2>
            <a href="profesores.php" class="btn btn-sm btn-info">Ver todos</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Especialidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT codigo, nombre, apellido, especialidad FROM profesores WHERE activo=1 ORDER BY codigo LIMIT 8");
                        while ($row = $stmt->fetch()):
                        ?>
                        <tr>
                            <td><span class="badge badge-success"><?= $row['codigo'] ?></span></td>
                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                            <td><?= htmlspecialchars($row['especialidad']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Calificaciones recientes -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-exclamation-triangle"></i> Calificaciones por debajo de 3.0</h2>
        <a href="calificaciones.php" class="btn btn-sm btn-info">Ver calificaciones</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Periodo</th>
                        <th>Definitiva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT CONCAT(e.nombre,' ',e.apellido) as estudiante, m.nombre as materia, p.nombre as periodo, c.definitiva FROM calificaciones c JOIN estudiantes e ON e.id = c.estudiante_id JOIN curso_materia cm ON cm.id = c.curso_materia_id JOIN materias m ON m.id = cm.materia_id JOIN periodos p ON p.id = c.periodo_id WHERE c.definitiva < 3.0 ORDER BY c.definitiva ASC LIMIT 10");
                    while ($row = $stmt->fetch()):
                        $gradeClass = $row['definitiva'] < 2.0 ? 'grade-low' : 'grade-fair';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['estudiante']) ?></td>
                        <td><?= htmlspecialchars($row['materia']) ?></td>
                        <td><?= htmlspecialchars($row['periodo']) ?></td>
                        <td><span class="<?= $gradeClass ?>"><?= number_format($row['definitiva'], 2) ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
