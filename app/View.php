<?php
Class View {

	public $layout = 'layout';
	public $viewPath = 'views';

	protected $name;
	protected $params;


	public function __construct($name, $params) {
		$this->name = $name;
		$this->params = $params;
	}

	/**
	 * render view file
	 *
	 * @return string
	 * @throws Exception
	 */
	public function render() {
		ob_start();
		ob_implicit_flush(false);
		extract($this->params, EXTR_OVERWRITE);
		$layout = $this->findFile($this->layout);
		require($layout);
		return ob_get_clean();
	}

	public function getBaseUrl() {
		return BASE_URL;
	}


	/**
	 * Find view file
	 *
	 * @param $viewName
	 * @return string
	 * @throws Exception
	 */
	protected function findFile($viewName) {
		$path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . $this->viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
		if(!is_file($path)){
			throw new Exception('Не найдено файл ' . $path);
		}
		return $path;
	}


	/**
	 * required view to layout
	 *
	 * @throws Exception
	 */
	protected function getContent() {
		$contentFile = $this->findFile($this->name);
		extract($this->params, EXTR_OVERWRITE);
		require($contentFile);
	}


}