<?php
$settings = getStyleSettings_dl();
if ( sizeof( $settings ) > 0 ) {
	$selectedItemTextColor  = '#ffffff';
	$buttonTextHoverColor   = $buttonTextColor = '#ffffff';
	$sliderColor            = $settings['settings']['hoverEffect'][0]->color1;
	$selectedItemColor      = $settings['settings']['hoverEffect'][0]->color1;
	$selectedItemhoverColor = $settings['settings']['hoverEffect'][0]->color2;
	$gridColor              = $settings['settings']['columnHeaderAccent'][0]->color1;
	$gridHoverColor         = $settings['settings']['columnHeaderAccent'][0]->color2;
	$buttonBackgroundColor  = $settings['settings']['callToActionButton'][0]->color1;
	$buttonhoverColor       = $settings['settings']['callToActionButton'][0]->color2;
	$linkColor              = $settings['settings']['linkColor'][0]->color1;
	$linkHoverColor         = $settings['settings']['linkColor'][0]->color2;
	?>
    <style>
        #search-rings .diamond-filter-title, .diamonds-search .diamond-filter-title {  background: <?php echo $gridHoverColor ?>!important;   }
        #search-diamonds .ui-slider-horizontal .ui-slider-range {
            background: <?php echo $sliderColor ?>;
            border-color: <?php echo $sliderColor ?>;
        }

        #search-diamonds .ui-slider .ui-slider-tooltip, #search-diamonds .ui-widget-content .ui-slider-handle {
            background: <?php echo $sliderColor ?>;
        }

        #search-diamonds .ui-slider .ui-slider-tooltip .ui-tooltip-pointer-down-inner {
            border-top: 7px solid <?php echo $sliderColor ?> !important;
        }

        .diamond-filter-title ul li a:hover {
            color: <?php echo $linkHoverColor ?>;
        }

        .product-controler ul li a {
            color: <?php echo $linkColor ?>;
        }

        .filter-for-shape ul li .shape-type.selected {
            background: <?php echo $linkHoverColor ?>;
        }

        .filter-for-shape ul li .shape-type.unselected{
                pointer-events: none;
                opacity: 0.5
        }

        .filter-for-shape ul li .shape-type:hover {
            background: <?php echo $selectedItemhoverColor ?>;
        }

        .filter-for-shape .cut-main ul li.active {
            background: <?php echo $linkHoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .filter-advanced .accordion:before, .change-view-result ul li.list-view a.active:before, .change-view-result ul li.grid-view a.active:before, .change-view-result ul li a.active:before, .search-in-table button {
            background-color: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .diamond-filter-title ul li a {
            color: <?php echo $linkHoverColor ?>;
        }

        .search-details .table thead tr th {
            background: <?php echo $gridHoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .search-details .table tbody tr th.table-selecter .state label:before {
            border: 2px solid<?php echo $selectedItemColor ?>;
        }

        .search-details .table tbody tr th.table-selecter input[type="checkbox"]:checked ~ .state label:after {
            background-color: <?php echo $selectedItemColor ?>;
        }

        .search-details .table tbody tr th.table-selecter .state label:after {
            border: 1px solid<?php echo $selectedItemhoverColor ?>;
        }

        .search-details .table tbody tr th.table-selecter input[type="checkbox"]:checked ~ .state label:before {
            background-color: <?php echo $selectedItemhoverColor ?>;
        }

        .search-details .table tbody tr:hover td, .search-details .table tbody tr:hover th {
            background: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .search-details .table tbody tr:hover td a {
            color: <?php echo $buttonTextColor ?>;
        }

        .grid-paginatin ul li.active {
            background: <?php echo $buttonBackgroundColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .grid-paginatin ul li.active a {
            background: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .grid-paginatin ul li.active:hover, .diamond-page .diamond-action button.addtocart:hover, .prefrence-action .preference-btn:hover, .compare-actions .view-product:hover, .grid-paginatin a#compare-main:hover, .grid-paginatin ul li:not(.grid-previous):not(.grid-next) a:hover, .compare-actions .delete-row:before, .compare-actions .delete-row {
            background: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $buttonTextHoverColor ?>;
        }

        .change-view-result ul li.list-view a:hover, .change-view-result ul li.grid-view a:hover, .change-view-result ul li a:hover {
            color: <?php echo $buttonTextColor ?>;
        }

        .search-in-table button:hover {
            background-color: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextHoverColor ?>;
        }

        .search-product-grid .product-details .product-box-pricing span {
            color: <?php echo $selectedItemColor ?>;
        }

        .product-details .product-box-action label {
            color: <?php echo $selectedItemhoverColor ?>;
        }

        .product-details .product-box-action .state label:before {
            border: 2px solid<?php echo $selectedItemhoverColor ?>;
        }

        .product-details .product-box-action .state label:after {
            border: 1px solid<?php echo $selectedItemColor ?>;
        }

        .product-details .product-box-action input[type="checkbox"]:checked ~ .state label:before {
            background: <?php echo $selectedItemColor ?>;
        }

        .product-details .product-box-action input[type="checkbox"]:checked ~ .state label:after {
            background-color: <?php echo $selectedItemColor ?>;
        }

        .product-controler ul li:before {
            background-color: <?php echo $buttonhoverColor ?>;
        }

        .specification-title h4 a {
            color: <?php echo $linkColor ?>;
        }

        .diamond-request-form .form-field .diamond-action span {
            color: <?php echo $selectedItemColor ?>;
        }
        .diamond-page .diamond-action button.addtocart {
            background: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .diamond-info h2 span {
            color: <?php echo $linkColor ?>;
        }

        .diamond-request-form .form-field label input:focus, .diamond-request-form .form-field label textarea:focus {
            border-color: <?php echo $selectedItemColor ?>;
        }

        .prefrence-action .preference-btn {
            background: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .diamond-request-form .form-field .prefrence-area input:checked ~ label:before {
            background: <?php echo $selectedItemColor ?>;
        }

        .compare-product .filter-title ul.filter-left li:hover a {
            color: <?php echo $linkHoverColor ?>;
        }

        .color-filter ul li.active, .filter-details .polish-depth ul li.active {
            background: <?php echo $linkHoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .color-filter ul li:hover, .filter-details .polish-depth ul li:hover, .filter-for-shape .cut-main ul li:hover {
            background: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $selectedItemTextColor ?>;
        }

        .ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span {
            border-color: transparent <?php echo $selectedItemColor ?> transparent transparent;
        }

        .ui-datepicker .ui-datepicker-next span {
            border-color: transparent transparent transparent<?php echo $selectedItemColor ?>;
        }

        .ui-datepicker .ui-datepicker-calendar .ui-datepicker-today {
            background: <?php echo $selectedItemhoverColor ?>;
        }

        .grid-paginatin a#compare-main {
            background: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .product-controler ul li a:hover {
            color: <?php echo $selectedItemhoverColor ?>;
        }
        .product-controler ul li:hover:before {
            background-color: <?php echo $selectedItemhoverColor ?>;
        }

        .product-slide-button .trigger-info:before {
            color: <?php echo $buttonhoverColor ?> !important;
        }

        .compare-actions .view-product {
            background: <?php echo $buttonBackgroundColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .compare-info table tbody tr th:nth-child(1) a:hover:before, .compare-info table tbody tr th:nth-child(1) a:before {
            background: <?php echo $buttonBackgroundColor ?>;
        }
        .filter-advanced .SumoSelect>.optWrapper>.options li.opt.selected {
            background-color: <?php echo $linkHoverColor; ?> !important;
            color: #ffffff !important;
        }
        .SumoSelect > .optWrapper > .options li.opt:hover {
            background-color: <?php    echo $selectedItemhoverColor; ?> !important;
            color: #ffffff !important;
        }
        .compare-product .filter-title ul.filter-left li a {
            color: <?php echo $linkColor ?>;
        }

        .compare-product .filter-title ul.filter-left li a:hover {
            color: <?php echo $linkHoverColor ?>;
        }

        .sumo_pagesize .optWrapper ul.options li.opt.selected {
            background-color: #E4E4E4;
            color: #000;
        }

        .SumoSelect > .optWrapper.multiple > .options li.opt.selected span i, .SumoSelect .select-all.selected > span i {
            background-color: <?php echo $buttonBackgroundColor.' !important' ?>;
        }

        .diamond-report .view_text a {
            color: <?php echo $buttonBackgroundColor ?> !important;
        }

        .internalusemodel.modal-slide .modal-inner-wrap, .dealerinfopopup.modal-slide .modal-inner-wrap {
            border: 2px solid<?php echo $selectedItemColor ?>;
        }

        .internalusemodel.modal-slide header button, .dealerinfopopup.modal-slide header button, #internaluseform button.preference-btn {
            background: <?php echo $buttonBackgroundColor.' !important' ?>;
            box-shadow: none;
            color: <?php echo $buttonTextColor ?>;
        }

        a.internaluselink {
            color: <?php echo $buttonBackgroundColor ?>;
        }

        a.internaluselink:hover {
            color: <?php echo $linkHoverColor ?>;
        }

        .internalusemodel .msg {
            padding: 2px;
            margin-bottom: 2px;
        }

        .internalusemodel .msg .error {
            color: #e40f0f;
        }

        .internalusemodel .msg .success {
            color: #29a529;
        }

        .breadcrumbs ul li a {
            color: <?php echo $linkColor ?> !important;
        }

        .breadcrumbs ul li a:hover {
            color: <?php echo $linkHoverColor ?> !important;
        }

        svg#Capa_1 {
            fill: <?php echo $linkHoverColor ?>;
        }

        .search-details .table tbody tr:hover td a svg#Capa_1{
            fill: <?php echo $selectedItemhoverColor ?>;   
        }

        .flow-tabs .tab-section li.tab-li.active a {
            background: <?php echo $gridHoverColor ?> !important;
        }

        .flow-tabs .tab-section li.tab-li.active a:after {
            border-color: transparent transparent transparent<?php echo $gridHoverColor ?>;
        }

        .filter-for-shape ul li .shape-type:hover {
            background: <?php echo $selectedItemhoverColor ?>;
        }
        #search-diamonds .noUi-horizontal .noUi-connect, #search-diamonds .ui-slider .noUi-handle {
            background-color: <?php echo $linkHoverColor ?>;
            border-color: <?php echo $linkHoverColor ?>;
        }
        .filter-advanced .accordion:before, .change-view-result ul li.list-view a.active:before, .change-view-result ul li.grid-view a.active:before, .change-view-result ul li a.active:before, .search-in-table button, .search-details .change-view-result ul li a.active {
            background-color: <?php echo $selectedItemhoverColor; ?> !important;
            color: #ffffff;
        }
        .filter-advanced .accordion:before {
            background-color: <?php echo $linkHoverColor; ?> !important;
            color: #ffffff;
        }
        
        .diamond-filter-title #navbar li.active a {
            color: <?php   echo $linkHoverColor; ?> !important;
        }
        .search-product-grid a.triggerVideo {
         color: #002;
        }
          
        /* navigation menu */
        
        .diamonds-filter #navbar li.active, .compare-product .filter-title ul.filter-left li.active { 
            background: <?php echo $selectedItemhoverColor ?>;
        }
        .diamonds-filter #navbar li.active a, .compare-product .filter-title ul.filter-left li.active a{
            color: <?php echo $buttonTextColor ?> !important;
        }
        .diamonds-filter #navbar li.active a{
            color: <?php echo $selectedItemhoverColor ?>;
            opacity: 1;
        }
        .diamonds-filter #navbar li:hover{
            background: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $selectedItemTextColor ?>;
        }
        .diamonds-filter #navbar li:hover a{
            color: <?php echo $selectedItemTextColor ?>;
        }
        .diamonds-filter .filter-left li {
            display: flex;
            align-items: center;
        }
        .diamonds-filter .filter-left li a {
           padding:12px;
        }
        /* .diamonds-filter .diamond-filter-title .filter-right li {
            padding:10px;
        } */
        .diamonds-filter .filter-right li a{
            padding:12px;
            display: flex;
        }
        .diamonds-filter ul li span.border-pattern{
            margin-bottom: 2px;
            margin-left: 20px;
            font-size: 19px;
            font-weight: 600;
            margin-right: 10px;
         }
        .diamonds-filter ul li:last-child span.border-pattern{
          display: none;
        }
        .diamonds-filter #navbar li:hover{
          transition: all 700ms cubic-bezier(0, 0.4, 0.51, 1.35);
        }
        .diamonds-filter ul li.active .show-filter-info{
            margin-right: 12px;
        }
        .diamonds-filter #navbar li.active i.fa.fa-info-circle{
            color: #fff !important;
        }
        .diamonds-filter ul li:hover .show-filter-info{
            margin-right: 12px;
        }
        .diamonds-filter ul li .show-filter-info{
            margin-right: 12px;
        }
        .compare-product ul li:hover{
            transition: all 700ms cubic-bezier(0, 0.4, 0.51, 1.35);
        }
        .compare-product ul li:hover a{
            background: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $selectedItemTextColor ?> !important;
            opacity: 1;
        }
        .compare-product .filter-title ul.filter-left{
            padding: 0 20px 0 20px;
        }
        .compare-product .filter-title ul.filter-left li a {
           color: #ffffff;
        }
       .search-product-grid .triggerVideo i {
            color: #000;
        }      
        .filter-details h4{            
            min-width: 155px;
        }
        @media (min-width: 1251px) and (max-width: 1533px) {
            .diamonds-filter ul li a{
	            font-size: 18px;
            }
            .diamonds-filter ul.filter-right li {
                margin-top:0px;
            }
        }           
        @media (min-width: 1066px) and (max-width: 1250px) {
            .diamonds-filter ul li a{
	            font-size: 17px;
            }
            .diamonds-filter ul.filter-right li {
                margin-top:0px;
            }
        }   
        @media (min-width: 987px) and (max-width: 1021px) {
            .diamonds-filter ul.filter-right li {
                margin-top:0px;
            }
        }
        @media (min-width: 981px) and (max-width: 986px) {
            .diamonds-filter .filter-left li a {
                padding:13px;
            }
            .diamonds-filter ul.filter-right li {
                margin-top:0px;
            }
        }
        @media (min-width: 1021px) and (max-width: 1065px) {
	        .diamonds-filter ul li a{
	            font-size: 15px;
	        }
        }
        @media (min-width: 981px) and (max-width: 1020px) {
	        .diamonds-filter ul li a{
	            font-size: 14px;
	        }
        }
        @media (max-width:980px) { 
            .diamonds-filter ul.filter-right li {
                margin-top:0px;
            }
        }

    </style>
    <script>
        var compareItemsarrayrb = [];
        var compareItemsrb = [];
    </script>
<?php } ?>