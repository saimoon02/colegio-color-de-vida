<?php
$pagina_titulo = 'Calificaciones';
require_once 'includes/header.php';
require_once 'includes/db.php';
$mensaje = '';

$periodos = $pdo->query("SELECT * FROM periodos ORDER BY anio DESC, numero")->fetchAll();
$periodo_activo_id = $pdo->query("SELECT id FROM periodos WHERE activo=1 LIMIT 1")->fetchColumn();
$periodo_filtro = isset($_GET['periodo']) ? (int)$_GET['periodo'] : $periodo_activo_id;
$curso_filtro = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;

// Guardar/actualizar notas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_grades') {
    $periodo_id = (int)$_POST['periodo_id'];
    $curso_id = (int)$_POST['curso_id'];
    $grades = $_POST['grades'] ?? [];

    foreach ($grades as $est_id => $materias) {
        foreach ($materias as $cm_id => $notas) {
            $nota = floatval($notas['nota'] ?? 0);
            $nota2 = floatval($notas['nota2'] ?? 0);
            $nota3 = floatval($notas['nota3'] ?? 0);
            $def = round(($nota * 0.3) + ($nota2 * 0.3) + ($nota3 * 0.4), 2);

            $stmt = $pdo->prepare("INSERT INTO calificaciones (estudiante_id, curso_materia_id, periodo_id, nota, nota2, nota3, definitiva) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE nota=?, nota2=?, nota3=?, definitiva=?");
            $stmt->execute([$est_id, $cm_id, $periodo_id, $nota, $nota2, $nota3, $def, $nota, $nota2, $nota3, $def]);
        }
    }
    $mensaje = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Calificaciones guardadas exitosamente.</div>';
}

$cursos = $pdo->query("SELECT id, nombre FROM cursos WHERE activo=1 ORDER BY nombre")->fetchAll();

// Obtener estudiantes y materias del curso seleccionado
$estudiantes = [];
$materias_curso = [];
if ($curso_filtro > 0) {
    $estudiantes = $pdo->prepare("SELECT id, codigo, nombre, apellido FROM estudiantes WHERE curso_id=? AND activo=1 ORDER BY apellido, nombre");
    $estudiantes->execute([$curso_filtro]);
    $estudiantes = $estudiantes->fetchAll();

    $materias_curso = $pdo->prepare("SELECT cm.id as cm_id, m.nombre as materia_nombre, m.id as materia_id, CONCAT(p.nombre,' ',p.apellido) as profesor FROM curso_materia cm JOIN materias m ON m.id=cm.materia_id LEFT JOIN profesores p ON p.id=cm.profesor_id WHERE cm.curso_id=? ORDER BY m.nombre");
    $materias_curso->execute([$curso_filtro]);
    $materias_curso = $materias_curso->fetchAll();

    // Obtener notas existentes
    $calificaciones = [];
    if ($periodo_filtro) {
        $stmt = $pdo->prepare("SELECT * FROM calificaciones WHERE periodo_id=? AND estudiante_id IN (SELECT id FROM estudiantes WHERE curso_id=?)");
        $stmt->execute([$periodo_filtro, $curso_filtro]);
        foreach ($stmt->fetchAll() as $cal) {
            $calificaciones[$cal['estudiante_id']][$cal['curso_materia_id']] = $cal;
        }
    }
}
?>

<?= $mensaje ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-star"></i> Calificaciones</h2>
    </div>
    <div class="card-body">
        <form method="GET" class="form-row" style="margin-bottom:20px;">
            <div class="form-group">
                <label>Periodo</label>
                <select name="periodo" class="form-control" onchange="this.form.submit()">
                    <?php foreach ($periodos as $per): ?>
                    <option value="<?= $per['id'] ?>" <?= $per['id'] == $periodo_filtro ? 'selected' : '' ?>><?= $per['nombre'] . ' ' . $per['anio'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Curso</label>
                <select name="curso" class="form-control" onchange="this.form.submit()">
                    <option value="0">-- Seleccionar curso --</option>
                    <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $curso_filtro ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($curso_filtro > 0 && !empty($estudiantes)): ?>
        <form method="POST">
            <input type="hidden" name="action" value="save_grades">
            <input type="hidden" name="periodo_id" value="<?= $periodo_filtro ?>">
            <input type="hidden" name="curso_id" value="<?= $curso_filtro ?>">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <?php foreach ($materias_curso as $mc): ?>
                            <th><?= htmlspecialchars($mc['materia_nombre']) ?></th>
                            <?php endforeach; ?>
                            <th>Def.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $est): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($est['apellido'] . ' ' . $est['nombre']) ?></strong></td>
                            <?php
                            $sum_def = 0;
                            $count_m = 0;
                            foreach ($materias_curso as $mc):
                                $cal = $calificaciones[$est['id']][$mc['cm_id']] ?? null;
                                $sum_def += $cal['definitiva'] ?? 0;
                                $count_m++;
                            ?>
                            <td style="min-width:180px;">
                                <div style="display:flex;gap:3px;">
                                    <input type="number" name="grades[<?= $est['id'] ?>][<?= $mc['cm_id'] ?>][nota]" value="<?= $cal['nota'] ?? '' ?>" step="0.01" min="0" max="5" placeholder="N1" style="width:55px;padding:4px;border:1px solid #ddd;border-radius:4px;font-size:0.8rem;">
                                    <input type="number" name="grades[<?= $est['id'] ?>][<?= $mc['cm_id'] ?>][nota2]" value="<?= $cal['nota2'] ?? '' ?>" step="0.01" min="0" max="5" placeholder="N2" style="width:55px;padding:4px;border:1px solid #ddd;border-radius:4px;font-size:0.8rem;">
                                    <input type="number" name="grades[<?= $est['id'] ?>][<?= $mc['cm_id'] ?>][nota3]" value="<?= $cal['nota3'] ?? '' ?>" step="0.01" min="0" max="5" placeholder="N3" style="width:55px;padding:4px;border:1px solid #ddd;border-radius:4px;font-size:0.8rem;">
                                </div>
                            </td>
                            <?php endforeach; ?>
                            <td>
                                <?php $prom = $count_m > 0 ? round($sum_def / $count_m, 2) : 0; ?>
                                <span class="<?= $prom >= 4 ? 'grade-excellent' : ($prom >= 3 ? 'grade-good' : 'grade-low') ?>" style="font-weight:700;"><?= number_format($prom, 2) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:15px;text-align:right;">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Calificaciones</button>
            </div>
        </form>
        <?php elseif ($curso_filtro > 0): ?>
        <div class="alert alert-warning">No hay estudiantes en este curso.</div>
        <?php else: ?>
        <div class="alert alert-info">Seleccione un curso para ver y registrar calificaciones.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
