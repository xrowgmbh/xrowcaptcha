<?php

class xrowCaptchaJscoreFunctions extends ezjscServerFunctions
{
    public static function excludes( $args )
    {
        $captcha_ini = eZINI::instance( 'xrowcaptcha.ini' );
        if( $captcha_ini->hasVariable( 'Settings', 'ExcludeURLs' ) &&  count( $captcha_ini->variable( 'Settings', 'ExcludeURLs' )) > 0 )
        {
            return "var excludeObjects =" . json_encode( $captcha_ini->variable( 'Settings', 'ExcludeURLs' ));
        }
        else
        {
            return "";
        }
    }
    
    public static function includes( $args )
    {
        $captcha_ini = eZINI::instance( 'xrowcaptcha.ini' );
        if( $captcha_ini->hasVariable( 'Settings', 'IncludeURLs' ) &&  count( $captcha_ini->variable( 'Settings', 'IncludeURLs' )) > 0 )
        {
            return "var includeObjects =" . json_encode( $captcha_ini->variable( 'Settings', 'IncludeURLs' ));
        }
        else
        {
            return "";
        }
    }

    public static function loadCaptcha()
    {
        if ( xrowCaptcha::isTrusted() )
        {
            return '';
        }
        else
        {
            $capt_text = xrowCaptcha::generateCaptcha();
            $hash = md5( rand () );
            $result = new xrowCaptchaResult();
            $result->hash = $hash;
            $result->result = $capt_text['exercise']['result'];
            $result->createtime = time();
            $result->store();

            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'hash',$hash);
            $tpl->setVariable('capt_text',$capt_text);
            return $tpl->fetch( 'design:captcha.tpl' );
        }
    }

    public static function compareResult( $inputresult, $hash_cap )
    {
        $http = eZHTTPTool::instance();
        if( $inputresult == null )
        {
            if( $http->hasPostVariable( 'inputresult' ) )
                $inputresult = $http->postVariable( 'inputresult' );
        }
        if( $hash_cap == null)
        {
            if( $http->hasPostVariable( 'hash_cap' ) )
                $hash_cap = $http->postVariable( 'hash_cap' );
        }

        $result = eZPersistentObject::fetchObject( xrowCaptchaResult::definition(), null, array( 'hash' => $hash_cap ) );
        $dbresult=$result->attribute('result');
        if ($dbresult == $inputresult)
        {
            $http->setSessionVariable('xrowCaptchaSolved', '1');
            return true;
        }
        else 
        {
            $http->setSessionVariable('xrowCaptchaSolved', '0');
            return false;
        }
    }

    public static function reloadChallange()
    {
        $var = xrowCaptcha::generateCaptcha();
        $db = eZDB::instance();
        $result = eZPersistentObject::fetchObject( xrowCaptchaResult::definition(), null, array( 'hash' => $db->escapeString( $_POST['hash'] ) ) );
        $result->result = $var['exercise']['result'];
        $result->store();
        return $var['exercise']['var1'].$var['exercise']['operator'].$var['exercise']['var2'];
    }
}