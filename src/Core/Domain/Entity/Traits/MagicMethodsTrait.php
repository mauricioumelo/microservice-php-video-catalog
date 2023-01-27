<?php

namespace Core\Domain\Entity\Traits;

use Exception;

trait MagicMethodsTrait{

    public function __get($propety){
        if(isset($this->{$propety})){
            return $this->{$propety};
        }

        $classname = get_class($this);
        throw new Exception("this propety not found in {$classname}.");
    }
}