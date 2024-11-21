<?php
defined( 'RING_BUILDER_Path' ) OR exit( 'No direct script access allowed' );
?>
<?php
$settings = getStyleSettingsRB();
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
    $viewButtonTextColor    = $settings['settings']['linkColor'][0]->color2;
	?>
    <style>

        #search-rings .diamond-filter-title {  background: <?php echo $gridHoverColor ?>!important;   }
        .rings-search .ui-slider-horizontal .ui-slider-range {
            background-color: <?php echo $sliderColor ?>;
            border-color: <?php echo $sliderColor ?>;
        }

        .ui-slider .ui-slider-tooltip, .ringbuilder-settings-index .price-filter .ui-slider-handle {
            background-color: <?php echo $sliderColor ?>;
        }

        .flow-tabs .tab-section li.tab-li.active a {
            background: <?php echo $gridHoverColor ?>;
        }

        .flow-tabs .tab-section li.tab-li.active a:after {
            border-color: transparent transparent transparent<?php echo $gridHoverColor ?>;
        }

        .diamond-filter-title ul li.active a, .diamond-filter-title ul li a:hover {
            color: <?php echo $selectedItemhoverColor ?>;
        }

        .ringbuilder-settings-view .diamonds-info .product-controler ul li a {
            color: <?php echo $linkColor ?>;
        }

        .ringbuilder-settings-view .diamonds-info .product-controler ul li a:hover {
            color: <?php echo $selectedItemhoverColor ?>;
        }

        .ringbuilder-settings-view .diamonds-info .diamond-action button {
            background-color: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }
        .diamond-page .diamond-action button.addtocart:hover, .ring-request-form .diamond-action button.addtocart:hover{   
            background-color: <?php echo $selectedItemhoverColor ?>;
         }

        .filter-for-shape ul li .shape-type.selected {
            background: <?php echo $linkHoverColor ?>;
        }

        .filter-for-shape ul li .shape-type:hover {
            background: <?php echo $selectedItemhoverColor ?>;
        }

        .filter-for-shape .cut-main ul li.active {
            background: <?php echo $selectedItemColor ?>;
            color: <?php echo $selectedItemTextColor ?>;
        }

        .filter-advanced .accordion:before, .change-view-result ul li.list-view a.active:before, .change-view-result ul li.grid-view a.active:before, .change-view-result ul li a.active:before, .search-in-table button {
            background-color: <?php echo $buttonhoverColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .flow-tabs .rings-search .color-filter ul li:hover {
            border-bottom: 2px solid<?php echo $selectedItemhoverColor ?>;
            background: #ffffff;
        }

        .flow-tabs .rings-search .color-filter ul li.active.selected {
            border-bottom: 2px solid<?php echo $selectedItemhoverColor ?>;
        }

        .ringbuilder-settings-index .searching-result .change-view ul li a.active {
            background-color: <?php echo $buttonhoverColor ?>;
        }

        .grid-paginatin ul li.active {
            background: <?php echo $selectedItemhoverColor ?>;
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
            color: <?php echo $buttonTextHoverColor ?>;
        }

        .search-in-table button:hover {
            background-color: <?php echo $selectedItemhoverColor ?>;
            color: <?php echo $buttonTextHoverColor ?>;
        }

        .search-product-grid .product-details .product-box-pricing span {
            color: <?php echo $selectedItemColor ?>;
        }

        .product-details .product-box-action label {
            color: <?php echo $selectedItemColor ?>;
        }

        .product-details .product-box-action .state label:before {
            border: 2px solid<?php echo $selectedItemColor ?>;
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
            background: <?php echo $buttonBackgroundColor ?>;
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

        .compare-product .filter-title ul.filter-left li.active a {
            color: <?php echo $linkHoverColor ?>;
        }

        .color-filter ul li.active, .filter-details .polish-depth ul li.active {
            border-bottom: 2px solid<?php echo $selectedItemColor ?>;
            color: <?php echo $buttonBackgroundColor ?>;
        }

        .color-filter ul li:hover, .filter-details .polish-depth ul li:hover, .filter-for-shape .cut-main ul li:hover {
            border-bottom:: 2px solid<?php echo $selectedItemColor ?>;
            color: <?php echo $buttonBackgroundColor ?>;
        }

        .ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span {
            border-color: transparent <?php echo $selectedItemColor ?> transparent transparent;
        }

        .ui-datepicker .ui-datepicker-next span {
            border-color: transparent transparent transparent<?php echo $selectedItemColor ?>;
        }

        .ui-datepicker .ui-datepicker-calendar .ui-datepicker-today {
            background: <?php echo $selectedItemColor ?>;
        }

        .grid-paginatin a#compare-main {
            background: <?php echo $buttonBackgroundColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .product-controler ul li a:hover {
            color: <?php echo $selectedItemhoverColor ?>;
        }

        .product-slide-button .trigger-info:before {
            color: <?php echo $buttonBackgroundColor ?> !important;
        }

        .compare-actions .view-product {
            background: <?php echo $viewButtonTextColor ?>;
            color: <?php echo $buttonTextColor ?>;
        }

        .compare-info table tbody tr th:nth-child(1) a:hover:before, .compare-info table tbody tr th:nth-child(1) a:before {
            background: <?php echo $buttonBackgroundColor ?>;
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
            fill: <?php echo $buttonhoverColor ?>;
        }

        svg#Capa_1:hover {
            fill: <?php echo $selectedItemhoverColor ?>;
        }

        .ringbuilder-settings-view .specification-title a svg {
            fill: <?php echo $buttonBackgroundColor ?>;
        }

        .flow-tabs .rings-search .shapepricefiltersection .filter-alignment-right .shape-type:hover {
            background: <?php echo $selectedItemhoverColor ?>;
        }
        .ui-datepicker .ui-datepicker-calendar .ui-datepicker-today a{ background: <?php echo $selectedItemhoverColor ?>; }
        #search-rings .ui-slider .noUi-handle, #search-rings .noUi-horizontal .noUi-connect { background: <?php echo $linkHoverColor ?>; }
        .filter-advanced .accordion:before, .change-view-result ul li.list-view a.active:before, .change-view-result ul li.grid-view a.active:before, .change-view-result ul li a.active:before, .search-in-table button, .search-details .change-view-result ul li a.active {
            background-color: <?php echo $buttonhoverColor; ?> !important;
            color: #ffffff;
        }
         .diamond-filter-title ul li.active a, .diamond-filter-title ul li.active{
            background-color : <?php echo $selectedItemhoverColor; ?> 
        }
       
    </style>
<?php } ?>