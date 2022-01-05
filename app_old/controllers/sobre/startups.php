<?php

namespace Mubbi;

class ControllerSobreStartups extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/startups');

    $this->document->setTitle('Startups - Base27');
    
    $data['header_startups'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/startups', $data));
  }
}
