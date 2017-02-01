<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout(){
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
	/**
	 * Inserta los primeros registros para germinar la base de datos sgpi. 
	 * 
	 * @return Vista con mensaje de resultados
	 */
    public function sembrar_bd(){
        try{
        	DB::transaction(function(){
            	$sql = file_get_contents(base_path().'/inserts.sql');
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                foreach ($statements as $stmt) {
                    DB::statement($stmt);
                }
        	});
            return 'BD germinada, retorno a la aplicación: <a href="/">Inicio</a>';
        }
        catch (Exception $e){
            return 'Error en la germinación de la BD; <br />Codigo: '.$e->getCode().'<br />Mensaje de excepción: '.$e->getMessage();
        }
    }
    
    /**
	 * Trunca la BD
	 * 
	 * @return Vista con mensaje de resultados
	 */    
    public function truncar_bd(){
        try{
            DB::transaction(function(){
                $query = "SELECT Concat('TRUNCATE TABLE ', table_schema,'.',TABLE_NAME, ';') as truncacion ";
                $query .= "FROM INFORMATION_SCHEMA.TABLES where  table_schema in ('sgpi');";
                $resultado = DB::select(DB::raw($query));
                
                if(!$resultado)
                    return 'Sin tablas para truncar';
                
                $statements = ['SET FOREIGN_KEY_CHECKS=0;'];
                
                foreach($resultado as $exp_truncacion)
                    array_push($statements, $exp_truncacion->truncacion);
                    
                array_push($statements, 'SET FOREIGN_KEY_CHECKS=1;');
                
                foreach ($statements as $stmt) {
                    DB::statement($stmt);
                }
            });
            return 'BD truncada, se recomienda germinar BD: <a href="/sembrar_bd">Germinar BD</a>';
        }
        catch (Exception $e){
            return 'Error en la truncación de la BD; <br />Codigo: '.$e->getCode().'<br />Mensaje de excepción: '.$e->getMessage();
        }
    }
    
	
    
}
