<?php
header("Content-type: text/html; charset=utf-8");
class PreviewAction extends Action {
    public function index(){
        $this->display('index');
    }
}