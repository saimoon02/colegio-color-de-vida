<?php
$pagina_titulo = 'Estudiantes';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_doc = $_POST['tipo_documento'];
    $num_doc = trim($_POST['numero_documento']);
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $fecha_nac = $_POST['fecha_nacimiento'] ?: null;
    $genero = $_POST['genero'] ?: null;
    $acudiente_nombre = trim($_POST['acudiente_nombre'] ?? '');
    $acudiente_telefono = trim($_POST['acudiente_telefono'] ?? '');
    $curso_id = $_POST['curso_id'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO estudiantes (codigo, nombre, apellido, tipo_documento, numero_documento, email, telefono, direccion, fecha_nacimiento, genero, acudiente_nombre, acudiente_telefono, curso_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$codigo, $nombre, $apellido, $tipo_doc, $num_doc, $email, $telefono, $direccion, $fecha_nac, $genero, $acudiente_nombre, $acudiente_telefono, $curso_id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Estudiante registrado exitosamente.</div>';
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("UPDATE estudiantes SET activo=0 WHERE id=?")->execute([$id]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Estudiante desactivado.</div>';
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_doc = $_POST['tipo_documento'];
    $num_doc = trim($_POST['numero_documento']);
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $fecha_nac = $_POST['fecha_nacimiento'] ?: null;
    $genero = $_POST['genero'] ?: null;
    $acudiente_nombre = trim($_POST['acudiente_nombre'] ?? '');
    $acudiente_telefono = trim($_POST['acudiente_telefono'] ?? '');
    $curso_id = $_POST['curso_id'] ?: null;

    $stmt = $pdo->prepare("UPDATE estudiantes SET codigo=?, nombre=?, apellido=?, tipo_documento=?, numero_documento=?, email=?, telefono=?, direccion=?, fecha_nacimiento=?, genero=?, acudiente_nombre=?, acudiente_telefono=?, curso_id=? WHERE id=?");
    $stmt->execute([$codigo, $nombre, $apellido, $tipo_doc, $num_doc, $email, $telefono, $direccion, $fecha_nac, $genero, $acudiente_nombre, $acudiente_telefono, $curso_id, $id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Estudiante actualizado.</div>';
}

// SEARCH
$buscar = trim($_GET['buscar'] ?? '');
$where = $buscar ? "WHERE e.activo=1 AND (e.nombre LIKE ? OR e.apellido LIKE ? OR e.codigo LIKE ? OR e.numero_documento LIKE ?)" : "WHERE e.activo=1";
$params = $buscar ? ["%$buscar%", "%$buscar%", "%$buscar%", "%$buscar%"] : [];

$stmt = $pdo->prepare("SELECT e.*, c.nombre as curso_nombre FROM estudiantes e LEFT JOIN cursos c ON c.id = e.curso_id $where ORDER BY e.apellido, e.nombre");
$stmt->execute($params);
$estudiantes = $stmt->fetchAll();

$cursos = $pdo->query("SELECT id, nombre FROM cursos WHERE activo=1 ORDER BY nombre")->fetchAll();
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-graduate"></i> Lista de Estudiantes (<?= count($estudiantes) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nuevo Estudiante
        </button>
    </div>
    <div class="card-body">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar por nombre, código o documento..." value="<?= htmlspecialchars($buscar) ?>">
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
                        <th>Curso</th>
                        <th>Género</th>
                        <th>Acudiente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $est): ?>
                    <tr>
                        <td><span class="badge badge-primary"><?= $est['codigo'] ?></span></td>
                        <td><?= htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) ?></td>
                        <td><?= $est['tipo_documento'] . ' ' . $est['numero_documento'] ?></td>
                        <td><?= htmlspecialchars($est['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($est['telefono'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($est['curso_nombre'] ?? '<span class="badge badge-warning">Sin curso</span>') ?></td>
                        <td><?= $est['genero'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($est['acudiente_nombre'] ?? '-') ?></td>
                        <td class="actions">
                            <button class="btn btn-sm btn-info" onclick='openEditModal(<?= json_encode($est) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                            <a href="?delete=<?= $est['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar este estudiante?')" title="Desactivar"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVO -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Nuevo Estudiante</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Código *</label>
                        <input type="text" name="codigo" class="form-control" required placeholder="EST-XXX">
                    </div>
                    <div class="form-group">
                        <label>Curso</label>
                        <select name="curso_id" class="form-control">
                            <option value="">-- Sin asignar --</option>
                            <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="RC">Registro Civil</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número Documento *</label>
                        <input type="text" name="numero_documento" class="form-control" required>
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
                        <label>Género</label>
                        <select name="genero" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
                <hr style="margin:15px 0;">
                <h4 style="margin-bottom:10px;">Acudiente</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre Acudiente</label>
                        <input type="text" name="acudiente_nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono Acudiente</label>
                        <input type="text" name="acudiente_telefono" class="form-control">
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

<!-- MODAL EDITAR -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Estudiante</h3>
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
                        <label>Curso</label>
                        <select name="curso_id" id="edit_curso_id" class="form-control">
                            <option value="">-- Sin asignar --</option>
                            <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="RC">Registro Civil</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Número Documento *</label>
                        <input type="text" name="numero_documento" id="edit_numero_documento" class="form-control" required>
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
                        <label>Género</label>
                        <select name="genero" id="edit_genero" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" id="edit_direccion" class="form-control">
                </div>
                <hr style="margin:15px 0;">
                <h4 style="margin-bottom:10px;">Acudiente</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre Acudiente</label>
                        <input type="text" name="acudiente_nombre" id="edit_acudiente_nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono Acudiente</label>
                        <input type="text" name="acudiente_telefono" id="edit_acudiente_telefono" class="form-control">
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
    document.getElementById('edit_numero_documento').value = data.numero_documento;
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('edit_telefono').value = data.telefono || '';
    document.getElementById('edit_direccion').value = data.direccion || '';
    document.getElementById('edit_fecha_nacimiento').value = data.fecha_nacimiento || '';
    document.getElementById('edit_genero').value = data.genero || '';
    document.getElementById('edit_curso_id').value = data.curso_id || '';
    document.getElementById('edit_acudiente_nombre').value = data.acudiente_nombre || '';
    document.getElementById('edit_acudiente_telefono').value = data.acudiente_telefono || '';
    openModal('modal-editar');
}
</script>

<?php require_once 'includes/footer.php'; ?>
