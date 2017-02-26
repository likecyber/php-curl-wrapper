<?php

class Curl {
	public $handle;

	public $init_options = array();
	public $options = array();
	public $raw_options = array();
	public $raw_strings = array();

	public $errno = NULL;
	public $error = NULL;
	public $info = NULL;
	public $result = NULL;

	public function __construct($options = NULL) {
		$this->init($options);
	}

	public function __call($function, $arguments) {
		if (function_exists("curl_".$function) && substr($function, 0, 5) !== "multi" && substr($function, 0, 5) !== "share") {
			if (in_array($function, array("copy_handle", "errno", "error", "escape", "pause", "unescape"))) {
				array_unshift($arguments, $this->handle);
			}
			return call_user_func_array("curl_".$function, $arguments);
		} else {
			if (count($arguments) === 1) {
				if ($this->__set($function, $arguments[0])) {
					return $this;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}

	public function __set($option, $value) {
		$_option = $option;
		if (is_string($option) && substr($option, 0, 8) !== "CURLOPT_") {
			$option = "CURLOPT_".$option;
		}
		if (is_string($option) && defined($option)) {
			$option = constant($option);
		}
		if (is_int($option)) {
			$this->setopt($_option, $value);
			return true;
		} else {
			return false;
		}
	}

	private function _setup() {
		$this->close();
		$this->options = array();
		$this->raw_options = array();
		$this->raw_strings = array();
		$this->handle = curl_init();
		$this->RETURNTRANSFER = true; // Override RETURNTRANSFER option.
		if (file_exists(__DIR__."/cacert.pem")) { // If cacert.pem exists, Override SSL_VERIFYPEER and CAINFO options.
			$this->SSL_VERIFYPEER = true;
			$this->CAINFO = realpath(__DIR__."/cacert.pem");
		}
	}

	public function init($options = NULL) {
		$this->_setup();
		$this->init_options = array();
		if (is_object($options) && isset($options->options) && is_array($options->options)) {
			$this->init_options = $options->options;
		} elseif (is_array($options)) {
			$this->init_options = $options;
		} elseif (is_string($options)) {
			$this->init_options = array("URL" => $options);
		}
		$this->setopt_array($this->init_options);
		return $this;
	}

	public function reset() {
		$this->_setup();
		$this->setopt_array($this->init_options);
		return $this;
	}

	public function setopt($option, $value) {
		$_option = $option;

		if (is_string($option) && substr($option, 0, 8) !== "CURLOPT_") {
			$option = "CURLOPT_".$option;
		}
		if (is_string($option) && defined($option)) {
			$option = constant($option);
		}
		if (isset($this->raw_str[$option])) {
			unset($this->options[$this->raw_str[$option]]);
		}
		curl_setopt($this->handle, $option, $value);
		$this->options[$_option] = $value;
		$this->raw_options[$option] = $value;
		$this->raw_strings[$option] = $_option;
		return $this;
	}

	public function setopt_array($options) {
		foreach ($options as $option => $value) {
			$this->setopt($option, $value);
		}
		return $this;
	}

	public function getinfo($option = NULL) {
		if (is_string($option) && substr($option, 0, 9) !== "CURLINFO_") {
			$option = "CURLINFO_".$option;
		}
		if (is_string($option) && defined($option)) {
			$option = constant($option);
		}
		if ($option === NULL) {
			return curl_getinfo($this->handle);
		} else {
			return curl_getinfo($this->handle, $option);
		}
	}

	public function exec() {
		$this->result = curl_exec($this->handle);
		$this->errno = $this->errno();
		$this->error = $this->error();
		$this->info = $this->getinfo();
		return $this->result;
	}

	public function close() {
		@curl_close($this->handle);
		return true;
	}

}

?>
