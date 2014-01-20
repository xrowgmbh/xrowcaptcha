<?php
class xrowCaptcha
{
    public static function isTrusted()
    {
        $http = eZHTTPTool::instance();
        if ( $http->sessionVariable('xrowCaptchaSolved', '0' ) === '1' )
        {
            return true;
        }
        elseif ( eZUser::instance()->isLoggedIn() )
        {
            $threedays= time() - 3600 * 72;
            if ( eZUser::instance()->lastVisit() > $threedays)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function generateCaptcha()
    {
        $http = eZHTTPTool::instance();
        $first_var = rand( 10, 90 );
        $second_var = rand( 0, 9 );
        $operator = ( rand( 0, 1 ) == 0 ) ? ' + ' : ' - ';
        $exercise = $first_var . $operator . $second_var;
        eval("\$result = " . $exercise . ';');
        $vars['exercise'] = array('var1' => $first_var,
                                  'var2' => $second_var,
                                  'operator' => $operator,
                                  'result' => $result
                                  );
        return $vars;
    }
}