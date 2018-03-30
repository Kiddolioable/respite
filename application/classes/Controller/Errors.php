<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Template {


    public function action_404() {

        $view = new View('errors/404');

        $this->template->set('content', $view);


    }

}