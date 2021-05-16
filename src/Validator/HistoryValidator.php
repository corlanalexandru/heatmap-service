<?php


namespace App\Validator;


use App\Entity\Types;

class HistoryValidator
{

    public function validate($data){
        $requiredFields = ['url','customer','type'];
        $errors = [];
        foreach ($requiredFields as $field) {
            if(!isset($data[$field]) || $data[$field] === '') {
                $errors[] = 'The key '.$field.' must be specified and not empty!';
            }
        }

        if(!$data['type'] instanceof Types) {
            $errors[] = 'The specified type does not exist!';
            return $errors;
        }

        return $errors;
    }

}