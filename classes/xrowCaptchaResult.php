<?php
class xrowCaptchaResult extends eZPersistentObject
{
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'id',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "hash" => array( 'name' => "hash",
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         "result" => array( 'name' => 'result',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "createtime" => array( 'name' => "createtime",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ) ),
                      'function_attributes' => array(  ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "xrowCaptchaResult",
                      "name" => "xrowcaptcha_result" );
    }
}