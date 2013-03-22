<?php

class Person
{
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
         *
         */
	public function whatYourName()
	{
		echo 'my name is ' . $this->name;
		echo "\n";
	}
}

$takehara = new Person('takehara');
$hamaco = new Person('hamaco');
$hamaco->whatYourName();
$takehara->whatYourName();
