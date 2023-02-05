<?php

namespace Core\Domain\Entity\Traits;

use Exception;

trait MagicMethodsTrait
{
    public function __get($property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        $classname = get_class($this);
        throw new Exception("this property not found in {$classname}.");
    }

    public function update(array $values)
    {
        foreach ($values as $property => $value) {
            if (isset($this->{$property})) {
                $this->{$property} = $value;
                if (isset($this->validate)) $this->validate();
               
            }
        }
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return (string) $this->createdAt->format("Y-m-d H:i:s");
    }
}
