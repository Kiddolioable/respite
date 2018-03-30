<?php defined('SYSPATH') or die('No direct script access.');

//Sets page views
class Controller_Page extends Controller_Template
{
    public $template = 'template';
    
    public function action_index()
    {
        $view = new View('page/index');
        $this->template->set('content', $view);
    }
    
    public function action_configurazioneutenti()
    {
        $view = new View('page/configurazioneutenti');
        $this->template->set('content', $view);
    }

    public function action_contact()
    {
        $view = new View('page/contact');
        $this->template->set('content', $view);
    }
    
    public function action_logout()
    {
        $view = new View('page/logout');
        $this->template->set('content', $view);
    }
    
    public function action_terminal_server()
    {
        $view = new View('page/terminal_server');
        $this->template->set('content', $view);
    }
    
    public function action_assoc_user_ts(){
        $view = new View('page/assoc_user_ts');
        $this->template->set('content', $view);
    }
}