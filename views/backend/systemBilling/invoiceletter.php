<?Php echo setlocale(LC_TIME, 'es_ES.UTF-8'); ?>
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?Php echo BASE_VIEW_URL; ?>">Dashboard</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <a href="#">Módulo Facturación</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Emisión de Facturas Electrónicas</span>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Sistema EasyBilling
                            <small>Sistema de Facturación Electrónica basada en requisitos del sistema de impuestos nacionales de Bolivia.</small>
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- END PAGE HEADER-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="invoice-content-2 bordered">
                                    <div class="row invoice-head">
                                        <div class="col-md-3 col-xs-6">
                                            <div class="invoice-logo">
                                                <img src="<?Php echo $vParamsViewBackEndLayout['root_backend_pages_css']; ?>squemas-invoice.png" class="img-responsive" alt="" />
                                                <h1 class="uppercase">Factura Oficial</h1>
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-xs-6">
                                            <div class="row">
                                                <div class="col-md-4 well pull-right text-right">
                                                    <strong>Nit</strong> 4826454016<br/>
                                                    <strong>Nro. Factura</strong> <?Php echo $this->vNumBilling; ?><br/>
                                                    <strong>Nro. de Autorización</strong> <?Php echo $this->vNumAutorization; ?><br/>
                                                </div>
                                            </div>                                            
                                            <div class="row">
                                                <div class="company-address">
                                                    <span class="invoice-text-1 bold uppercase">Sergio Martín Alarcón Montero</span>
                                                    <br/>
                                                    <span class="bold">Dirección</span> Calle Pinilla, Edificio Arcadia Nro. 2588
                                                    <br/>Mezzanine, Torre B - oficina 109, zona San Jorge
                                                    <br/>
                                                    <span class="bold">Ciudad,País</span> La Paz, Bolivia
                                                    <br/>
                                                    <span class="bold">Telefono oficina</span> 2430880
                                                    <br/>
                                                    <span class="bold">Telefono móvil</span> 78795415
                                                    <br/>                                                
                                                    <span class="bold">Correo electrónico</span> info@squemas.com
                                                    <br/>
                                                    <span class="bold">Sitio Web</span> www.squemas.com
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row invoice-cust-add">
                                        
                                        <div class="col-xs-3">
                                            <h2 class="invoice-title uppercase">Lugar y fecha de emisión</h2>
                                            <p class="invoice-desc">La Paz, <?Php echo strftime("%A, %d de %B de %Y", $this->vDateTransaction); ?></p>
                                        </div>
                                        <div class="col-xs-6">
                                            <h2 class="invoice-title uppercase">Nombre Cliente</h2>
                                            <p class="invoice-desc">Sergio Martín Alarcón Montero</p>
                                        </div>                                        
                                        <div class="col-xs-3">
                                            <h2 class="invoice-title uppercase">NIT Cliente</h2>
                                            <p class="invoice-desc"><?Php echo $this->vIDClient; ?></p>
                                        </div>
                                    </div>
                                    <div class="row invoice-body">
                                        <div class="col-xs-12 table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase">Cantidad</th>
                                                        <th class="invoice-title uppercase">Descripción</th>
                                                        <th class="invoice-title uppercase text-center">Precio Unitario</th>
                                                        <th class="invoice-title uppercase text-center">Precio Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center sbold">1</td>
                                                        <td>
                                                            <h3>Alquiler de espacios de trabajo</h3>
                                                            <p>Sala de reuniones, 2 a 6 personas, 1 semana, 3 horas por día, de 9:00 am a 12:30 pm</p>
                                                        </td>
                                                        <td class="text-center sbold">80Bs</td>
                                                        <td class="text-center sbold"><?Php echo $this->vAmountBilling; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row invoice-subtotal">
                                        <div class="col-xs-8">
                                            <h2 class="invoice-title uppercase">Monto total literal</h2>
                                            <p class="invoice-desc"><?Php echo $this->vAmountBillingText; ?></p>
                                        </div>                                        
                                        <div class="col-xs-4">
                                            <h2 class="invoice-title uppercase">Monto total numeral</h2>
                                            <p class="invoice-desc grand-total"><?Php echo $this->vAmountBilling; ?></p>
                                        </div>
                                    </div>
                                    <div class="row invoice-subtotal">                                        
                                        <div class="col-md-4 invoice-text-2 well pull-left">
                                            <strong>Código de control</strong> <?Php echo $this->formatControlCodeInvoice($this->vControlCodeString); ?><br/>
                                            <strong>Fecha límite de emisión</strong> <?Php echo $this->vDateTransactionBilling; ?><br/>
                                        </div>
                                        <div class="col-md-1 well pull-right">
                                            <img src="<?Php echo $vParamsViewQRCode['root_qrcode_img'].$this->vQRCodeImage; ?>" class="img-responsive" alt="" />
                                        </div>                                        
                                    </div>
                                    
                                    <div class="row invoice-subtotal">                                        
                                        <div class="col-md-12">
                                            <p>Esta factura contribuye al desarrollo del país, el uso ilícito de esta será sancionado de acuerdo a la ley.</p>
                                            <p>Ley Nº 453: El proveedor deberá suministrar el servicio en las modalidades y términos ofertados o convenidos.</p>
                                        </div>                                        
                                    </div>                                    
                                    
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <a class="btn btn-lg green-haze hidden-print uppercase print-btn" onclick="javascript:window.print();">Print</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>                        
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2017 &copy; EasyBilling Bolivia módulo de
                    <a target="_blank" href="http://ideas-envision.com">Ideas-Envision</a> Servicios Integrales &nbsp;|&nbsp;
                    <a href="http://ideas-envision.com" title="Utiliza nuestro Framework PHP IdEn v3.3" target="_blank">IdEn Framework</a>
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!-- BEGIN QUICK NAV -->
        <nav class="quick-nav">
            <a class="quick-nav-trigger" href="#0">
                <span aria-hidden="true"></span>
            </a>
            <ul>
                <li>
                    <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" target="_blank" class="active">
                        <span>Purchase Metronic</span>
                        <i class="icon-basket"></i>
                    </a>
                </li>
                <li>
                    <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/reviews/4021469?ref=keenthemes" target="_blank">
                        <span>Customer Reviews</span>
                        <i class="icon-users"></i>
                    </a>
                </li>
                <li>
                    <a href="http://keenthemes.com/showcast/" target="_blank">
                        <span>Showcase</span>
                        <i class="icon-user"></i>
                    </a>
                </li>
                <li>
                    <a href="http://keenthemes.com/metronic-theme/changelog/" target="_blank">
                        <span>Changelog</span>
                        <i class="icon-graph"></i>
                    </a>
                </li>
            </ul>
            <span aria-hidden="true" class="quick-nav-bg"></span>
        </nav>
        <div class="quick-nav-overlay"></div>
        <!-- END QUICK NAV -->
        <!--[if lt IE 9]>
<script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>respond.min.js"></script>
<script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>excanvas.min.js"></script> 
<script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>jquery.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBootstrap['root_bootstrap_js']; ?>bootstrap.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>js.cookie.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_plugins']; ?>jquery.blockui.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_global_scripts']; ?>app.min.js" type="text/javascript"></script>        
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_layouts_scripts']; ?>layout.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_layouts_scripts']; ?>demo.min.js" type="text/javascript"></script>
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_layouts_scripts']; ?>quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <!-- BEGIN SYSTEM EASYBILLING SCRIPTS -->
        <script src="<?Php echo $vParamsViewBackEndLayout['root_backend_pages_scripts']; ?>systemBilling.min.js" type="text/javascript"></script>
        <!-- END SYSTEM EASYBILLING SCRIPTS -->
            
        <script>
            $(document).ready(function(){
                $('#clickmewow').click(function(){
                    $('#radio1003').attr('checked', 'checked');
                });
                
            })
        </script>
    </body>

</html>