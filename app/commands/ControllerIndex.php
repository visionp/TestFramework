<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:33
 */

namespace app\commands;


use PHPHtmlParser\Dom;

class ControllerIndex extends ControllerBaseConsole
{
    protected $parser;
    protected $baseUrl = 'http://priceofficials.com';
    protected $parsedUrls = [];

    public function actionIndex()
    {
        ini_set('memory_limit', '-1');

        $this->parsePage($this->baseUrl);
        return $this->renderConsole(print_r(array_filter($this->parsedUrls), 1));
    }

    protected function parsePage($url)
    {
        if($this->isShopLink($url)) {
            $url = $this->baseUrl . '/' . $url;
            $url = str_replace('//', '/', $url);
        }

        if(!key_exists($url, $this->parsedUrls) && count($this->parsedUrls) < 40) {
            $this->echoToConsole("Parsing url: {$url}");

            /**
             * @var \PHPHtmlParser\Dom $domHtml
             */
            $domHtml = $this->getParser()->loadFromUrl($url);
            $this->parsedUrls[$url] = $this->getProductInfo($domHtml);

            foreach($this->getAllLinks($domHtml) as $a) {
                $this->parsePage($a);
            }
            return $this->parsedUrls;
        }
        return [];
    }

    /**
     * @param Dom $domHtml
     * @return array
     */
    public function getProductInfo(\PHPHtmlParser\Dom $domHtml)
    {
        $result = [];
        foreach($domHtml->find('div.lt-product-details-page div.product-info ') as $item) {
            $result[] = [
                'name' => $this->getDetails($item),
                'price' => $this->getPrice($item),
            ];
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

    protected function isShopLink($link)
    {
        $baseUrl = str_replace('/', '\/', $this->baseUrl);
        $regularExpress = "/^({$baseUrl})?(\/?product).{2,}/i";
        return preg_match($regularExpress, $link);
    }
}