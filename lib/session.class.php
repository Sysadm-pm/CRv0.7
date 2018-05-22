<?php

class Session
{
    protected static $flash_message;

    public static function setFlash($massage)
    {
        self::set('flash',$massage);
        //self::set('location',App::getRouter()->getAction() );
        //var_dump(App::getRouter()->getAction());
    }

    public static function hasFlash()
    {
        return !is_null( self::get('flash'));
    }

    public static function flash()
    {
        echo self::get('flash');
    }

    public static function unflash()
    {

            self::delete('flash');

    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        if(isset($_SESSION[$key]) )
        {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function delete($key)
    {
        if(isset($_SESSION[$key]) )
        {
            unset( $_SESSION[$key]);
        }
    }
    public static function destroy()
    {
        session_destroy();
    }


}