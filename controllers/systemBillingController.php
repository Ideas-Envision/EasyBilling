<?Php

class systemBillingController extends IdEnController
	{
        private $vDosingWrenchKey = 'w[TXnSWw7E-3mna$KLY4$M@_hQhBtNhqKImvvqmhTr]A-j=]WMspNS5NI]gQp%WQ';
    
		public function __construct(){
            
                parent::__construct();
            
				/* BEGIN VALIDATION TIME SESSION USER */
				if(!IdEnSession::getSession(DEFAULT_USER_AUTHENTICATE)){
                        $this->redirect('access');
                } else {
                    IdEnSession::timeSession();
                }
                /* END VALIDATION TIME SESSION USER */            
			}
    
        private function getDosingWrenchKey(){
            return $this->vDosingWrenchKey;
            }    
    
		public function index(){
            
            $this->vView->visualizar('index');
			}
    
		public function dataBilling(){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    
                    $vNumAutorization = (string) $_POST['vNumAutorization'];
                    $vNumBilling = (string) $_POST['vNumBilling'];
                    $vIDClient = (string) $_POST['vIDClient'];
                    $date = new DateTime($_POST['vDateTransactionBilling']);
                    $vDateTransaction = $date->format('Y-m-d');
                    $vDateTransactionBilling = (string) str_replace('/','',$_POST['vDateTransactionBilling']);
                    
                    $vAmountBilling = $this->roundNumber($_POST['vAmountBilling']);
                    
                    /* GENERA LOS 5 NUMEROS DE VERHOEFF */
                    $vFiveDigitVerhoeffNumber = $this->fiveDigitVerhoeffNumber($vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling);
                    
                    /* GENERA LOS 5 NUMEROS DE VERHOEFF INCREMENTADOS EN 1 POR SEPARADO */
                    $vFiveDigitVerhoeffSeparateDigits = $this->separateDigitsAndAddNumber($vFiveDigitVerhoeffNumber, 1);
                    //print_r($vFiveDigitVerhoeffSeparateDigits);
                    //exit;
                    /* GENERA LA CADENA CONCATENADA EXTRAIDA DE LA LLAVE DE DOSIFICACIÓN MÁS LOS 5 NUMEROS DE VERHOEFF INCREMENTADOS EN 1  */
                    $vStringDosingWrenchKey = $this->getDosingWrenchKeyString($vNumAutorization, $vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling, $vFiveDigitVerhoeffSeparateDigits);
                    //echo $this->compararCadenas($vStringDosingWrenchKey, '1904008691195pPg97825622iF047S%)v}@N4W20080201233aQqqXCEH2600627VS2[aD');
                    //exit;
                    /* CADENA ALLEGEDRC4 */
                    $vStringAllegedRC4 = $this->algorithmAllegedRC4($vStringDosingWrenchKey, $this->getDosingWrenchKey(), $vFiveDigitVerhoeffNumber);
                    
                    /* APLICACIÓN DE BASE64 A CADENA ALLEGEDRC4 */
                    $vStringBase64 = $this->algorithmBase64($vStringAllegedRC4);

                    
                    /* GENERACIÓN DEL CÓDIGO DE CONTROL */
                    $vControlCodeString = $this->getControlCode($vStringBase64, $this->getDosingWrenchKey(), $vFiveDigitVerhoeffNumber);
                    
                    //echo $vAmountBilling.'-'.$vFiveDigitVerhoeffNumber.'-'.$vFiveDigitVerhoeffSeparateDigits.'-'.$vStringDosingWrenchKey.'-'.$vStringAllegedRC4.'-'.$vStringBase64.'-'.$vControlCodeString;
                    //echo $vControlCodeString;
                    echo 'invoiceLetter/'.$vNumAutorization.'/'.$vNumBilling.'/'.$vIDClient.'/'.$vDateTransactionBilling.'/'.$vDateTransaction.'/'.$vAmountBilling.'/'.$vControlCodeString;
                }            
			}
    
		public function invoiceLetter($vNumAutorization,$vNumBilling,$vIDClient,$vDateTransactionBilling,$vDateTransaction,$vAmountBilling,$vControlCodeString){            

				/* CARGA LIBRERIAS */
				$this->getLibrary('phpqrcode/qrlib');
				//$this->vNumberVerhoeff = new Verhoeff;
            
                $vNumAutorization = (string) $vNumAutorization;
                $vNumBilling = (string) $vNumBilling;
                $vIDClient = (string) $vIDClient;
                $vDateTransactionBilling = (string) $vDateTransactionBilling;
                $vDateTransaction = (string) $vDateTransaction;
                $vAmountBilling = (string) $vAmountBilling;
                $vAmountBillingText = $this->num2letras($vAmountBilling);
                $vControlCodeString = (string) $vControlCodeString;
            
                $this->vView->vNumAutorization = $vNumAutorization;
                $this->vView->vNumBilling = $vNumBilling;
                $this->vView->vIDClient = $vIDClient;
                $this->vView->vDateTransactionBilling = $vDateTransactionBilling;
                $this->vView->vDateTransaction = $vDateTransaction;
                $this->vView->vAmountBilling = $vAmountBilling;
                $this->vView->vAmountBillingText = $vAmountBillingText;
                $this->vView->vControlCodeString = $vControlCodeString;
            
            
                /* GENERADOR QRCode */
                //$tempDir =  ROOT_APPLICATION.'views\backend\systemBilling\imagesqrcode\\';
                $tempDir =  ROOT_APPLICATION.'views'.DIR_SEPARATOR.'backend'.DIR_SEPARATOR.'systemBilling'.DIR_SEPARATOR.'imagesqrcode'.DIR_SEPARATOR;
                $codeContents = $vNumAutorization.'/'.$vNumBilling.'/'.$vIDClient.'/'.$vDateTransactionBilling.'/'.$vAmountBilling.'/'.$vControlCodeString;
                // we need to generate filename somehow, 
                // with md5 or with database ID used to obtains $codeContents...
                $fileName = $vNumBilling.'_'.md5($codeContents).'.png';
                $pngAbsoluteFilePath = $tempDir.$fileName;
                $urlRelativeFilePath = $fileName;
                // generating
                if (!file_exists($pngAbsoluteFilePath)) {
                    QRcode::png($codeContents, $pngAbsoluteFilePath);
                    /*echo 'File generated!';
                    echo '<hr />';*/
                } else {
                    /*echo 'File already generated! We can use this cached file to speed up site on common codes!';
                    echo '<hr />';*/
                }

                $this->vView->vQRCodeImage = $urlRelativeFilePath;
                
                $this->vView->visualizar('invoiceletter');
			}    
    
		public function algorithmVerhoeff($vNumber, $vNumberLoop){
				/* CARGA LIBRERIAS */
				$this->getLibrary('verhoeff');
				$this->vNumberVerhoeff = new Verhoeff;
            
                $vNumber = (string) $vNumber;
                $vNumberLoop = (string) $vNumberLoop;
            
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
            
                $vStringAllegedRC4 = $this->vAllegedRC4String->encryptMessageRC4($vStringDosingWrenchKey, $vDosingWrenchKey.$vFiveDigitVerhoeffNumber, true);            
            
                $vFiveDigitVerhoeffSeparateDigits = $this->separateDigitsAndAddNumber($vFiveDigitVerhoeffNumber, 1);
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
            
                $tmp1 = floor($totalAmount*$sp1/$vFiveDigitVerhoeffSeparateDigits[0]);
                $tmp2 = floor($totalAmount*$sp2/$vFiveDigitVerhoeffSeparateDigits[1]);
                $tmp3 = floor($totalAmount*$sp3/$vFiveDigitVerhoeffSeparateDigits[2]);
                $tmp4 = floor($totalAmount*$sp4/$vFiveDigitVerhoeffSeparateDigits[3]);
                $tmp5 = floor($totalAmount*$sp5/$vFiveDigitVerhoeffSeparateDigits[4]);

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
            
                $vNumBilling = (string) $this->algorithmVerhoeff($vNumBilling, 2);
                $vIDClient = (string) $this->algorithmVerhoeff($vIDClient, 2);
                $vDateTransactionBilling = (string) $this->algorithmVerhoeff($vDateTransactionBilling, 2);
                $vAmountBilling = (string) $this->algorithmVerhoeff($vAmountBilling, 2);
                
                $vFiveDigitVerhoeffNumber = $vNumBilling + $vIDClient + $vDateTransactionBilling + $vAmountBilling;
            
                return substr($this->algorithmVerhoeff($vFiveDigitVerhoeffNumber,5), -5, 5);
			}
    
		public function getDosingWrenchKeyString($vNumAutorization, $vNumBilling, $vIDClient, $vDateTransactionBilling, $vAmountBilling, $vFiveDigitVerhoeffNumber){
                
                $vNumBilling = (string) $this->algorithmVerhoeff($vNumBilling, 2);
                $vIDClient = (string) $this->algorithmVerhoeff($vIDClient, 2);
                $vDateTransactionBilling = (string) $this->algorithmVerhoeff($vDateTransactionBilling, 2);
                $vAmountBilling = (string) $this->algorithmVerhoeff($vAmountBilling, 2);
            
                $vArrayFiveDigitVerhoeffNumber[] = array();
                $vArrayStringDosingWrenchKey[] = array();
                $vPositionStringDosingWrenchKey = 0;
            
                $vStringDosingWrenchKey = $this->getDosingWrenchKey();
            
                
                for($i = 0;$i < strlen($vFiveDigitVerhoeffNumber); $i++){ 
                    $vArrayStringDosingWrenchKey[$i] = substr($vStringDosingWrenchKey, $vPositionStringDosingWrenchKey, $vFiveDigitVerhoeffNumber[$i]);
                    $vStringDosingWrenchKey = substr($vStringDosingWrenchKey, $vArrayFiveDigitVerhoeffNumber[$i]);
                    $vPositionStringDosingWrenchKey = $vPositionStringDosingWrenchKey + $vFiveDigitVerhoeffNumber[$i];
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