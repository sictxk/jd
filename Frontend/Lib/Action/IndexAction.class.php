<?php
header("Content-type: text/html; charset=utf-8");
class IndexAction extends Action {
    public function index(){

        $this->assign('user_info',$_SESSION['user_info']);

        $this->display('index');
    }
}