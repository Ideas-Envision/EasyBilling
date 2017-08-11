<?Php

class systemBillingController extends IdEnController
	{
    
		public function __construct(){
            
                parent::__construct();
            
                $this->vDosingWrenchKey = '9rCB7Sv4X29d)5k7N%3ab89p-3(5[A';
			}     
    
		public function index(){
                $this->vView->visualizar('index');
			}
    
		public function dataBilling(){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
                    $vNumAutorization = (string) $_POST['vNumAutorization'];
                    $vNumBilling = (string) $_POST['vNumBilling'];
                    $vIDClient = (string) $_POST['vIDClient'];
                    $vDateTransactionBilling = (string) $_POST['vDateTransactionBilling'];
                    $vAmountBilling = (string) $_POST['vAmountBilling'];
                    $vDosingWrenchKey = (string) $_POST['vDosingWrenchKey'];


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
                    $vFiveDigitVerhoeffNumber = $this->separateDigitsAndAddNumber($this->fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling), 1);
                    echo $this->getDosingWrenchKeyString($vFiveDigitVerhoeffNumber);
                }            
			}
    
		public function algorithmVerhoeff($vNumber, $vNumberLoop){
				/* CARGA LIBRERIAS */
				$this->getLibrary('verhoeff');
				$this->vNumberVerhoeff = new Verhoeff;
            
                $vNumber = (string) $vNumber;
                $vNumberLoop = (float) $vNumberLoop;
            
                /* NUMERO VERHOEFF */
                $vValor = '';
                for($i = 0; $i < $vNumberLoop; $i++){
                    $vValor = $vNumber.$this->vNumberVerhoeff->calc($vNumber);
                    $vNumber = $vValor;
                }
            
                return $vNumber;
			}
    
		public function fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling){
            
                $vNumBilling = (float) $this->algorithmVerhoeff($vNumBilling, 2);
                $vIDClient = (float) $this->algorithmVerhoeff($vIDClient, 2);
                $vDateTransactionBilling = (float) $this->algorithmVerhoeff($vDateTransactionBilling, 2);
                $vAmountBilling = (float) $this->algorithmVerhoeff($vAmountBilling, 2);
            
                $vFiveDigitVerhoeffNumber = $vNumBilling + $vIDClient + $vDateTransactionBilling + $vAmountBilling;
            
                return substr($this->algorithmVerhoeff($vFiveDigitVerhoeffNumber,5), -5, 5);
			}
    
		public function getDosingWrenchKeyString($vFiveDigitVerhoeffNumber){
            
                $vFiveDigitVerhoeffNumber = (string) $vFiveDigitVerhoeffNumber;
            
                //$this->$vDosingWrenchKey
            
                return $vFiveDigitVerhoeffNumber;
			}    
    
	}
?>