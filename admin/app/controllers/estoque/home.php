<?php

namespace Mubbi;

class ControllerEstoqueHome extends BaseController {
  public function index() {

    $this->document->setTitle('Estoque');

    $data['user_info'] = $this->session->data['user_info'];
    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('estoque/list', $data));
  }
}
