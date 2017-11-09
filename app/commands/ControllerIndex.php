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
    protected $csvDelimiter = ';';

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '-1');

        $this->parsePage($this->baseUrl);
        $filePath = MAIN_DIRECTORY . 'storage' . DIRECTORY_SEPARATOR . 'result-parsing.csv';
        $data = array_filter($this->parsedUrls);
        $file = fopen($filePath, 'w+');
        $tableData = $this->makeCsvData($data);
        foreach($tableData as $row) {
            fputcsv($file, $row, $this->csvDelimiter);
        }
        fclose($file);
        $table = new ClassBuildTableAscii($tableData);
        return $this->renderConsole($table->asText());
    }

    protected function makeCsvData(array $data)
    {
        $csvData = [];
        foreach($data as $name => $row) {
            $csvData[] = [
                'url' => $name,
                'name' => $row['name'],
                'price' => $row['price']
            ];
        }
        return $csvData;
    }
    /**
     * @param $url
     * @return array
     */
    protected function parsePage($url)
    {
        $url = $this->preparedLink($url);

        if($this->canContinue($url)) {
            $this->echoToConsole("Parsing url: {$url}");
            /**
             * @var \PHPHtmlParser\Dom $domHtml
             */
            $domHtml = $this->getParser()->loadFromUrl($url);
            $this->parsedUrls[$url] = $this->isProductLink($url) ? $this->getProductInfo($domHtml) : null;

            foreach($this->getAllLinks($domHtml) as $a) {
                $this->parsePage($a);
            }

            return $this->parsedUrls;
        }
        return [];
    }

    /**
     * @param $url
     * @return bool
     */
    protected function canContinue($url)
    {
        $isCan = !key_exists($url, $this->parsedUrls);
        if($isCan && $this->maxPages > 0) {
            $isCan = $isCan && (count($this->parsedUrls) < $this->maxPages);
        }
        if($isCan && $this->maxProducts > 0) {
            $isCan = $isCan && ($this->countProducts < $this->maxProducts);
        }
        return $isCan;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    protected function preparedLink($url)
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
    protected function getProductInfo(\PHPHtmlParser\Dom $domHtml)
    {
        $result = [];
        foreach($domHtml->find('div.lt-product-details-page div.product-info') as $item) {
            $result = [
                'name' => $this->getDetails($item),
                'price' => $this->getPrice($item),
            ];
            $this->countProducts++;
        }
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
        $links = $domHtml->find('a');
        $result = [];
        foreach($links as $link) {
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