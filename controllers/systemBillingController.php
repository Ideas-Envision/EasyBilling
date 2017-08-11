<?Php

class systemBillingController extends IdEnController
	{
        //private $vDosingWrenchKey = '9rCB7Sv4X29d)5k7N%3ab89p-3(5[A';
        //private $vDosingWrenchKey = 'A3Fs4s$)2cvD(eY667A5C4A2rsdf53kw9654E2B23s24df35F5';
        private $vDosingWrenchKey = '442F3w5AggG7644D737asd4BH5677sasdL4%44643(3C3674F4';
    
		public function __construct(){
            
                parent::__construct();
			}
    
        private function getDosingWrenchKey(){
            return $this->vDosingWrenchKey;
            }    
    
		public function index(){
                $this->vView->visualizar('index');
			}
    
		public function dataBilling(){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
                    $vNumAutorization = $_POST['vNumAutorization'];
                    $vNumBilling = $_POST['vNumBilling'];
                    $vIDClient = $_POST['vIDClient'];
                    $vDateTransactionBilling = $_POST['vDateTransactionBilling'];
                    $vAmountBilling = (int) $_POST['vAmountBilling'];
                    $vDosingWrenchKey = $_POST['vDosingWrenchKey'];
                    
                    /*$vNumAutorization = $vNumAutorization;
                    $vNumBilling = $vNumBilling;
                    $vIDClient = $vIDClient;
                    $vDateTransactionBilling = $vDateTransactionBilling;*/
                    //$vAmountBilling = round(vAmountBilling);
                    /*$vDosingWrenchKey = $vDosingWrenchKey;     */               


                    /*
                    echo 'Número de Autorización: '.$vNumAutorization.'<br/>';
                    echo 'Número de Factura: '.$vNumBilling.'<br/>';
                    echo 'NIT / CI del Cliente: '.$vIDClient.'<br/>';
                    echo 'Fecha de la Transacción: '.$vDateTransactionBilling.'<br/>';
                    echo 'Monto de la Transacción: '.$vAmountBilling.'<br/>';
                    echo 'Llave de Dosificación: '.$vDosingWrenchKey.'<br/>';
                    echo 'Verhoeff Factura: '.$this->fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling).'<br/>';
                    echo 'Separate: '.$this->separateDigitsAndAddNumber($this->fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling), 1);
                    */
                    
                    /* GENERA LOS 5 NUMEROS DE VERHOEFF */
                    $vFiveDigitVerhoeffNumber = $this->fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling);
                        
                    /* GENERA LOS 5 NUMEROS DE VERHOEFF INCREMENTADOS EN 1 POR SEPARADO */
                    $vFiveDigitVerhoeffSeparateDigits = $this->separateDigitsAndAddNumber($vFiveDigitVerhoeffNumber, 1);
                    
                    /* GENERA LA CADENA CONCATENADA EXTRAIDA DE LA LLAVE DE DOSIFICACIÓN MÁS LOS 5 NUMEROS DE VERHOEFF INCREMENTADOS EN 1  */
                    $vStringDosingWrenchKey = $this->getDosingWrenchKeyString($vNumAutorization, $vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling, $vFiveDigitVerhoeffSeparateDigits);
                    
                    /* CADENA ALLEGEDRC4 */
                    $vStringAllegedRC4 = $this->algorithmAllegedRC4($vStringDosingWrenchKey, $this->getDosingWrenchKey(), $vFiveDigitVerhoeffNumber);
                    
                    /* APLICACIÓN DE BASE64 A CADENA ALLEGEDRC4 */
                    $vStringBase64 = $this->algorithmBase64($vStringAllegedRC4);
                    
                    /* GENERACIÓN DEL CÓDIGO DE CONTROL */
                    $vControlCodeString = $this->getControlCode($vStringBase64, $this->getDosingWrenchKey(), $vFiveDigitVerhoeffNumber);
                    
                    //echo $vAmountBilling.'-'.$vFiveDigitVerhoeffNumber.'-'.$vFiveDigitVerhoeffSeparateDigits.'-'.$vStringDosingWrenchKey.'-'.$vStringAllegedRC4.'-'.$vStringBase64.'-'.$vControlCodeString;
                    echo $vControlCodeString;
                }            
			}
    
		public function algorithmVerhoeff($vNumber, $vNumberLoop){
				/* CARGA LIBRERIAS */
				$this->getLibrary('verhoeff');
				$this->vNumberVerhoeff = new Verhoeff;
            
                $vNumber = (float) $vNumber;
                $vNumberLoop = (float) $vNumberLoop;
            
                /* NUMERO VERHOEFF */
                $vValor = '';
                for($i = 0; $i < $vNumberLoop; $i++){
                    $vValor = $vNumber.$this->vNumberVerhoeff->calc($vNumber);
                    $vNumber = $vValor;
                }
            
                return $vNumber;
			}
    
		public function algorithmAllegedRC4($vStringDosingWrenchKey, $vDosingWrenchKey, $vFiveDigitVerhoeffNumber){
				/* CARGA LIBRERIAS */
				$this->getLibrary('allegedRC4');
				$this->vAllegedRC4String = new AllegedRC4;
            
                $vStringDosingWrenchKey = (string) $vStringDosingWrenchKey;
                $vDosingWrenchKey = (string) $vDosingWrenchKey;
                $vFiveDigitVerhoeffNumber = (string) $vFiveDigitVerhoeffNumber;
            
                $vStringAllegedRC4 = $this->vAllegedRC4String->encryptMessageRC4($vStringDosingWrenchKey, $vDosingWrenchKey.$vFiveDigitVerhoeffNumber, true);
            
            
                $vFiveDigitVerhoeffSeparateDigits = $this->separateDigitsAndAddNumber($vFiveDigitVerhoeffNumber, 1);
                $numbers = str_split($vFiveDigitVerhoeffSeparateDigits);
                $chars = str_split($vStringAllegedRC4);
            
                $totalAmount=0;
                $sp1=0; $sp2=0; $sp3=0; $sp4=0; $sp5=0;          
                $tmp=1;
            
                for($i=0; $i<strlen($vStringAllegedRC4);$i++){
                    $totalAmount += ord($chars[$i]);
                    switch($tmp){
                        case 1: $sp1 += ord($chars[$i]); break;
                        case 2: $sp2 += ord($chars[$i]); break;
                        case 3: $sp3 += ord($chars[$i]); break;
                        case 4: $sp4 += ord($chars[$i]); break;
                        case 5: $sp5 += ord($chars[$i]); break;
                    }            
                    $tmp = ($tmp<5)?$tmp+1:1;
                }
            
                $tmp1 = floor($totalAmount*$sp1/$numbers[0]);
                $tmp2 = floor($totalAmount*$sp2/$numbers[1]);
                $tmp3 = floor($totalAmount*$sp3/$numbers[2]);
                $tmp4 = floor($totalAmount*$sp4/$numbers[3]);
                $tmp5 = floor($totalAmount*$sp5/$numbers[4]);

                $sumProduct = $tmp1 + $tmp2 + $tmp3 + $tmp4 + $tmp5;             
                    
                return $sumProduct;
			}

		public function algorithmBase64($vNumber){
				/* CARGA LIBRERIAS */
				$this->getLibrary('base64');
				$this->vBase64 = new Base64;
            
                $vNumber = (string) $vNumber;
                
                $vValor = $this->vBase64->convert($vNumber);

                return $vValor;
			}
    
		public function getControlCode($vStringBase64, $vDosingWrenchKey, $vFiveDigitVerhoeffNumber){
            
				/* CARGA LIBRERIAS */
				$this->getLibrary('allegedRC4');
				$this->vAllegedRC4String = new AllegedRC4;
            
                $vStringBase64 = (string) $vStringBase64;
                $vDosingWrenchKey = (string) $vDosingWrenchKey;
                $vFiveDigitVerhoeffNumber = (string) $vFiveDigitVerhoeffNumber;
            
                $vControlCodeString = $this->vAllegedRC4String->encryptMessageRC4($vStringBase64, $vDosingWrenchKey.$vFiveDigitVerhoeffNumber, true);

                return $vControlCodeString;
			}    
    
		public function fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling){
            
                $vNumBilling = (float) $this->algorithmVerhoeff($vNumBilling, 2);
                $vIDClient = (float) $this->algorithmVerhoeff($vIDClient, 2);
                $vDateTransactionBilling = (float) $this->algorithmVerhoeff($vDateTransactionBilling, 2);
                $vAmountBilling = (float) $this->algorithmVerhoeff($vAmountBilling, 2);
            
                $vFiveDigitVerhoeffNumber = $vNumBilling + $vIDClient + $vDateTransactionBilling + $vAmountBilling;
            
                return substr($this->algorithmVerhoeff($vFiveDigitVerhoeffNumber,5), -5, 5);
			}
    
		public function getDosingWrenchKeyString($vNumAutorization, $vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling, $vFiveDigitVerhoeffNumber){
                
                $vNumBilling = (float) $this->algorithmVerhoeff($vNumBilling, 2);
                $vIDClient = (float) $this->algorithmVerhoeff($vIDClient, 2);
                $vDateTransactionBilling = (float) $this->algorithmVerhoeff($vDateTransactionBilling, 2);
                $vAmountBilling = (float) $this->algorithmVerhoeff($vAmountBilling, 2);
            
                $vFiveDigitVerhoeffNumber = (string) $vFiveDigitVerhoeffNumber;
            
                $vArrayFiveDigitVerhoeffNumber[] = array();
                $vArrayStringDosingWrenchKey[] = array();
                $vPositionStringDosingWrenchKey = 0;
            
                $vStringDosingWrenchKey = '';
            
                $x = (string) $vFiveDigitVerhoeffNumber;
                
                for($i = 0;$i < strlen($x); $i++){ 
                    $vArrayFiveDigitVerhoeffNumber[$i] = $x[$i];
                    $vArrayStringDosingWrenchKey[$i] = substr($this->getDosingWrenchKey(), $vPositionStringDosingWrenchKey, $vArrayFiveDigitVerhoeffNumber[$i]);
                    $vPositionStringDosingWrenchKey = $vPositionStringDosingWrenchKey + $x[$i];
                }
                
                $vArrayStringDosingWrenchKey[0] = $vNumAutorization.$vArrayStringDosingWrenchKey[0];
                $vArrayStringDosingWrenchKey[1] = $vNumBilling.$vArrayStringDosingWrenchKey[1];
                $vArrayStringDosingWrenchKey[2] = $vIDClient.$vArrayStringDosingWrenchKey[2];
                $vArrayStringDosingWrenchKey[3] = $vDateTransactionBilling.$vArrayStringDosingWrenchKey[3];
                $vArrayStringDosingWrenchKey[4] = $vAmountBilling.$vArrayStringDosingWrenchKey[4];
                
                $vStringDosingWrenchKey = $vArrayStringDosingWrenchKey[0].$vArrayStringDosingWrenchKey[1].$vArrayStringDosingWrenchKey[2].$vArrayStringDosingWrenchKey[3].$vArrayStringDosingWrenchKey[4];
                //return $vArrayFiveDigitVerhoeffNumber;
                //return print_r($vArrayStringDosingWrenchKey);
                return $vStringDosingWrenchKey;
			}    
    
	}
?>