<?php
$pagina_titulo = 'Materias';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $nombre = trim($_POST['nombre']);
    $area = trim($_POST['area'] ?? '');
    $ih = (int)($_POST['intensidad_horaria'] ?? 0);
    $pdo->prepare("INSERT INTO materias (nombre, area, intensidad_horaria) VALUES (?,?,?)")->execute([$nombre, $area, $ih]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Materia creada.</div>';
}

if (isset($_GET['delete'])) {
    $pdo->prepare("UPDATE materias SET activo=0 WHERE id=?")->execute([(int)$_GET['delete']]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Materia desactivada.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $pdo->prepare("UPDATE materias SET nombre=?, area=?, intensidad_horaria=? WHERE id=?")->execute([trim($_POST['nombre']), trim($_POST['area']), (int)$_POST['intensidad_horaria'], $id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Materia actualizada.</div>';
}

$buscar = trim($_GET['buscar'] ?? '');
$where = $buscar ? "WHERE m.activo=1 AND (m.nombre LIKE ? OR m.area LIKE ?)" : "WHERE m.activo=1";
$params = $buscar ? ["%$buscar%","%$buscar%"] : [];
$stmt = $pdo->prepare("SELECT m.* FROM materias m $where ORDER BY m.nombre");
$stmt->execute($params);
$materias = $stmt->fetchAll();
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-book"></i> Lista de Materias (<?= count($materias) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nueva Materia
        </button>
    </div>
    <div class="card-body">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar materia..." value="<?= htmlspecialchars($buscar) ?>">
            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Materia</th>
                        <th>Área</th>
                        <th>Intensidad Horaria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $i => $m): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($m['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($m['area'] ?? '-') ?></td>
                        <td><?= $m['intensidad_horaria'] ?> hrs/semana</td>
                        <td class="actions">
                            <button class="btn btn-sm btn-info" onclick='openEditModal(<?= json_encode($m) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                            <a href="?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar esta materia?')" title="Desactivar"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVA MATERIA -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Nueva Materia</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Área</label>
                        <input type="text" name="area" class="form-control" placeholder="Ej: Ciencias">
                    </div>
                    <div class="form-group">
                        <label>Intensidad Horaria (hrs/sem)</label>
                        <input type="number" name="intensidad_horaria" class="form-control" value="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="closeModal('modal-nuevo')">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR MATERIA -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Materia</h3>
            <button class="modal-close" onclick="closeModal('modal-editar')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Área</label>
                        <input type="text" name="area" id="edit_area" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Intensidad Horaria (hrs/sem)</label>
                        <input type="number" name="intensidad_horaria" id="edit_intensidad_horaria" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="closeModal('modal-editar')">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nombre').value = data.nombre;
    document.getElementById('edit_area').value = data.area || '';
    document.getElementById('edit_intensidad_horaria').value = data.intensidad_horaria;
    openModal('modal-editar');
}
</script>

<?php require_once 'includes/footer.php'; ?>
