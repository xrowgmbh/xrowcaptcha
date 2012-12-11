<?php

class xrowCaptchaJscoreFunctions extends ezjscServerFunctions
{
    public static function excludes( $args )
    {
        if( eZINI::instance( 'xrowcaptcha.ini' )->hasVariable( 'Settings', 'ExcludeURLs' ) )
        {
            return "var excludeObjects =" . json_encode( eZINI::instance( 'xrowcaptcha.ini' )->variable( 'Settings', 'ExcludeURLs' ));
        }
        
    }
    
   /* public static function checkIfShowCaptcha()
    { 
        $xrowcaptcha = eZINI::instance( 'xrowcaptcha.ini' );
        $excludeObjects = array();
        if( $xrowcaptcha->hasVariable( 'Settings', 'ExcludeURLs' ) )
        {
            $excludeObjects = $xrowcaptcha->variable( 'Settings', 'ExcludeURLs' );
        }
        return array( 'excludeObjects' => $excludeObjects );
    }*/

    public static function loadCaptcha()
    {
        if ( xrowCaptcha::isTrusted() )
        {   
            return '';
        }else
        {
            $capt_text=xrowCaptcha::generateCaptcha();
            $hash=md5( rand () );
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

    public static function compareResult($inputresult,$hash_cap)
    {
        if($inputresult==null)
        {
            $inputresult= $_POST['inputresult'];
        }
        if($hash_cap==null)
        {
            $hash_cap = $_POST['hash_cap'];
        }
        
        $result = eZPersistentObject::fetchObject( xrowCaptchaResult::definition(), null, array( 'hash' => $hash_cap ) );
        
        $dbresult=$result->attribute('result');
        if ($dbresult == $inputresult)
        { 
            $http = eZHTTPTool::instance();
            $http->setSessionVariable('xrowCaptchaSolved', '1');
            return true;
        }else 
        {
            $http = eZHTTPTool::instance();
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
