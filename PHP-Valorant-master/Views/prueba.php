<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Video de Fondo</title>
<style>
  body {
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: Arial, sans-serif; /* Selecciona una fuente legible */
  }
  
  #video-background {
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -100;
  }
  
  #content {
    position: relative;
    z-index: 1;
    color: white; /* Color del texto sobre el video */
    padding: 20px; /* Añade un espacio alrededor del contenido */
  }
</style>
</head>
<body>

<video autoplay loop muted id="video-background">
  <source src="videoclove.mp4" type="video/mp4">
  Tu navegador no soporta videos HTML5.
</video>

<div id="content">
  <!-- Contenido de tu página aquí -->
  <h1>Bienvenido</h1>
  <p>Este es un ejemplo de cómo agregar un video como fondo del body.</p>
</div>

</body>
</html>
