var select,attval;
var xrowCaptchaSuccess=false ;

jQuery(document).ready(function($) { 
       $(function(){
           $('#noScriptPrompt').hide();
           $('#nosubmit').css("visibility","visible");
       });
       
        jQuery("form").each(function(index) 
        {
            
            if(jQuery.inArray(jQuery(this).attr('action'), excludeObjects) == -1)
            {
                if( !(jQuery('.xrow-captcha').length > 0))
                {
                    jQuery(this).delegate("input:submit", 'click', function(event) 
                    {
                        if( document.xrowCaptchaSuccess )
                        {
                             jQuery('.log2').hide();
                             jQuery('.log1').show();
                             return true;
                        }else
                        {
                             jQuery('.log2').show();
                             jQuery('.log1').hide();
                             return false;
                        }
                             return false;
                     });

                    jQuery(this).prepend('<div class="xrow-captcha"></div>');
                    
                    jQuery('.xrow-captcha').each(function()
                    {
                        var element = jQuery(this);
                        jQuery.ez( 'xrowcaptcha::loadCaptcha', {}, function( data )
                        {
                            if ( data.error_text ) {
                                    element.html('<div class="error">' + data.error_text + '<div>');
                                } else {
                                    if(data.content == '')
                                    {
                                        document.xrowCaptchaSuccess = true; 
                                        element.html(data.content);
                                    }else{
                                    document.xrowCaptchaSuccess = false; 
                                    element.html(data.content);}
                                }
                         });
                      });
                    }else
                    {
                        jQuery(this).delegate("input:submit", 'click', function(event) 
                        {
                             if( document.xrowCaptchaSuccess )
                             {
                                  jQuery('.log2').hide();
                                  jQuery('.log1').show();
                                  return true;
                             }else
                             {
                                  jQuery('.log2').show();
                                  jQuery('.log1').hide();
                                  return false;
                             }
                             return false;
                         });
                         
                         jQuery('.xrow-captcha').each(function(index) 
                         {
                             var id = jQuery(this).attr('id');
                             var element = jQuery(this);
                             jQuery.ez( 'xrowcaptcha::loadCaptcha', {}, function( data )
                             {
                                  if ( data.error_text )
                                  {
                                      element.html('<div class="error">' + data.error_text + '<div>');
                                  } else 
                                  {
                                      if(data.content == '')
                                      {
                                          document.xrowCaptchaSuccess = true; 
                                          element.html(data.content);
                                      }else
                                      {
                                          document.xrowCaptchaSuccess = false; 
                                          element.html(data.content);
                                      }
                                   }
                              });
                          });
                     }
                }
           });

         jQuery('#code').live('click',function(e) 
        {
            var hash = jQuery(this).data( 'hash' );
            jQuery.ez('xrowcaptcha::reloadChallange', { 'hash' : hash }, function(result) {
                jQuery('.ca_challange').html(result.content);
                jQuery("#solution").attr("value",'');
                jQuery(".log1").hide();
                jQuery(".log2").hide();
            });
        });
        
        jQuery('#solution').live('blur',function(e) {
            var inputresult = parseInt(jQuery("#solution").attr("value"));
            var hash_cap = jQuery("input[name='xrowCaptchaHash']").attr("value");
            jQuery.ez('xrowcaptcha::compareResult', { 'inputresult':inputresult, 'hash_cap':hash_cap }, function(e) {
               select=e.content;
                if(select)
                {
                    document.xrowCaptchaSuccess = true;
                    jQuery('.log2').hide();
                    jQuery('.log1').show();
                    return true;
                   
                }else 
                {
                    jQuery('.log2').show();
                    jQuery('.log1').hide();
                    return false;
                }
            });
       });
});