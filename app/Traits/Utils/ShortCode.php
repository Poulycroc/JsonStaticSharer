<?php

namespace App\Traits\Utils;

use App\Traits\Utils\Arr as ArrUtils;
use Illuminate\Support\Arr;

/**
 * Array Class with more features.
 *
 * You can use it as stand-alone in any Php Project
 *
 * @category Utils
 *
 * @author   Daniel Sum <daniel@cherrypulp.com>
 * @author   Stéphan Zych <stephan@cherrypulp.com>
 * @license  http://opensource.org/licenses/MIT MIT License
 *
 * @see     http://arx.xxx/doc/Arr
 */
class ShortCode
{
    public static function shortcode(string $string, $data = [], $params = ['nl2br' => false, 'delimiters' => ['{', '}']]): string
    {
        ArrUtils::mergeWithDefaultParams($params);

        if ($params['nl2br']) {
            $string = nl2br($string);
        }

        $data = array_dot($data);

        $string = Utils::smrtr($string, $data, $params['delimiters']);

        $string = (new BlockSt())->compile($string);

        return $string;
    }

    /**
     * Do a shortcode and translate labels.
     *
     * @param $string
     * @param $data
     * @param $params
     * @param null $locale
     *
     * @return mixed|string
     */
    public static function st($string, $data = [], $params = [], $locale = null)
    {
        return self::shortcode(__($string, array_dot($data), $locale), array_dot($data), $params);
    }
}
