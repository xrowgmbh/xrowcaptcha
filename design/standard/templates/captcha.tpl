{literal}
<script type="text/javascript">
    $(document).ready(function(){
       $(".log1").hide();
       $(".log2").hide();
    });
</script>
{/literal}

<div class="mat_captcha">

    <label class="ca_title">{"Security challenge"|i18n( 'design/xrowcaptcha' )} ({"required"|i18n( 'design/xrowcaptcha' )})</label>

    <p class="ca_frage">{"Please solve the following challenge."|i18n( 'design/xrowcaptcha' )}</p>
 
    <div class="ca_captcha">
        <div class="ca_image">
          <span class="ca_challange"> {$capt_text.exercise.var1} {$capt_text.exercise.operator} {$capt_text.exercise.var2} </span> =<div class="test"></div>
        </div>
        <input type="hidden" name="xrowCaptchaHash" value="{$hash}" />
        <input type="text" id="solution" name="{$hash}" value="" />

        <button type="button" id="code" data-hash="{$hash}" title="Get a new challenge." >{'New challenge'|i18n('design/xrowcaptcha')}</button>
      
        <p class="log1" >{'Challenge successfully solved.'|i18n('design/xrowcaptcha')}</p>
        
        <p class="log2">{'Challenge not solved.'|i18n('design/xrowcaptcha')}</p>
    </div>

</div>