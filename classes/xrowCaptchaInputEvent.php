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
            if ( eZINI::instance( 'xrowcaptcha.ini' )->hasVariable( 'Settings', 'ExcludeURLs' ) )
            {
                $excludes = eZINI::instance( 'xrowcaptcha.ini' )->variable( 'Settings', 'ExcludeURLs' );
                foreach ( $excludes as $exclude )
                {
                    $path = $_SERVER['REQUEST_URI'];
                    if ( in_array( $path,$exclude) == false) 
                    { 
                        return true;
                    }
                }
            }
            
            if ( xrowCaptcha::isTrusted() )
            {
                return true;
            }
            

            if ( isset( $_POST['xrowCaptchaHash'] ) )
            {
                $db = eZDB::instance();
                $hash = $db->escapeString( $_POST['xrowCaptchaHash'] );
                $input = (int) $_POST[$hash] ;
                $result = eZPersistentObject::fetchObject( xrowCaptchaResult::definition(), null, array( 
                    'hash' => $hash 
                ) );
                if ( isset( $result ) && $result->result === $input )
                {
                    // ZPersistentObject::removeObject(xrowCaptchaResult::definition(), null, array( 'hash' =>$hash ) );
                    return true;
                }
                
            }
            throw new Exception("Please solve the captcha.");
        } 
        return true;
  }
}