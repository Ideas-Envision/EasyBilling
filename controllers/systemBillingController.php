<?Php

class systemBillingController extends IdEnController
	{		
		public function __construct(){
                parent::__construct();
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
                    $vAmountBilling = $_POST['vAmountBilling'];
                    $vDosingWrenchKey = $_POST['vDosingWrenchKey'];


                    echo 'Número de Autorización: '.$vNumAutorization.'<br/>';
                    echo 'Número de Factura: '.$vNumBilling.'<br/>';
                    echo 'NIT / CI del Cliente: '.$vIDClient.'<br/>';
                    echo 'Fecha de la Transacción: '.$vDateTransactionBilling.'<br/>';
                    echo 'Monto de la Transacción: '.$vAmountBilling.'<br/>';
                    echo 'Llave de Dosificación: '.$vDosingWrenchKey.'<br/>';
                }            
			}    
	}
?>