<?php

abstract class IdEnController
	{
		protected $vView;
		
		public function __construct()
			{
				$this->vView = new IdEnView(new IdEnRequest);
			}
		
		abstract public function index();/* OBLIGA A TODAS LAS CLASES HEREDADAS DE LA MISMA IMPLEMENTEN UN METODO INDEX CON O SIN CODIGO */
		
		protected function LoadModel($vModel)
			{
				$vModel = $vModel.'Model';
				$vRouteModel = ROOT_APPLICATION.'models'.DIR_SEPARATOR.$vModel.'.php';
				
				if(is_readable($vRouteModel))
					{
						require_once $vRouteModel;
						$vModel = new $vModel;
						return $vModel;
					}
				else
					{
						throw new Exception($vModel.' - No Existe el Modelo!');
						//header('Location: '.BASE_VIEW_URL.'error/model/1005');
						exit;						
					}
			}
        
		protected function getLibrary($vLibrary)
			{
				$vRouteLibrary = ROOT_APPLICATION.'libs'.DIR_SEPARATOR.$vLibrary.'.php';
				
				if(is_readable($vRouteLibrary))
					{
						require_once $vRouteLibrary;
					}
				else
					{
						throw new Exception($vLibrary.' - No Existe la Libreria!');
					}
			}        
		
		protected function redirect($vRoute = FALSE)
			{
				if($vRoute)
					{
						header('Location:'.BASE_VIEW_URL.$vRoute);
						exit;								
					}
				else
					{
						header('Location:'.BASE_VIEW_URL);					
						exit;						
					}
			}
    
        /* BEGIN GLOBAL FUNCTIONS */
        public function stringInverted($vStringNormal){
            $vStringNormal = (string) $vStringNormal;
            $vStringInverted = '';
            
            for ($i=strlen($vStringNormal); $i >= 0; $i--){
              $vStringInverted .= $vStringNormal[$i];
            }
            
            return $vStringInverted;
        }
    
        public function separateDigitsAndAddNumber($vNumberToSeparate, $vNumberToAdd){
            $vNumberToSeparate = (float) $vNumberToSeparate;
            $vNumberToAdd = (float) $vNumberToAdd;
            
            $x = (string) $vNumberToSeparate;
            
            $vNewVal = '';
            
            for($i = 0;$i < strlen($x); $i++){ 
                $vNewVal .= $x[$i] + $vNumberToAdd;
            }
            
            return $vNewVal;
        }
    
        public function roundUp($value){        
            //reemplaza (,) por (.)        
            $value2 = str_replace(',','.',$value);
            //redondea a 0 decimales        
            return round($value2, 0, PHP_ROUND_HALF_UP);
        }

        public function validateNumber($value){
            if(!preg_match('/^[0-9,.]+$/', $value)){
                throw new InvalidArgumentException(sprintf("Error! Valor restringido a número, %s no es un número.",$value));
            }
        }

        public function validateDosageKey($value){
            if(!preg_match('/^[A-Za-z0-9=#()*+-_\@\[\]{}%$]+$/', $value)){
                throw new InvalidArgumentException(sprintf("Error! llave de dosificación,<b> %s </b>contiene caracteres NO permitidos.",$value));
            }
        }    
        /* END GLOBAL FUNCTIONS */
						
	}

?>
