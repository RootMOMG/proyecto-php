<?php
if(isset($_POST)) {
    require_once 'includes/conexion.php';
    // Conexion a BD
    if(!isset($_SESSION)) {
        session_start();
    }
    // Recoger los valores del formulario de registro
    $nombre = $_POST['nombre'] ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = $_POST['apellidos'] ? mysqli_real_escape_string($db, $_POST['apellidos']) : false;
    $email = $_POST['email'] ? mysqli_real_escape_string($db, $_POST['email']) : false;
    $password = $_POST['password'] ? mysqli_real_escape_string($db, $_POST['password']) : false;
    // Array de errores
    $errores = array();

    // Validar los datos antes de guardarlos en la base de datos
    // Validar campo nombre
    if(!empty($nombre) && !is_numeric($nombre) && !preg_match('/[0-9]/', $nombre)) {
        $nombre_validado = true;
    } else {
        $nombre_validado = false;
        $errores['nombre'] = "El nombre no es valido";
    }
    // Validar campo apellidos
    if(!empty($apellidos) && !is_numeric($apellidos) && !preg_match('/[0-9]/', $apellidos)) {
        $apellidos_validado = true;
    } else {
        $apellidos_validado = false;
        $errores['apellidos'] = "Los apellidos no son validos";
    }
    // Validar campo email
    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_validado = true;
    } else {
        $email_validado = false;
        $errores['email'] = "El email no es valido";
    }
    // Validar campo password
    if(!empty($password)) {
        $password_validado = true;
    } else {
        $password_validado = false;
        $errores['password'] = "El password esta vacío";
    }

    $guardar_usuario = false;
    if(count($errores) == 0) {
        $guardar_usuario = true;
        // Cifrar la contraseña
        $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]);
        // Insertar usuario en la tabla usuarios de la BBDD
        $sql = "INSERT INTO USUARIOS VALUES (null, '$nombre', '$apellidos', '$email', '$password_segura', CURDATE())";
        $insert = mysqli_query($db, $sql);
        if($insert) {
            $_SESSION['completado'] = "El registro se ha completado con exito";
        } else {
            $_SESSION['errores']['general'] = "Fallo al guardar el usuario";
        }
    } else {
        $_SESSION['errores'] = $errores;
    }    
}
header('Location: index.php');