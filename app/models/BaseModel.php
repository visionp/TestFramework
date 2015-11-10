<?php

namespace app\models;

use app\components\Storage;

Class BaseModel {
	private $errors = [];


	/**
	 * Validate model and fill errors
	 *
	 * @return bool
	 */
	public function validate() {
		$attributes = $this->getAttributes();

		foreach($attributes as $name => $attribute) {
			if(empty($this->$name)) {
				$this->errors[$name][] = 'Поле ' . $name . ' обязательно к заполнению';
			} else {
				if($name == 'email' && !preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $this->email)) {
					$this->errors[$name][] = 'Поле ' . $name . ' содержит невалидный email';
				}
			}

		}
		return count($this->errors) == 0;
	}


	/**
	 * Load data to model
	 *
	 * @param $data
	 */
	public function load($data) {
		foreach($this->getAttributes() as $name => $val) {
			if(isset($data[$name])) {
				$this->$name = $data[$name];
			}
		}
	}


	/**
	 * Save data model
	 *
	 * @return bool
	 */
	public function save() {
		$storage = new Storage();
		$data = [];
		foreach($this->getAttributes() as $name => $val) {
			$data[$name] = $val;
		}
		$storage->add($data);
		return true;
	}


	/**
	 * Get attributes model
	 *
	 * @return array
	 */
	public function getAttributes() {
		$attr = get_object_vars($this);
		unset($attr['errors']);
		return $attr;
	}


	/**
	 * Return after validate
	 *
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}


}