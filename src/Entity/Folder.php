<?php

namespace App\Entity;

class Folder
{
    private $id;
    private $name;
    private $user;
    private $files; // Tableau pour stocker les fichiers associés au dossier

    public function __construct($name)
    {
        $this->name = $name;
        $this->files = []; // Initialiser le tableau de fichiers
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    // Getter et setter pour les fichiers
    public function getFiles(): array
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        $this->files[] = $file;
        return $this;
    }
}
