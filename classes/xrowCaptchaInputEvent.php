<?php

class xrowCaptchaInputEvent
{

    /**
     * request/input event listener
     */
    static public function input()
    {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            if( xrowCaptcha::isTrusted() )
            {
                return true;
            }
            
            $captcha_ini = eZINI::instance( 'xrowcaptcha.ini' );
            $path = $_SERVER['REQUEST_URI'];
            
            if( $captcha_ini->hasVariable( 'Settings', 'ExcludeURLs' ) && $captcha_ini->variable( 'Settings', 'Type' ) == "exclude" )
            {
                $excludes = $captcha_ini->variable( 'Settings', 'ExcludeURLs' );
                foreach ( $excludes as $exclude )
                {
                    if ( strstr( $path, $exclude) == false)
                    {
                        return true;
                    }
                }
            }
            else if( $captcha_ini->hasVariable( 'Settings', 'IncludeURLs' ) && $captcha_ini->variable( 'Settings', 'Type' ) == "include" )
            {
                $includes = $captcha_ini->variable( 'Settings', 'IncludeURLs' );
                foreach ( $includes as $include )
                {
                    //quick hack... functionality is nonsense anyway
                    if ( strstr( $path, "/ezjscore/call/" ))
                    {
                        return true;
                    }
                    if ( strstr( $path, $include) != false)
                    {
                        return true;
                    }
                }
            }
            
            if( isset( $_POST['xrowCaptchaHash'] ) )
            {
                $db = eZDB::instance();
                $hash = $db->escapeString( $_POST['xrowCaptchaHash'] );
                $input = (int) $_POST[$hash];
                $result = eZPersistentObject::fetchObject( xrowCaptchaResult::definition(), null, array( 
                    'hash' => $hash 
                ) );
                if ( isset( $result ) && $result->result === $input )
                {
                    // eZPersistentObject::removeObject(xrowCaptchaResult::definition(), null, array( 'hash' =>$hash ) );
                    return true;
                }
                
            }
            throw new Exception("Please solve the captcha.");
        } 
        return true;
  }
}