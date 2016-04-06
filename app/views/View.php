<?php
namespace app\views;

Class View {

	public $layout = 'layout';
	public $viewPath = 'views';
    protected $js = [];
    protected $jsFile = [];

	public $name;
	public $params;


	/**
	 * render view file
	 *
	 * @return string
	 * @throws Exception
	 */
	public function render()
	{
		ob_start();
		ob_implicit_flush(false);
		extract($this->params, EXTR_OVERWRITE);
		$layout = $this->findFile($this->layout);
		require($layout);
		return ob_get_clean();
	}


	/**
	 * @return string
	 */
	public function getBaseUrl()
	{
		return BASE_URL;
	}


	/**
	 * Find view file
	 *
	 * @param $viewName
	 * @return string
	 * @throws \Exception
	 */
	protected function findFile($viewName)
	{
		$path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $this->viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
		if(!is_file($path)){
			throw new \Exception('Не найдено файл ' . $path);
		}
		return $path;
	}


    public function registerJs($js, $positionEnd = true)
	{
        $k = $this->getKeyPosition($positionEnd);
        $this->js[$k][] = $js;
    }


	/**
	 * @param $js_source
	 * @param bool|true $positionEnd
	 */
    public function registerJsFile($js_source, $positionEnd = true)
	{
        $k = $this->getKeyPosition($positionEnd);
        $this->jsFile[$k][] = $js_source;
    }


	/**
	 * @param $key
	 * @return int
	 */
    public function getKeyPosition($key)
	{
        return $key ? 1 : 0;
    }


	/**
	 * @param $positionEnd
	 * @return string
	 */
    public function renderJs($positionEnd)
	{
        $js = '';
        if(count($this->js[$positionEnd])){
            $js = '<script>';
            $js .= implode(' ', $this->js[$positionEnd]);
            $js .= '</script>';
        }
        return $js;
    }


	/**
	 * required view to layout
	 *
	 * @throws Exception
	 */
	protected function getContent()
	{
		$contentFile = $this->findFile($this->name);
		extract($this->params, EXTR_OVERWRITE);
		require($contentFile);
	}

}