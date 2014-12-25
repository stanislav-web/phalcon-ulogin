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
            } else {
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
    public static function stringResolve($data)
    {

        $array = [];

        $data = explode(',', trim($data));

        foreach ($data as $provider) {

            if (mb_strpos($provider, "=") !== false) {

                $bool = explode('=', $provider);

                if ($bool[1] === 'true') {
                    $array['required'][] = $bool[0];
                } else {
                    $array['hidden'][] = $bool[0];
                }
            } else {
                // collect to required
                $array['required'][] = $provider;
            }
        }

        return $array;
    }

}