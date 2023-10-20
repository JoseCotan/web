<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar un empleado</title>
</head>
<body>
    <?php
    require '../auxiliar.php';

    if (!isset($_GET['id'])) {
        return volver_empleados();
    }

    $id = trim($_GET['id']);
    $pdo = conectar();
    $empleado = buscar_empleado_por_id($id, $pdo);

    if (!$empleado) {
        return volver_empleados();
    }

    extract($empleado);

    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $numero = obtener_post('numero');
        $nombre = obtener_post('nombre');
        $apellidos = obtener_post('apellidos');
        $salario = obtener_post('salario');
        $fecha_alta = obtener_post('fecha_alta');

        if (empty($numero)) {
            $errores[] = "El campo Número no puede estar vacío.";
        }
        if (empty($nombre)) {
            $errores[] = "El campo Nombre no puede estar vacío.";
        }
        if (empty($apellidos)) {
            $errores[] = "El campo Apellidos no puede estar vacío.";
        }
        if (!is_numeric($salario) || $salario < 0) {
            $errores[] = "El salario debe ser un número mayor o igual a cero.";
        }

        if (empty($errores)) {
            $sent = $pdo->prepare('UPDATE empleados
                                  SET numero = :numero,
                                      nombre = :nombre,
                                      apellidos = :apellidos,
                                      salario = :salario,
                                      fecha_alta = :fecha_alta
                                WHERE id = :id');
            $sent->execute([
                ':numero' => $numero,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':salario' => $salario,
                ':fecha_alta' => $fecha_alta,
                ':id' => $id,
            ]);
            header("Location: /empleados/index.php");
            exit();
        }
    }
    ?>
    <?php if (!empty($errores)) : ?>
        <ul>
            <?php foreach ($errores as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <form action="" method="post">
        <label for="numero">Número</label>
        <input type="text" name="numero" id="numero" value="<?= $numero ?>"><br>
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>"><br>
        <label for="apellidos">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" value="<?= $apellidos ?>"><br>
        <label for="salario">Salario</label>
        <input type="text" name="salario" id="salario" value="<?= $salario ?>"><br>
        <label for="fecha_alta">Fecha alta</label>
        <input type="text" name="fecha_alta" id="fecha_alta" value="<?= $fecha_alta ?>"><br>
        <button type="submit">Modificar</button>
        <a href="/empleados/index.php">Cancelar</a>
    </form>
</body>
</html>