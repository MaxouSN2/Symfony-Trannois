<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class User implements PasswordAuthenticatedUserInterface
{
    private $id;
    private $email;
    private $password;
    private $name; // Nouveau champ pour le nom de l'utilisateur
    private $plan;
    private $createdAt;
    private $folders; // Propriété pour stocker les dossiers associés

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->folders = []; // Initialisation du tableau des dossiers
    }

    // Getters et setters pour les autres propriétés...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(string $plan): self
    {
        $this->plan = $plan;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Getters et setters pour la propriété 'folders'...
    public function getFolders(): array
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): self
    {
        $this->folders[] = $folder;
        $folder->setUser($this); // Lier le dossier à l'utilisateur
        return $this;
    }

    public function removeFolder(Folder $folder): self
    {
        if (($key = array_search($folder, $this->folders, true)) !== false) {
            unset($this->folders[$key]);
            $folder->setUser(null); // Retirer la relation
        }
        return $this;
    }
}
