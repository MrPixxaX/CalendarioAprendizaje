<?php

$pdo = new PDO('mysql:host=localhost;dbname=sistema', 'root', '');


$accion = (isset($_GET['accion'])) ? $_GET['accion'] : 'leer';
switch ($accion) {
    case 'agregar':

        $sentenciaSQL = $pdo->prepare("INSERT INTO eventos(title,description,color,textColor,start,end)
        VALUES(:title,:description,:color,:textColor,:start,:end)");

        $respuesta = $sentenciaSQL->execute(array(
            "title" => $_POST['title'],
            "description" => $_POST['description'],
            "color" => $_POST['color'],
            "textColor" => $_POST['textColor'],
            "start" => $_POST['start'],
            "end" => $_POST['end']
        ));

        
        echo json_encode($respuesta);
        break;
    case "eliminar":
        // echo "Instrucción eliminar";
        $respuesta = false;
        if (isset($_POST["id"])) {
            $sentenciaSQL = $pdo->prepare("DELETE FROM eventos WHERE id=:id");
            $respuesta = $sentenciaSQL->execute(array("id" => $_POST["id"]));
        }
        echo json_encode($respuesta);
        break;
    case "modificar":
        $sentenciaSQL = $pdo->prepare("UPDATE eventos 
        set title=:title,description=:description,color=:color,textColor=:textColor,start=:start,end=:end WHERE id=:id");

        $respuesta = $sentenciaSQL->execute(array(
            "id" => $_POST['id'],
            "title" => $_POST['title'],
            "description" => $_POST['description'],
            "color" => $_POST['color'],
            "textColor" => $_POST['textColor'],
            "start" => $_POST['start'],
            "end" => $_POST['end']
        ));
        echo json_encode($respuesta);
        break;
    default:
        //seleccionar los eventos del calendario
        $setenciaSQL = $pdo->prepare("SELECT * FROM eventos");
        $setenciaSQL->execute();

        $resultado = $setenciaSQL->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultado);
        break;
}
