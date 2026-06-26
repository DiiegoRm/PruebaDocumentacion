<?php

include_once "../../includes/database.php";
include_once "../../includes/static.inc.php";
include_once "validation_controller.php";

class User{

    private $documento;
    private $login;
    private $email;
    private $nombre;
    private $grupo;
    private $recobre;
    
    public function validate_user_data($parametros){
        $validation = new Validation();
        $error      = false;
        $msg        = "";

        $this->documento = $parametros->{"documento"};
        if(!$validation->check_empty($this->documento) or $validation->only_numbers($this->documento) or $this->documento > 2147483647){
            $error = true;
            $msg   .= "Documento invalido, solo se aceptan numeros o se encuentra fuera del rango permitido \t";
        }

        $this->login = $validation->clean_input($parametros->{"login"});
        if(!$validation->check_empty($this->login) or $validation->only_letters($this->login)){
            $error = true;
            $msg   .= "Login invalido, No se aceptan numeros \t";
        }

        $this->email = $validation->clean_input($parametros->{"email"});
        if(!$validation->check_empty($this->email) or $validation->check_email($this->email)){
            $error = true;
            $msg   .= "Correo invalido, valide el formato";
        }

        $this->nombre = $validation->clean_input($parametros->{"nombre"});
        if(!$validation->check_empty($this->nombre) or $validation->only_letters($this->nombre)){
            $error = true;
            $msg   .= "Nombre invalido, No se aceptan numeros";
        }

        $this->grupo = $validation->clean_input($parametros->{"grupo"});
        if(!$validation->check_empty($this->grupo) or $validation->only_numbers($this->grupo)){
            $error = true;
            $msg   .= "Grupo invalido, solo se acepta el id";
        }

        $this->recobre = $parametros->{"recobre"};
        if(!$this->recobre)
            $this->recobre = 0;

        if(!$validation->check_empty($this->recobre) or $validation->only_boolean($this->recobre)){
            $error = true;
            $msg   .= "Activacion Recobre invalido, solo se acepta 1 - 0";
        }

        return array(
            "error" => $error,
            "msg"   => $msg
        );
    }
    
    public function create($parametros){   
        
        $validation = $this->validate_user_data($parametros);
        
        if($validation['error']){
            return array(
                'estado'  => 0, 
                'mensaje' => $validation['msg']
            );
        }

        $check = mysqli_fetch_array(db_query("SELECT id FROM usuarios WHERE id = '$this->documento' or login = '$this->login'"));
        if(count($check) > 0)
            return array(
                'estado'  => 0, 
                'mensaje' => 'usuario ya existe en la base de datos'
            );

        
        $sql = db_query("INSERT INTO usuarios (id, login, email, nombre, idgrupo, active, recobre, user_ldap) VALUES ('$this->documento', '$this->login', '$this->email', '$this->nombre', '$this->grupo', 'Si', '$this->recobre', '$this->login')", true);
        if(!$sql){
            var_dump("INSERT INTO usuarios (id, login, email, nombre, idgrupo, active, recobre, user_ldap) VALUES ('$this->documento', '$this->login', '$this->email', '$this->nombre', '$this->grupo', 'Si', '$this->recobre', '$this->login')");die();
            return array(
                'estado'  => 0, 
                'mensaje' => 'error en la conexion, contacte al administrador'
            );
        }

        return array(
                'estado'  => 1, 
                'mensaje' => 'usuario creado con exito'
        );
        
    }
    public function update($parametros){

        $validation = $this->validate_user_data($parametros);
        
        if($validation['error']){
            return array(
                'estado'  => 0, 
                'mensaje' => $validation['msg']
            );
        }

        $check = mysqli_fetch_array(db_query("SELECT id FROM usuarios WHERE id = '$this->documento'"));
        if(count($check) <= 0)
            return array(
                'estado'  => 0, 
                'mensaje' => 'usuario no existe en la base de datos'
            );
        
        $sql = db_query("UPDATE usuarios SET login = '$this->login', email = '$this->email', nombre = '$this->nombre', idgrupo = '$this->grupo', recobre = '$this->recobre', user_ldap = '$this->login', modify_date = NOW() WHERE id = '$this->documento'", true);
        if(!$sql){
            return array(
                'estado'  => 0, 
                'mensaje' => 'error en la conexion, contacte al administrador'
            );
        }

        return array(
                'estado'  => 1, 
                'mensaje' => 'usuario actualizado con exito'
        );
    }

    public function enable($parametros){
        
        $validation = new Validation();
        $error      = false;

        $documento = $parametros->{"documento"};
        if(!$validation->check_empty($documento) or $validation->only_numbers($documento) or $this->documento > 2147483647)
            $error = true;

        if($error){
            return array(
                'estado'  => 0, 
                'mensaje' => 'Documento invalido, solo se aceptan numeros o se encuentra fuera del rango permitido'
            );
        }

        $check = mysqli_fetch_array(db_query("SELECT id FROM usuarios WHERE id = '$documento'"));
        if(count($check) <= 0)
            return array(
                'estado'  => 0, 
                'mensaje' => 'usuario no existe en la base de datos'
            );
        
        $sql = db_query("UPDATE usuarios SET active = 'Si' WHERE id = '$documento'", true);
        if(!$sql){
            return array(
                'estado'  => 0, 
                'mensaje' => 'error en la conexion, contacte al administrador'
            );
        }

        return array(
                'estado'  => 1, 
                'mensaje' => 'usuario Activado con exito'
        ); 
    }

    public function disable($parametros){

        $validation = new Validation();
        $error      = false;

        $documento = $parametros->{"documento"};
        if(!$validation->check_empty($documento) or $validation->only_numbers($documento) or $this->documento > 2147483647)
            $error = true;

        if($error){
            return array(
                'estado'  => 0, 
                'mensaje' => 'Documento invalido, solo se aceptan numeros o se encuentra fuera del rango permitido'
            );
        }

        $check = mysqli_fetch_array(db_query("SELECT id FROM usuarios WHERE id = '$documento'"));
        if(count($check) <= 0)
            return array(
                'estado'  => 0, 
                'mensaje' => 'usuario no existe en la base de datos'
            );
        $sql = db_query("UPDATE usuarios SET active = 'No' WHERE id = '$documento'", true);
        if(!$sql){
            return array(
                'estado'  => 0, 
                'mensaje' => 'error en la conexion, contacte al administrador'
            );
        }

        return array(
                'estado'  => 1, 
                'mensaje' => 'usuario Desactivado con exito'
        ); 
    }
    
    public function read($parametros){
        $validation = new Validation();

        $error = false;

        $documento = $parametros->{"documento"};
        if(!$validation->check_empty($documento) or $validation->only_numbers($documento) or $this->documento > 2147483647)
            $error = true;

        if($error){
            return array(
                'estado'  => 0, 
                'mensaje' => 'Documento invalido, solo se aceptan numeros o se encuentra fuera del rango permitido'
            );
        }
        
        $sql = db_query("SELECT * FROM usuarios WHERE id = '$documento'", true);
        while($row = mysqli_fetch_array($sql)) {
            $response = array(
                'documento'           => $row['id'], 
                'login'               => $row['login'],
                'email'               => $row['email'],
                'nombre'              => $row['nombre'],
                'grupo'               => $row['idgrupo'],
                'recobre'             => $row['recobre'],
                'ultima_modificacion' => $row['modify_date']
            ); 
        }
        return $response;
    }

}
?>
