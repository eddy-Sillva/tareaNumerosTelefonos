<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea de las ladas</title>
    <link rel="stylesheet" href="bootstrap-4.5.0-dist/css/bootstrap.min.css">
    <script src="bootstrap-4.5.0-dist/js/bootstrap.min.js"></script>

    <style>
    </style>
</head>
<body>
    <div class="container">
    <form action="index.php" method="POST">
        <div class="form-group">
          <label for="nombre">Nombre:</label>
          <input type="text" name="nombre" class="form-control" id="nombre" require>
        </div>
        <div class="form-group">
          <label for="telefono">Telefono</label>
          <input type="text" name="telefono" class="form-control" id="telefono" require>
        </div>
        <button type="submit" class="btn btn-primary btn-block">ACEPTAR</button>
      </form>
    </div>
</body>

<?php
if(isset($_POST['nombre']) || isset($_POST['telefono'])){
  $nombre=$_POST['nombre'];
  $telefono=$_POST['telefono'];
    if(telefono($telefono)){
      $valores=lada($telefono);
      if($valores!=null){
        echo "Nos pondremos pronto en contacto con ustedes: ".$nombre." Al numero: ".$telefono." Perteneciente a: ".$valores["NOMBRE_CIUDAD"];
      }else{
        echo "Lo sentimos: ".$nombre." Parece ser que tu numero no es valido por favor verificalo: ".$telefono;
      }
    }else{
      echo "Hola: ".$nombre." Parece ser que escribiste mal tu numero: ".$telefono;
    }
}else{
}

function lada($telefono){
  $data = file_get_contents("ladas.json");
  $valorLadas = json_decode($data, true);
foreach ($valorLadas as $ladas) {
  $numeros=7;
  if(strlen(trim($ladas["lada"])) == 2){
    ++$numeros;
  }
  $patron = "/^".trim($ladas["lada"])."[0-9]{".+$numeros."}+$/";
  if(preg_match($patron, $telefono) == true){
    return $ladas;
  }
}
  return null;
}

function telefono($dato){
  $patron = "/^[0-9]{10}+$/";
	if(preg_match($patron, $dato)){
		return true;
	}else{
		return false;
	}
}

function convertirJSON(){
  $json_data = file_get_contents('ladas.txt');
// separamos el fichero en lineas (\n)
  $json_data = preg_split( "/\n/", $json_data );
// para cada elemento
  for ($x = 0; $x < count($json_data); $x++) {
    // lo separamos por tabulador (\t)
    $dupla = preg_split("/\t/", $json_data[$x], -1, PREG_SPLIT_NO_EMPTY);
    // sustituimos el elemento por un array asociativo similar
    $json_data[$x] = array(
        "NOMBRE_CIUDAD" => $dupla[0],
        "lada" => $dupla[1]
    );
  }
  $json = json_encode($json_data);
  $bytes = file_put_contents("archivo.json", $json); 
}
?>
</html>