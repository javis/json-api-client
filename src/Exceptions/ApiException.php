<?php
namespace Javis\JsonApi\Exceptions;

class ApiException extends \Exception
{
    protected $errors = [];

     public function __construct($errors = [], $status = 0)
     {
         $message = "";

         $this->errors = $errors;

         if (!empty($errors)) {
             $error = array_shift($errors);

             if (isset($error->title)) {
                 $message = $error->title;
             }

             if (isset($error->detail)) {
                 $message .= (empty($message)) ? $error->detail : ": $error->detail";
             }
         }

         if (empty($message)) {
             $message = "API Server Responded with error";

             if ($status) {
                 $message .= " $status";
             }
         }

         // make sure everything is assigned properly
        parent::__construct($message, $status);
     }

     public function getErrors()
     {
         return $this->errors;
     }

}
