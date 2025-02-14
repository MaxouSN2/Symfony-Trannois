<?php

namespace App\Entity;

class File
{
    private $name;
    private $size;
    private $uploadDate;

    public function __construct($name, $size)
    {
        $this->name = $name;
        $this->size = $size; // La taille en Mo par exemple
        $this->uploadDate = new \DateTime();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getUploadDate(): \DateTime
    {
        return $this->uploadDate;
    }
}
