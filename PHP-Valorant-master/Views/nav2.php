<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         .puntos {
            width: 100%;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .confort {
            width: 30%;
            height: 100%;
            padding: 5px;
        }

        .confort img {
            width: 100%;
            height: 100%;

        }

        .elementos {

            width: 25%;
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0px;
            padding: 0px;


        }

        .elementos img {
            width: 35%;
            height: auto;
        }

        .elementos a {
            font-size: 10px;
            color: white;
            text-decoration: none;
        }

        .elementos a:hover {
            font-size: 10px;
            color: white;
            text-decoration: none;
        }


        .confi {
            width: 5%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: -45%;
        }

        .confi img {
            width: 60%;
            height: 40%;
            margin-left: 40%;
            /* Coloca la imagen a la derecha del contenedor */
        }
    </style>
</head>
<body>
<div class="puntos">
        <div class="confort">
            <img src="../img/confrontacion.png" alt="">
        </div>
        <div class="elementos">
            <a href=""><img src="../svg/radioactivo.svg" frameborder="0" scrolling="no"></img> 0/4</a>
            <a href=""><img src="../svg/valorant.svg" frameborder="0" scrolling="no"></img> 3000</a>
            <a href=""><img src="../svg/carta2.svg" frameborder="0" scrolling="no"></img></a>
            <a href=""><img src="../svg/radianita.svg" frameborder="0" scrolling="no"></img> 215</a>
            <a href=""><img src="../svg/kingdom.svg" frameborder="0" scrolling="no"></img> 8000</a>
        </div>
        <div class="confi">
            <a href="#" onclick="confirmarCerrarSesion()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#ffffff">
                    <path fill="none" d="M0 0h24v24H0z"/>
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </a>
        </div>
    </div>

    <script>
        function confirmarCerrarSesion() {
            // Mostrar una alerta con dos botones: Aceptar y Cancelar
            if (confirm("¿Estás seguro de cerrar la sesión?")) {
                // Si el usuario hace clic en Aceptar, redirige a la página de validación de sesión
                window.location.href = "../Config/validarSesion.php?logout=true";
            }
            // Si el usuario hace clic en Cancelar, no hace nada
        }
    </script>
</body>
</html>