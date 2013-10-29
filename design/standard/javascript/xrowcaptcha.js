var select,attval;
var xrowCaptchaSuccess = false;
var ent = true;
var con = false;

jQuery(document).ready(function($) { 
       $(function(){
           $('#noScriptPrompt').hide();
           $('#nosubmit').css("visibility","visible");
       });
        var z= $("form").length;
        jQuery("form").each(function(index) 
        {
            if(jQuery(this).attr('action') !== undefined )
            {
                var zeichenkette=jQuery(this).attr('action');
            }else{var zeichenkette="";}
            
            var s=zeichenkette.indexOf("http");
            
            if(s>=0)
            { 
                ent=false;
            }else{
                ent=true;
            }
            
            if (typeof includeObjects !== 'undefined') {
                for(var i=0;i<includeObjects.length;i++)
                {
                    if(zeichenkette.indexOf(includeObjects[i])>0)
                    {
                        con=true;
                    }
                    else
                    {
                        con=false;
                        break;
                    }
                }
            }
            else if (typeof excludeObjects !== 'undefined') {
            {
                for(var i=0;i<excludeObjects.length;i++)
                {
                    if(zeichenkette.indexOf(excludeObjects[i])>=0)
                    {
                        con=false;
                        break;
                    }else{con=true;}
                  
                }
            }
            
            if(con == true && ent == true && zeichenkette !== "")
            { 
                if( !(jQuery('.xrow-captcha').length > 0) || z>0)
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
                    if(!(jQuery(this).children('.xrow-captcha').length>0))
                    {
                        jQuery(this).prepend('<div class="xrow-captcha"></div>');
                    }
                    
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
                     z--;
                    
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