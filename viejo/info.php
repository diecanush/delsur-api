<?php
    
    function rutas(){
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        //OBTENER EL METODO HTTP DE LA PETICION
        $metodo = $_SERVER['REQUEST_METHOD'];

        //echo 'Método: '.$metodo.'<br>';
        
        //DESARMAR LA RUTA DE LA URL
        if(isset($_SERVER['PATH_INFO'])){
            $ruta = explode('/',$_SERVER['PATH_INFO']);
            $tabla = $ruta[1];
            if(isset($ruta[2])){
                $registro = $ruta[2];
                echo ($tabla.'->'.$registro);

                if($metodo=='GET'){
                    //echo " (pide un get al registro $registro de la tabla $tabla)<br>";
                    require('getRegistro.php');
                    echo getRegistro($tabla,$registro);
                }

    


                if($metodo=='POST'){

                    $datos = json_decode(file_get_contents("php://input"));
                    //var_dump($datos);

                    if(isset($datos->_method) && $datos->_method === 'DELETE'){
                        //echo "quiere borrar";
                        
                        require('borrarRegistro.php');
                        
                        borrarRegistro($tabla, $registro);
                    }else{
                        //echo " (quiere modificar)";

                        require_once('actualizarRegistro.php');
                        
                        actualizarRegistro($tabla,$registro,$datos);

                    }                

                }


            }else{

                if($metodo == 'GET'){

                    require('getTabla.php');
                    echo getTabla($tabla);

                }

                if($metodo == 'POST'){
                    require_once('grabarRegistro.php');
                    
                    $datos = json_decode(file_get_contents("php://input"));
                    
                    echo json_encode(grabarRegistro($tabla,$datos));

                }
                
            }
        }

        

    }
    rutas();
?>