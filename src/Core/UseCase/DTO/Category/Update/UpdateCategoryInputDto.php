<?php

namespace Core\UseCase\DTO\Category\Update;

class UpdateCategoryInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public ?bool $isActive = null,
    ) {
    }

    public function getdata(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if (!is_null($this->description)) {
            $data['description'] = $this->description;
        }
        
        if (!is_null($this->isActive)) {
            $data['isActive'] = $this->isActive;
        }

        return $data;
    }
}
