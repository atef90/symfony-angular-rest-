<?php

namespace FullStack\Utils\Trt;
use Symfony\Component\HttpFoundation\Request;

trait ResponseUtils
{

    public function getSuccessResponse($data = array())
    {
        return array(
            "success" => true,
            "data" => $data
        );
    }

    public function getFailureResponse($msq, $code, $lvl = "dev")
    {
        return array(
            "success" => false,
            "errorMsg" => $msq,
            "errorCode" => $code,
            "errorLvl" => $lvl
        );
    }

    public function getApplicationBaseUrl(Request $request)
    {
        return $request->getSchemeAndHttpHost() . $request->getBasePath() . '/';
    }
}