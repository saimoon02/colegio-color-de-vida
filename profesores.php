<?php
$pagina_titulo = 'Profesores';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_doc = $_POST['tipo_documento'];
    $num_doc = trim($_POST['documento']);
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');
    $fecha_nac = $_POST['fecha_nacimiento'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO profesores (codigo, nombre, apellido, tipo_documento, documento, email, telefono, direccion, especialidad, fecha_nacimiento) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$codigo, $nombre, $apellido, $tipo_doc, $num_doc, $email, $telefono, $direccion, $especialidad, $fecha_nac]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Profesor registrado exitosamente.</div>';
}

if (isset($_GET['delete'])) {
    $pdo->prepare("UPDATE profesores SET activo=0 WHERE id=?")->execute([(int)$_GET['delete']]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Profesor desactivado.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE profesores SET codigo=?, nombre=?, apellido=?, tipo_documento=?, documento=?, email=?, telefono=?, direccion=?, especialidad=?, fecha_nacimiento=? WHERE id=?");
    $stmt->execute([trim($_POST['codigo']), trim($_POST['nombre']), trim($_POST['apellido']), $_POST['tipo_documento'], trim($_POST['documento']), trim($_POST['email']), trim($_POST['telefono']), trim($_POST['direccion']), trim($_POST['especialidad']), $_POST['fecha_nacimiento'] ?: null, $id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Profesor actualizado.</div>';
}

$buscar = trim($_GET['buscar'] ?? '');
$where = $buscar ? "WHERE p.activo=1 AND (p.nombre LIKE ? OR p.apellido LIKE ? OR p.codigo LIKE ? OR p.especialidad LIKE ?)" : "WHERE p.activo=1";
$params = $buscar ? ["%$buscar%","%$buscar%","%$buscar%","%$buscar%"] : [];
$stmt = $pdo->prepare("SELECT p.* FROM profesores p $where ORDER BY p.apellido, p.nombre");
$stmt->execute($params);
$profesores = $stmt->fetchAll();
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-chalkboard-teacher"></i> Lista de Profesores (<?= count($profesores) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nuevo Profesor
        </button>
    </div>
    <div class="card-body">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar por nombre, código o especialidad..." value="<?= htmlspecialchars($buscar) ?>">
            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
        </form>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Especialidad</th>
                        <th>Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profesores as $p): ?>
                    <tr>
                        <td><span class="badge badge-success"><?= $p['codigo'] ?></span></td>
                        <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
                        <td><?= $p['tipo_documento'] . ' ' . $p['documento'] ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['telefono'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['especialidad'] ?? '-') ?></td>
                        <td><?= $p['fecha_ingreso'] ?></td>
                        <td class="actions">
                            <button class="btn btn-sm btn-info" onclick='openEditModal(<?= json_encode($p) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                            <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar este profesor?')" title="Desactivar"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVO PROFESOR -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Nuevo Profesor</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Código *</label>
                        <input type="text" name="codigo" class="form-control" required placeholder="PROF-XXX">
                    </div>
                    <div class="form-group">
                        <label>Especialidad</label>
                        <input type="text" name="especialidad" class="form-control" placeholder="Ej: Matemáticas">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo Documento</label>
                        <select name="tipo_documento" class="form-control">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="PA">Pasaporte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número Documento *</label>
                        <input type="text" name="documento" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control">
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

<!-- MODAL EDITAR PROFESOR -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Profesor</h3>
            <button class="modal-close" onclick="closeModal('modal-editar')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Código *</label>
                        <input type="text" name="codigo" id="edit_codigo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Especialidad</label>
                        <input type="text" name="especialidad" id="edit_especialidad" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo Documento</label>
                        <select name="tipo_documento" id="edit_tipo_documento" class="form-control">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="PA">Pasaporte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número Documento *</label>
                        <input type="text" name="documento" id="edit_documento" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="edit_fecha_nacimiento" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" id="edit_direccion" class="form-control">
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
    document.getElementById('edit_codigo').value = data.codigo;
    document.getElementById('edit_nombre').value = data.nombre;
    document.getElementById('edit_apellido').value = data.apellido;
    document.getElementById('edit_tipo_documento').value = data.tipo_documento;
    document.getElementById('edit_documento').value = data.documento;
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('edit_telefono').value = data.telefono || '';
    document.getElementById('edit_direccion').value = data.direccion || '';
    document.getElementById('edit_especialidad').value = data.especialidad || '';
    document.getElementById('edit_fecha_nacimiento').value = data.fecha_nacimiento || '';
    openModal('modal-editar');
}
</script>

<?php require_once 'includes/footer.php'; ?>
