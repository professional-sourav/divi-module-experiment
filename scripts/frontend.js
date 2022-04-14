// This script is loaded both on the frontend page and in the Visual Builder.

jQuery(function($) {

    const ajax_pagination_options = shop_extension_options;

    // console.log( ajax_pagination_options, ajax_pagination_options['cwe_ajax_pagination'] );

    // if ( ajax_pagination_options['cwe_ajax_pagination']['desktop'] === "on" ) {
    if ( ajax_pagination_options['cwe_ajax_pagination'] === "on" ) {

        $(document).on(
            'click', 
            '.woocommerce .csh_custom_woo_products nav.woocommerce-pagination ul li a, .csh_custom_woo_products .woocommerce nav.woocommerce-pagination ul li a', 
            function(e) {

            e.preventDefault();

            // $(this).parents('.csh_custom_woo_products > .et_pb_module_inner > .csh_custom_woo_products').load( $(this).attr('href') );

            let page_data = httpGet( $(this).attr('href') );
            console.log(page_data);

            const div = document.createElement('div');
            div.innerHTML= page_data;

            var shopBlock = div.querySelector('div.woocommerce');

            $(this).parents('.csh_custom_woo_products > .et_pb_module_inner > .csh_custom_woo_products').html( shopBlock );
        });
    }
});



function httpGet(theUrl)
{
    let xmlhttp;
    
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            return xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", theUrl, false);
    xmlhttp.send();
    
    return xmlhttp.response;
}