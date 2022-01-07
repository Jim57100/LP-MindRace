<?php

namespace App\Entity\UserEntity;

use App\Entity\AbstractEntity;

class UserEntity extends AbstractEntity {

  private $id;
  private $username;
  private $password;
  private $mail;
  private $roles;
  private $firstName;
  private $lastName;
  private $createdAt;
  private $uniquId;

  public function getId() :?int
  {
    return $this->id;
  }

  public function setId($id)
  {
    return $this->id = (int) $id;
  }

  public function getUserName(): ?string
  {
    return $this->username;
  }


  public function setUserName(string $username) :string
  {
     return $this->username = $username;
  }


  public function getRoles() :?string
  {
    return $this->roles;
  }


  public function setRoles(?string $roles) :string
  {
    //De base l'utilisateur qui s'inscrit aura le role ROLE_USER
    if($roles === null) {
      return $this->roles = "ROLE_USER";
    } else {
     return $this->roles = $roles;
    }

  }


  public function getPassword() :?string
  {
    return $this->password;
  } 


  public function setPassword(string $password) :string
  {
    return $this->password = $password;
  } 

 /**
   * Get the value of email
   */ 
  public function getMail() :string
  {
    return $this->mail;
  }


  public function setMail(string $mail) :string
  {
   return $this->mail = $mail;
  }


  public function getFirstName() :string
  {
    return $this->firstName;
  }

  
  public function setFirstName(string $firstName) :string
  {
    return $this->firstName = $firstName;
  }
 

  public function getLastName() :?string
  {
    return $this->lastName;
  }

  
  public function setLastName(string $lastName) :string
  {
    return $this->lastName = $lastName;
  }

  
  public function getCreatedAt() :string
  {
    return $this->createdAt;
  }

  
  public function setCreatedAt(string $createdAt) :string
  {
    return $this->createdAt = $createdAt;
  }

  
  public function getUniquId() :string
  {
    return $this->uniquId;
  }

  
  public function setUniquId($uniquId) :?string
  {
    return $this->uniquId = $uniquId;
  }
}