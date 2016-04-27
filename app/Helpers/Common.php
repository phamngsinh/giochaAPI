<?php
if (! function_exists ( 'toArrayCamel' )) {
    function toArrayCamel($apiResponseArray)
    {
        $keys = array_map(function ($i) use (&$apiResponseArray) {
            if (is_array($apiResponseArray[$i])) {
                $apiResponseArray[$i] = $this->toArrayCamel($apiResponseArray[$i]);
            }

            $parts = explode('_', $i);
            return array_shift($parts) . implode('', array_map('ucfirst', $parts));
        }, array_keys($apiResponseArray));

        return array_combine($keys, $apiResponseArray);
    }
}
if (! function_exists ( 'makeResponse' )) {

    function makeResponse($result, $message,$code = \Illuminate\Http\Response::HTTP_OK, $status = true, $is_api = false)
    {
        $rs = [
            'success' => $status,
            'message' => $message,
            'code' => $code,
        ];
        if (\Illuminate\Support\Facades\Input::get('limit', 0) == 0) {
            $rs['data'] = $result;
        } elseif ($is_api) {
            $rs = is_array($result) ? array_merge($result, $rs) : $rs;
        } else {
            $rs['data'] = $result;
        }

        return response()->json($rs);
    }
}


/**
 * Build the top level navigation
 *
 * @return HTML list items (li) as string
 */
function renderMenu()
{
    return app('App\Jobs\Navigation\Builder')->build()->render();
}

/**
 * Recursively convert all keys of an array from snake_case to camelCase or CamelCase
 * Based on the excellent post here: http://www.mendoweb.be/blog/php-convert-string-to-camelcase-string/
 *
 * @param mixed $array
 * @param boolean $ucfirst
 */
if (!function_exists('array_camel_case')) {
    /**
     * @param $array
     * @param bool $ucfirst
     * @return array|bool
     */
    function array_camel_case($array, $ucfirst = false)
    {
        if (!is_array($array)) {
            return false;
        }

        $return = array();
        foreach ($array as $key => $value) {
            if (strpos($key, '_') !== false) {
                $newKey = str_replace(' ', '', ucwords(trim(preg_replace('/[^a-z0-9]+/i', ' ', $key))));
                if (!$ucfirst) {
                    $newKey = lcfirst($newKey);
                }
            } else {
                $newKey = $key;
            }
            if (is_array($value)) {
                $newValue = array_camel_case($value, $ucfirst);
            } else {
                $newValue = $value;
            }
            $return[$newKey] = $newValue;
        }

        return $return;
    }
}

/**
 * Specifically deal with duplicated array of pivots in a N-N relationship
 * for example we have an array of pivots like this:
 *  [
 *      [
 *          'id' => 1,
 *          'pivot' => [
 *              'role': 5,
 *          ]
 *      ],
 *      [
 *          'id' => 1,
 *          'pivot' => [
 *              'role': 6,
 *          ]
 *      ],
 *  ]
 *  After passed to this function, it'll become:
 *  [
 *      [
 *          'id' => 1,
 *          'pivot' => [
 *              'role': [5, 6],
 *          ]
 *      ]
 *  ]
 *
 * @param array $array
 * @param string $pivotField
 * @param string $idField
 * @param string $pivotKey
 */
if (!function_exists('array_merge_pivot')) {
    /**
     * @param $array
     * @param $pivotField
     * @param string $idField
     * @param string $pivotKey
     * @return mixed
     */
    function array_merge_pivot($array, $pivotField, $idField = 'id', $pivotKey = 'pivot')
    {
        $duplicatedElements = array();
        // Since a contact can have many roles in a developer so we need to do a merge with
        // records that belongs to the same contact and developer but the role is different
        foreach ($array as $key => &$element) {
            if (!isset($currentElementId) || $currentElementId != $element->$idField) {
                $currentElementId = $element->$idField;
                $currentKey = $key;
            } elseif ($currentElementId == $element->$idField) {
                if (!is_array($array[$currentKey]->$pivotKey->$pivotField)) {
                    $array[$currentKey]->$pivotKey->$pivotField = array($array[$currentKey]->$pivotKey->$pivotField);
                }

                $array[$currentKey]->$pivotKey->$pivotField = array_merge(
                    $array[$currentKey]->$pivotKey->$pivotField,
                    array($element->$pivotKey->$pivotField)
                );
                $duplicatedElements[] = $key;
            }
        }

        foreach ($duplicatedElements as $key) {
            unset($array[$key]);
        }

        return $array;
    }
}


if (!function_exists('table_camel_case')) {
    /**
     * @param null $tableField
     * @return null|string
     */
    function table_camel_case($tableField = null)
    {
        $camelCase = null;
        if (!$tableField) {
            return $camelCase;
        }

        if (strpos($tableField, '.')) {
            $fields = explode('.', $tableField);
            $camelCase = camel_case($fields[1]);
        } else {
            $camelCase = camel_case($tableField);
        }

        return $camelCase;
    }
}

if (!function_exists('convert_time_u_to_carbon')) {
    /**
     * @param $timestamps
     * @param string $format
     * @return \Carbon\Carbon|null|string
     */
    function convert_time_u_to_carbon($timestamps, $format = 'Y-m-d h:i:s')
    {
        $valid = date('Y', $timestamps);
        $time = null;
        if ($valid == 1970) {
            return $time;
        }
        $dateTime = date($format, $timestamps);

        $time = new \Carbon\Carbon($dateTime);

        return $time;//d/m/Y,h:ia

    }
}
if (!function_exists('compare_datetime_carbon')) {
    /**
     * @param $date1
     * @param $data2
     * @return mixed
     */
    function compare_datetime_carbon($date1, $date2)
    {

        $date1 = isset($date1->year) ? $date1 : convert_time_u_to_carbon($date1);
        $date2 = isset($date2->year) ? $date2 : convert_time_u_to_carbon($date2);
        return $date1->diffInDays($date2, false);


    }
}
