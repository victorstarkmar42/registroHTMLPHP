<?php
class DB{
    protected static $con;
    private function __construct(){
        try{
            self::$con = new PDO(
                'mysql:charset=utf8mb4; process.env.HOST=; process.env.PORT; process.env.BDNAME',
                ' process.env.USER',' process.env.PASS');
                self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$con->setAttribute(PDO::ATTR_PERSISTENT,false);
        }
        catch(PDOException $e){
            echo "No hemos podido conectar con la base de datos";
            exit;
        }
    }

    public static function getConn(){
        if(!self::$con){
            new DB();
        }
        return self::$con;
    }

}

$con = DB::getConn();


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    header("Content-Type: application/json");
    $array_devolver=[];
    $email = strtolower($_POST['email']);
    $name = strtolower($_POST['name']);
    $phone = strtolower($_POST['phone']);
    // comprobar si el user existe 
    $buscar_user = $con->prepare("SELECT * FROM usuarios WHERE email = '$email' LIMIT 1");
    $buscar_user->bindParam(':email', $email, PDO::PARAM_STR);
    $buscar_user->execute();

    if($buscar_user->rowCount() == 1){
        // Existe
        $array_devolver['error'] = "Este mail ya existe";
        $array_devolver['is_login']= false;
    }else{
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $nuevo_user = $con->prepare("INSERT INTO users (email, password,name,phone) VALUES(:email, :password, :name, :phone)");
        $nuevo_user->bindParam(':email', $email, PDO::PARAM_STR);
        $nuevo_user->bindParam(':password', $password, PDO::PARAM_STR);
        $nuevo_user->bindParam(':name', $name, PDO::PARAM_STR);
        $nuevo_user->bindParam(':phone', $phone, PDO::PARAM_STR);
        $nuevo_user->execute();

        $user_id = $con->lastInsertId();
        $_SESSION['user_id']= (int) $user_id;
        $array_devolver['redirect']= ''; 
        $array_devolver['is_login']= true;
    }

    echo json_encode($array_devolver);

}else{
    exit("Fuera de aquí");
}


?>