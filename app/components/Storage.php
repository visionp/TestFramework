<?php
namespace components;

class Storage  extends  ComponentBase {

    public function add($data) {
        $old = $this->get();
        if(is_array($old)){
            $old[] = $data;
        } else {
            $old = [$data];
        }
        $w=fopen($this->getFile(),'w');
        $text = serialize($old);
        fwrite($w,$text);
        fclose($w);
    }

    public function get() {
        if(!is_file($this->getFile())) {
            return [];
        }
        $r=fopen($this->getFile(),'r');
        $text=fread($r,filesize($this->getFile()));
        fclose($r);
        return unserialize($text);
    }

    protected function getFile() {
        $path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'storage.txt';
        return $path;
    }

}