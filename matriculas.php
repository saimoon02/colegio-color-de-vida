<?php
$pagina_titulo = 'Matrículas';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'matricular') {
    $estudiante_id = (int)$_POST['estudiante_id'];
    $curso_id = (int)$_POST['curso_id'];
    $anio = (int)$_POST['anio'];

    $stmt = $pdo->prepare("INSERT INTO matriculas (estudiante_id, curso_id, anio, estado) VALUES (?,?,?,'Activa') ON DUPLICATE KEY UPDATE curso_id=?, estado='Activa'");
    $stmt->execute([$estudiante_id, $curso_id, $anio, $curso_id]);

    // Actualizar curso del estudiante
    $pdo->prepare("UPDATE estudiantes SET curso_id=? WHERE id=?")->execute([$curso_id, $estudiante_id]);

    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Matrícula registrada exitosamente.</div>';
}

if (isset($_GET['cambiar_estado'])) {
    $id = (int)$_GET['cambiar_estado'];
    $estado = $_GET['estado'] ?? 'Cancelada';
    $pdo->prepare("UPDATE matriculas SET estado=? WHERE id=?")->execute([$estado, $id]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Estado actualizado.</div>';
}

$buscar = trim($_GET['buscar'] ?? '');
$where = $buscar ? "WHERE (e.nombre LIKE ? OR e.apellido LIKE ? OR e.codigo LIKE ?)" : "";
$params = $buscar ? ["%$buscar%","%$buscar%","%$buscar%"] : [];
$stmt = $pdo->prepare("SELECT m.*, CONCAT(e.nombre,' ',e.apellido) as estudiante_nombre, e.codigo as estudiante_codigo, c.nombre as curso_nombre FROM matriculas m JOIN estudiantes e ON e.id=m.estudiante_id JOIN cursos c ON c.id=m.curso_id $where ORDER BY m.creado_en DESC");
$stmt->execute($params);
$matriculas = $stmt->fetchAll();

$estudiantes_sin = $pdo->query("SELECT id, codigo, nombre, apellido FROM estudiantes WHERE activo=1 AND curso_id IS NULL ORDER BY apellido")->fetchAll();
$cursos = $pdo->query("SELECT id, nombre FROM cursos WHERE activo=1 ORDER BY nombre")->fetchAll();
$anio_actual = date('Y');
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-signature"></i> Matrículas (<?= count($matriculas) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nueva Matrícula
        </button>
    </div>
    <div class="card-body">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar estudiante..." value="<?= htmlspecialchars($buscar) ?>">
            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Código</th>
                        <th>Curso</th>
                        <th>Año</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $mat): ?>
                    <tr>
                        <td><?= htmlspecialchars($mat['estudiante_nombre']) ?></td>
                        <td><span class="badge badge-primary"><?= $mat['estudiante_codigo'] ?></span></td>
                        <td><?= htmlspecialchars($mat['curso_nombre']) ?></td>
                        <td><?= $mat['anio'] ?></td>
                        <td><?= $mat['fecha_matricula'] ?></td>
                        <td>
                            <?php
                            $badge = $mat['estado'] === 'Activa' ? 'success' : ($mat['estado'] === 'Cancelada' ? 'danger' : 'warning');
                            ?>
                            <span class="badge badge-<?= $badge ?>"><?= $mat['estado'] ?></span>
                        </td>
                        <td class="actions">
                            <?php if ($mat['estado'] === 'Activa'): ?>
                            <a href="?cambiar_estado=<?= $mat['id'] ?>&estado=Suspendida" class="btn btn-sm btn-warning" title="Suspender"><i class="fas fa-pause"></i></a>
                            <a href="?cambiar_estado=<?= $mat['id'] ?>&estado=Cancelada" class="btn btn-sm btn-danger" title="Cancelar"><i class="fas fa-times"></i></a>
                            <?php else: ?>
                            <a href="?cambiar_estado=<?= $mat['id'] ?>&estado=Activa" class="btn btn-sm btn-success" title="Reactivar"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVA MATRICULA -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-file-signature"></i> Nueva Matrícula</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="matricular">
            <div class="modal-body">
                <div class="form-group">
                    <label>Estudiante *</label>
                    <select name="estudiante_id" class="form-control" required>
                        <option value="">-- Seleccionar estudiante --</option>
                        <?php foreach ($estudiantes_sin as $es): ?>
                        <option value="<?= $es['id'] ?>"><?= htmlspecialchars($es['apellido'] . ' ' . $es['nombre'] . ' (' . $es['codigo'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Curso *</label>
                        <select name="curso_id" class="form-control" required>
                            <option value="">-- Seleccionar curso --</option>
                            <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Año</label>
                        <input type="number" name="anio" class="form-control" value="<?= $anio_actual ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="closeModal('modal-nuevo')">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Matricular</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
