<?php
Class PagenotfoundController extends BaseCtrl
{
    function __construct($class,$request)
    {
        $this->class = strtolower(str_replace('Controller','',$class));
        
        $this->request = $request['Type'] = 'xhtml';
    }
    
    function index()
    {
        header("HTTP/1.0 404 Not Found");
        $this->renderView(null,array('Controller'=>'pagenotfound','Type'=>'xhtml','Method'=>'index'),'404 : Page Not Found');
    }
}
?>