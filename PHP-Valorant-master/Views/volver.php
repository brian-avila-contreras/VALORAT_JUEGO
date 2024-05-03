<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Botón Volver</title>
    <link rel="stylesheet" href="styles.css">
    <style>
.btn-volver {
    position: absolute;
    top: 10px;
    right: 25px;
    background-color: rgb(238, 90, 90);
    color: white;
    border: 2px solid rgb(238, 90, 90);
    padding: 10px 20px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-volver:hover {
    background-color: white;
    color: rgb(238, 90, 90);
}

    </style>
</head>

<body>
    <a href="javascript:history.back()" class="btn btn-danger btn-volver">Volver</a>
</body>

</html>