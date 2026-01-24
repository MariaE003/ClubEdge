<?php 
class UserController extends BaseController{
    public function __construct(){
        parent::__construct();
        $this->requireRole1('admin');
    }

    
}