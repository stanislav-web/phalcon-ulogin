<?php
namespace ULogin;

/**
 * ULogin parse data class
 *
 * @package   ULogin
 * @since     PHP >=5.4.28
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Parser
{

    /**
     * Resolve mapper
     *
     * @param mixed $data input data
     * @access static
     * @return array
     */
    public static function map($data) {

        if(is_array($data) === true) {

            $array = self::arrayResolve($data);
        } else {

            $array = self::stringResolve($data);

        }

        return $array;
    }

    /**
     * Resolve array data as providers
     *
     * @param array $data
     * @access static
     * @return array
     */
    public static function arrayResolve(array $data)
    {

        $array = [];

        foreach ($data as $provider => $bool) {

            if ($bool === true) {

                $array['required'][] = $provider;

            }
            else {

                $array['hidden'][] = $provider;

            }
        }

        return $array;
    }

    /**
     * Resolve string data as providers
     *
     * @param string $data
     * @access static
     * @return array
     */
    public static function stringResolve($data = '')
    {

        $data = explode(',', trim($data));

        $array = self::separate($data);

        return $array;
    }

    /**
     * Separate string
     *
     * @param array $data
     * @access static
     * @return array
     */
    private static function separate(array $data)
    {
        $array = [];

        foreach ($data as $provider) {

            if(($bool = self::isDelim($provider)) === false) {
                $array['required'][] = $provider;
            }
            else {
                if ($bool[1] === 'true') {
                    $array['required'][] = strval($bool[0]);
                } else {
                    $array['hidden'][] = strval($bool[0]);
                }
            }
        }

        return $array;

    }

    /**
     * Check if data has delimiter
     *
     * @param string $provider
     * @param string $delimiter
     * @access static
     * @return array|bool
     */
    private static function isDelim($provider, $delimiter = '=')
    {
        if (mb_strpos($provider, $delimiter) !== false) {

            $res = explode('=', $provider);

            return $res;
        }

        return false;

    }
}
