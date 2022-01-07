<?php

namespace App\Entity;

abstract class AbstractEntity
{
    public function __construct(array $data)
    {

        //Hydratation des propriétés du user
        foreach ($data as $propertyName => $propertyValue) {
            $parts = array_map(function (string $part) {
                return ucfirst(strtolower($part));
            }, explode('-', $propertyName));

            $setterName = 'set' . implode('', $parts);

            if (method_exists($this, $setterName)) {
                $this->$setterName($propertyValue);
            }
        }
    }
}

//$user = new User([
//    'username' => 'Toto'
//]);