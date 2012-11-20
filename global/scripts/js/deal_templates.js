jQuery(document).ready(function(){
   switch(initial_page)
   {
      case 'heist':
         jQuery("#content").load("/deal_templates/heist_daily.php");
         break;
      case 'sampler':
         jQuery("#content").load("/deal_templates/daily_sampler.php");
         break;
      case 'heist_access':
         jQuery("#content").load("/deal_templates/heist_access.php");
         break;
      default:
         break;
   }
   if(jQuery.cookie("zenid") == null)
   {
      jQuery.getJSON("http://www.stogieboys.com/api.php?action=get_session",
              function(data){
                  jQuery.cookie("zenid", data.zenid, {expires:7});
                  zenid = data.zenid;
                  jQuery("#cart_link").attr("href", jQuery("#cart_link").attr("href") + zenid);
                  jQuery("#checkout_link").attr("href", jQuery("#checkout_link").attr("href") + zenid);
              });
      jQuery("#cart_total").html("$0.00");
      jQuery("#cart_number").html("0");
   }
   else
   {
      zenid = jQuery.cookie("zenid");
      jQuery("#cart_link").attr("href", jQuery("#cart_link").attr("href") + zenid);
      jQuery("#checkout_link").attr("href", jQuery("#checkout_link").attr("href") + zenid);
      jQuery.getJSON("http://www.stogieboys.com/api.php?action=get_cart", {"zenid":zenid},
                     function(data){
                        jQuery("#cart_total").html(data.total);
                        jQuery("#cart_number").html(data.count);
                     });
   }
   jQuery('#access_link').live("click", function(e){
      e.preventDefault();
      jQuery("#content").load("/deal_templates/heist_access.php");
      return false;
   });
   jQuery('#sampler_link').live("click", function(e){
      e.preventDefault();
      jQuery("#content").load("/deal_templates/daily_sampler.php");
      return false;
   });
   jQuery('#heist_link').live("click", function(e){
      e.preventDefault();
      jQuery("#content").load("/deal_templates/heist_daily.php");
      return false;
   });
   jQuery('#5pack_link').live("click", function(e){
      e.preventDefault();
      jQuery.support.cors = true;
      jQuery.get('http://www.stogieboys.com/output_dynamic_filters.php',
                 function(data){
                  data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
                  jQuery("#content").html(data);
               fix_multi_submits();
                 });
      jQuery.ajax({
          url: "http://www.stogieboys.com/index.php",
          data: {"main_page":"product_filter_result2","categories_id":"321"},
          type: "GET",
          timeout: 30000,
          dataType: "text", // "xml", "json"
          success: function(data) {
            data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
            jQuery("#content").append(data);
               fix_multi_submits();
          }
      });
      
      jQuery("#product_filter_from").append('<input type="hidden" name="categories_id" id="categories_id" value="321" class="inputboxsmall" size="30">');
      return false;
   });
   
   
   
   jQuery("#product_filter_from select").live("change", function(){
       event.preventDefault();
       var serials = jQuery(this).closest("form").serialize();
       jQuery.get('http://www.stogieboys.com/index.php?' + serials, function(data){
               data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
               jQuery("#productListing").html(data);
               fix_multi_submits();
               });
       return false;
   });
   jQuery('.navSplitPagesLinks a').live('click',  function(event)
   {
       event.preventDefault();
       var full_url = jQuery(this).attr('href');
       jQuery.get('http://www.stogieboys.com/api.php',{action:'product_filter', full_url:full_url }, function(data){
               data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
               jQuery("#productListing").html(data);
               fix_multi_submits();
               });
       return false;
       
   });
   jQuery('a.alpha_filter').live('click',  function(event)
   {
       event.preventDefault();
       var full_url = jQuery(this).attr('href');
       jQuery.get('http://www.stogieboys.com/api.php',{action:'product_filter', full_url:full_url }, function(data){
               data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
               jQuery("#productListing").html(data);
               fix_multi_submits();
               });
       return false;
       
   });
   jQuery('.productListing-heading a').live('click',  function(event)
   {
       event.preventDefault();
       var full_url = jQuery(this).attr('href');
       jQuery.get('http://www.stogieboys.com/api.php',{action:'product_filter', full_url:full_url }, function(data){
               data = data.replace(/src=\"/g,'src="http://www.stogieboys.com/');
               jQuery("#productListing").html(data);
               fix_multi_submits();
               });
       return false;
       
   });
   jQuery('td.productListing-data form[name="cart_quantity"] input[type="image"]').live("click",function(event){
      event.preventDefault();
      var prod_id = jQuery(this).closest("form").find("input[name='products_id']").attr("value");
      var quant = jQuery(this).closest("form").find("input[name='cart_quantity']").attr("value");
      jQuery.ajax({
          url: "http://www.stogieboys.com/api.php",
          data: {"multistore":"1","action":"add_product", "products_id": prod_id, "cart_quantity":quant,
          "zenid":zenid},
          type: "POST",
          timeout: 30000,
          dataType: "json", // "xml", "json"
          success: function(data) {
            jQuery("#cart_total").html(data.total);
            jQuery("#cart_number").html(data.count);
          }
      });
      return false;
   });
   jQuery("a.buy_now_link").live("click", function(){
      event.preventDefault();
      var prod_id = jQuery(this).attr("href");
      var quant = 1;
      jQuery.ajax({
          url: "http://www.stogieboys.com/api.php",
          data: {"multistore":"1","action":"add_product", "products_id": prod_id, "cart_quantity":quant,
          "zenid":zenid},
          type: "POST",
          timeout: 30000,
          dataType: "json", // "xml", "json"
          success: function(data) {
            jQuery("#cart_total").html(data.total);
            jQuery("#cart_number").html(data.count);
          }
      });
      return false;
   });
   
});
function fix_multi_submits()
{
  jQuery('td.productListing-data form[name="cart_quantity"]').each(
	      function(){
		jQuery(this).attr("action", "http://www.stogieboys.com/shopping_cart?action=add_product");
                jQuery(this).attr("target","_blank");
	      }
	    );
}