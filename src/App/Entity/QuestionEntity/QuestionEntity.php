<?php

namespace App\Entity\QuestionEntity;

use App\Entity\AbstractEntity;


class UserEntity extends AbstractEntity {

  
private $id;
private $label;
private $level;
private $answer;


/**
 * Get the value of id
 */ 
public function getId()
{
return $this->id;
}

/**
 * Get the value of label
 */ 
public function getLabel()
{
return $this->label;
}

/**
 * Set the value of label
 *
 * @return  self
 */ 
public function setLabel($label)
{
$this->label = $label;

return $this;
}

/**
 * Get the value of level
 */ 
public function getLevel()
{
return $this->level;
}

/**
 * Set the value of level
 *
 * @return  self
 */ 
public function setLevel($level)
{
$this->level = $level;

return $this;
}

/**
 * Get the value of answer
 */ 
public function getAnswer()
{
return $this->answer;
}


}