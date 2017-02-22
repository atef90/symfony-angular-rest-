<?php


namespace FullStack\Utils\Trt;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;


trait RequestUtils
{

    public function validateParams(Request $request, $keys,$httpVerb="GET")
    {
        if($httpVerb =="GET"){
            $params=$request->query->all(); //to get all GET params
        }else{
            $params=$request->request->all(); //to get all POST params
        }

        if (!is_array($params)) throw new Exception("The params are not formed");

        $missedKeys=array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $params)){
                $missedKeys[]=$key;
            }
        }

        if(count($missedKeys) > 0){
            throw new Exception("These fields : " . implode(",", $missedKeys) . " are missings");
        }
        return true;
    }

}