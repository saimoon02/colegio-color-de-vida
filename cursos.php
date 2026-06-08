<?php
$pagina_titulo = 'Cursos';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $grado_id = (int)$_POST['grado_id'];
    $nombre = trim($_POST['nombre']);
    $anio = (int)$_POST['anio'];
    $director_id = $_POST['director_id'] ?: null;
    $jornada = $_POST['jornada'];
    $salon = trim($_POST['salon'] ?? '');
    $capacidad = (int)($_POST['capacidad'] ?? 30);

    $stmt = $pdo->prepare("INSERT INTO cursos (grado_id, nombre, anio, director_id, jornada, salon, capacidad) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$grado_id, $nombre, $anio, $director_id, $jornada, $salon, $capacidad]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Curso creado exitosamente.</div>';
}

if (isset($_GET['delete'])) {
    $pdo->prepare("UPDATE cursos SET activo=0 WHERE id=?")->execute([(int)$_GET['delete']]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Curso desactivado.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE cursos SET grado_id=?, nombre=?, anio=?, director_id=?, jornada=?, salon=?, capacidad=? WHERE id=?");
    $stmt->execute([(int)$_POST['grado_id'], trim($_POST['nombre']), (int)$_POST['anio'], $_POST['director_id'] ?: null, $_POST['jornada'], trim($_POST['salon']), (int)$_POST['capacidad'], $id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Curso actualizado.</div>';
}

$buscar = trim($_GET['buscar'] ?? '');
$where = $buscar ? "WHERE c.activo=1 AND (c.nombre LIKE ? OR g.nombre LIKE ?)" : "WHERE c.activo=1";
$params = $buscar ? ["%$buscar%","%$buscar%"] : [];
$stmt = $pdo->prepare("SELECT c.*, g.nombre as grado_nombre, g.nivel, CONCAT(p.nombre,' ',p.apellido) as director_nombre FROM cursos c JOIN grados g ON g.id=c.grado_id LEFT JOIN profesores p ON p.id=c.director_id $where ORDER BY g.id, c.nombre");
$stmt->execute($params);
$cursos = $stmt->fetchAll();

$grados = $pdo->query("SELECT id, nombre, nivel FROM grados WHERE activo=1 ORDER BY id")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre, apellido FROM profesores WHERE activo=1 ORDER BY apellido")->fetchAll();
$anio_actual = date('Y');
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-school"></i> Lista de Cursos (<?= count($cursos) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nuevo Curso
        </button>
    </div>
    <div class="card-body">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar curso..." value="<?= htmlspecialchars($buscar) ?>">
            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Grado</th>
                        <th>Nivel</th>
                        <th>Año</th>
                        <th>Jornada</th>
                        <th>Salón</th>
                        <th>Director</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursos as $c): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($c['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($c['grado_nombre']) ?></td>
                        <td><span class="badge badge-info"><?= $c['nivel'] ?></span></td>
                        <td><?= $c['anio'] ?></td>
                        <td><?= $c['jornada'] ?></td>
                        <td><?= htmlspecialchars($c['salon'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($c['director_nombre'] ?? '<span class="badge badge-warning">Sin asignar</span>') ?></td>
                        <td><?= $c['capacidad'] ?></td>
                        <td class="actions">
                            <button class="btn btn-sm btn-info" onclick='openEditModal(<?= json_encode($c) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                            <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar este curso?')" title="Desactivar"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVO CURSO -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Nuevo Curso</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: Primero A">
                    </div>
                    <div class="form-group">
                        <label>Grado *</label>
                        <select name="grado_id" class="form-control" required>
                            <?php foreach ($grados as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre'] . ' - ' . $g['nivel']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Año *</label>
                        <input type="number" name="anio" class="form-control" value="<?= $anio_actual ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Jornada</label>
                        <select name="jornada" class="form-control">
                            <option value="Manana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Unica">Única</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Salón</label>
                        <input type="text" name="salon" class="form-control" placeholder="Ej: 101">
                    </div>
                    <div class="form-group">
                        <label>Capacidad</label>
                        <input type="number" name="capacidad" class="form-control" value="30">
                    </div>
                </div>
                <div class="form-group">
                    <label>Director de Grupo</label>
                    <select name="director_id" class="form-control">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($profesores as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" onclick="closeModal('modal-nuevo')">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR CURSO -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Curso</h3>
            <button class="modal-close" onclick="closeModal('modal-editar')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Grado *</label>
                        <select name="grado_id" id="edit_grado_id" class="form-control" required>
                            <?php foreach ($grados as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre'] . ' - ' . $g['nivel']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Año *</label>
                        <input type="number" name="anio" id="edit_anio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jornada</label>
                        <select name="jornada" id="edit_jornada" class="form-control">
                            <option value="Manana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Unica">Única</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Salón</label>
                        <input type="text" name="salon" id="edit_salon" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Capacidad</label>
                        <input type="number" name="capacidad" id="edit_capacidad" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Director de Grupo</label>
                    <select name="director_id" id="edit_director_id" class="form-control">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($profesores as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></option>
                        <?php endforeach; ?>
                    </select>
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
    document.getElementById('edit_grado_id').value = data.grado_id;
    document.getElementById('edit_anio').value = data.anio;
    document.getElementById('edit_jornada').value = data.jornada;
    document.getElementById('edit_salon').value = data.salon || '';
    document.getElementById('edit_capacidad').value = data.capacidad;
    document.getElementById('edit_director_id').value = data.director_id || '';
    openModal('modal-editar');
}
</script>

<?php require_once 'includes/footer.php'; ?>
