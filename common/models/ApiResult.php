<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/6/18
 * Time: 11:12 PM
 */

namespace common\models;


class ApiResult
{
    public $result;
    public $error;
    public $errorMessage;

    public function __construct($result = null, $error = -1, $errorMessage = '')
    {
        $this->error = $error;
        $this->errorMessage = $errorMessage;
        $this->result = $result;
    }


    public function setResponse($response)
    {
        try {
            $this->result = $response['result'];
            $this->error = $response['error'];
            $this->errorMessage = $response['errorMessage'];
        } catch (\Exception $e) {
            var_dump('Error en set Response: ' .$e->getMessage());
        }
    }

    public function getResponse()
    {
        return ['result' => $this->result, 'error' => $this->error, 'errorMessage' => $this->errorMessage];
    }
}