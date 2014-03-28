<?php namespace Customerio;

class Response {

    /**
     * HTTP Status Code
     * @var integer
     */
    protected $code;

    /**
     * Message
     * @var String
     */
    protected $message;

    /**
     * Successful Request (or not)
     * @var bool
     */
    protected $success = false;

    /**
     * Create a new Response
     * @param integer $code
     * @param string $message
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;

        $this->parseCode($code);
    }

    /**
     * Whether request is successful
     * or not
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * Return status code message
     * @return String
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Parse code to determine
     * success
     * @param $code
     */
    protected function parseCode($code)
    {
        switch($code)
        {
            case 200 :
                $this->success = true;
                break;
            default :
                $this->success = false;
                break;
        }
    }
}
