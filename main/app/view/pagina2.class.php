<?php
class pagina2 extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/mapa_mental5.html');
        
        // replace the main section variables
        $this->html->enableSection('main');
        
        parent::add($this->html);
    }
}
