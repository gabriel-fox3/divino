<?php

namespace Mubbi;

class ControllerEstoqueHome extends BaseController {
  public function index() {

    $this->document->setTitle('Estoque');
    $this->document->addStyle('css/styles/estoque');

    $this->document->addStyle('js/plugins/iconpicker/css/fontawesome-iconpicker');
    $this->document->addScript('js/plugins/iconpicker/js/fontawesome-iconpicker.min');

    $data['user_info'] = $this->session->data['user_info'];
    
    $data['add'] = $this->load->view('estoque/add', $data);
    $data['sidebar'] = $this->load->controller('common/sidebar', $data);
    $data['navbar'] = $this->load->controller('common/navbar', $data);
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('estoque/list', $data));
  }
}
