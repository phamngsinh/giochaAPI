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
