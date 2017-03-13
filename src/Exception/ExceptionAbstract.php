<?php

    namespace RatePAY\Exception;

    abstract class ExceptionAbstract extends \Exception
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct($message);
        }

        /**
         * @return string
         */
        public function __toString()
        {
            //return __CLASS__ . ":" . $this->message;
            return $this->message;
        }
    }
