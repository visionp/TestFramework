<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:33
 */

namespace app\commands;


use app\helpers\ClassBuildTableAscii;
use PHPHtmlParser\Dom;

class ControllerIndex extends ControllerBaseConsole
{
    protected $parser;
    protected $baseUrl = 'http://priceofficials.com';
    protected $parsedUrls = [];
    protected $maxPages = 0;
    protected $maxProducts = 2;
    protected $countProducts = 0;
    protected $csvNameFile = 'result-parsing.csv';
    protected $csvDelimiter = ';';

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '-1');
        $startTime = microtime(true);
        $data = array_filter($this->parsePage($this->baseUrl));
        $this->writeToCsv($data);
        $endTime = microtime(true);
        $consoleData = [
            [
                'Status' => 'done',
                'Time, s' => $endTime - $startTime,
                'Number of parsed links' => count($this->parsedUrls),
                'Number of products' => $this->countProducts,
                'Result file' => $this->getFilePath()
            ]
        ];
        $table = new ClassBuildTableAscii($consoleData);
        return $this->renderToConsole($table->asText());
    }

    /**
     * @param array $data
     */
    protected function writeToCsv(array $data)
    {
        $file = fopen($this->getFilePath(), 'w+');
        array_unshift($data, [
            'url' => 'Url',
            'name' => 'Name',
            'price' => 'Price'
        ]);
        foreach($data as $row) {
            fputcsv($file, $row, $this->csvDelimiter);
        }
        fclose($file);
    }

    protected function getFilePath()
    {
        return $filePath = MAIN_DIRECTORY . 'storage' . DIRECTORY_SEPARATOR . $this->csvNameFile;;
    }

    /**
     * @param $url
     * @return array
     */
    protected function parsePage($url)
    {
        $url = $this->prepareLink($url);
        $items = [];

        if($this->canContinue($url)) {
            $this->echoToConsole("Parsing url: {$url}");
            /**
             * @var \PHPHtmlParser\Dom $domHtml
             */
            $domHtml = $this->getParser()->loadFromUrl($url);
            $items[] = $this->isProductLink($url) ? $this->getProductInfo($url, $domHtml) : null;

            foreach($this->getAllLinks($domHtml) as $a) {
                $items = array_merge($items, $this->parsePage($a));
            }

            return $items;
        }
        return [];
    }

    /**
     * @param $url
     * @return bool
     */
    protected function canContinue($url)
    {
        $key = md5($url);
        $isCan = !key_exists($key, $this->parsedUrls);
        if($isCan && $this->maxPages > 0) {
            $isCan = $isCan && (count($this->parsedUrls) < $this->maxPages);
        }
        if($isCan && $this->maxProducts > 0) {
            $isCan = $isCan && ($this->countProducts < $this->maxProducts);
        }
        $this->parsedUrls[$key] = true;
        return $isCan;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    protected function prepareLink($url)
    {
        if($this->isShopLink($url) && !$this->hasBaseLink($url)) {
            $url = str_replace('//', '/', $url);
            $url = ltrim($url, '/');
            $url = $this->baseUrl . '/' . $url;
            $url .= preg_match('/\?/', $url) ? '&PageSpeed=noscript' : '?PageSpeed=noscript';
        }
        return $url;
    }

    /**
     * @param Dom $domHtml
     * @return array
     */
    protected function getProductInfo($url, \PHPHtmlParser\Dom $domHtml)
    {
        $result = [];
        foreach($domHtml->find('div.lt-product-details-page div.product-info') as $item) {
            $result = [
                'url' => $url,
                'name' => $this->getDetails($item),
                'price' => $this->getPrice($item),
            ];
        }
        $this->countProducts++;
        return $result;
    }

    /**
     * @param \PHPHtmlParser\Dom\HtmlNode $node
     */
    protected function getDetails($node)
    {
        return $node->find('h1.entry-title')[0]->text();
    }

    /**
     * @param \PHPHtmlParser\Dom\HtmlNode $node
     */
    protected function getPrice($node)
    {
        return $node->find('span.amount')[0]->text();
    }

    /**
     * @param Dom $domHtml
     * @return array
     */
    protected function getAllLinks(\PHPHtmlParser\Dom  $domHtml)
    {
        $result = [];
        foreach($domHtml->find('a') as $link) {
            $href = $link->href;
            if($this->isShopLink($href)) {
                $result[] = trim($href);
            }
        }
        return array_filter($result);
    }

    /**
     * @return Dom
     */
    protected function getParser()
    {
        if(empty($this->parser)) {
            $this->parser = new Dom();
        }
        return $this->parser;
    }

    /**
     * @param $link
     * @return int
     */
    protected function isShopLink($link)
    {
        $baseUrl = str_replace('/', '\/', $this->baseUrl);
        $regularExpress = "/^({$baseUrl})?(\/?product\/|product-category\/computers).{2,}/i";
        return preg_match($regularExpress, $link);
    }

    /**
     * @param $link
     * @return int
     */
    protected function isProductLink($link)
    {
        $baseUrl = str_replace('/', '\/', $this->baseUrl);
        $regularExpress = "/^{$baseUrl}\/product\/.{2,}/i";
        return preg_match($regularExpress, $link);
    }

    /**
     * @param $link
     * @return int
     */
    protected function hasBaseLink($link)
    {
        $baseUrl = str_replace('/', '\/', $this->baseUrl);
        $regularExpress = "/^{$baseUrl}.*/i";
        return preg_match($regularExpress, $link);
    }

}