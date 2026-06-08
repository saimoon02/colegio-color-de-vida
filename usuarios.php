<?php
$pagina_titulo = 'Usuarios';
require_once 'includes/header.php';
require_once 'includes/db.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email'] ?? '');
    $usuario = trim($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol_id = (int)$_POST['rol_id'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, usuario, password, rol_id) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$nombre, $apellido, $email, $usuario, $password, $rol_id]);
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Usuario creado.</div>';
}

if (isset($_GET['delete'])) {
    $pdo->prepare("UPDATE usuarios SET activo=0 WHERE id=?")->execute([(int)$_GET['delete']]);
    $mensaje = '<div class="alert alert-info"><i class="fas fa-info-circle"></i> Usuario desactivado.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email'] ?? '');
    $usuario = trim($_POST['usuario']);
    $rol_id = (int)$_POST['rol_id'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=?, usuario=?, password=?, rol_id=? WHERE id=?")->execute([$nombre, $apellido, $email, $usuario, $password, $rol_id, $id]);
    } else {
        $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=?, usuario=?, rol_id=? WHERE id=?")->execute([$nombre, $apellido, $email, $usuario, $rol_id, $id]);
    }
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Usuario actualizado.</div>';
}

$usuarios = $pdo->query("SELECT u.*, r.nombre as rol_nombre FROM usuarios u JOIN roles r ON r.id=u.rol_id WHERE u.activo=1 ORDER BY u.nombre")->fetchAll();
$roles = $pdo->query("SELECT * FROM roles ORDER BY id")->fetchAll();
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users-cog"></i> Usuarios del Sistema (<?= count($usuarios) ?>)</h2>
        <button class="btn btn-success btn-sm" onclick="openModal('modal-nuevo')">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
                        <td><strong><?= htmlspecialchars($u['usuario']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                        <td><span class="badge badge-<?= $u['rol_nombre'] === 'admin' ? 'danger' : ($u['rol_nombre'] === 'docente' ? 'info' : 'success') ?>"><?= $u['rol_nombre'] ?></span></td>
                        <td class="actions">
                            <button class="btn btn-sm btn-info" onclick='openEditModal(<?= json_encode($u) ?>)' title="Editar"><i class="fas fa-edit"></i></button>
                            <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                            <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar este usuario?')" title="Desactivar"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVO USUARIO -->
<div class="modal-overlay" id="modal-nuevo">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Nuevo Usuario</h3>
            <button class="modal-close" onclick="closeModal('modal-nuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
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
                        <label>Usuario *</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Contraseña *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="rol_id" class="form-control">
                            <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['descripcion']) ?></option>
                            <?php endforeach; ?>
                        </select>
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

<!-- MODAL EDITAR USUARIO -->
<div class="modal-overlay" id="modal-editar">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Usuario</h3>
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
                        <label>Apellido *</label>
                        <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Usuario *</label>
                        <input type="text" name="usuario" id="edit_usuario" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nueva Contraseña (dejar vacía para no cambiar)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="rol_id" id="edit_rol_id" class="form-control">
                            <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['descripcion']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
    document.getElementById('edit_apellido').value = data.apellido;
    document.getElementById('edit_usuario').value = data.usuario;
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('edit_rol_id').value = data.rol_id;
    openModal('modal-editar');
}
</script>

<?php require_once 'includes/footer.php'; ?>
