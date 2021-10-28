<?php

namespace Mubbi;

class ControllerSobreRepresentadas extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/representadas');

    $this->document->setTitle('Representadas - Multibio Soluções Laboratoriais');

    $data['pagina'] = $this->func->getPage(1);  

    $data['selected_r'] = isset($this->request->get['r']) && $this->request->get['r'] !== '' ? $this->request->get['r'] : null;

    // echo "<pre>";
    //   print_r($data['pagina']);
    // echo "</pre>";
    // exit;

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/representadas', $data));
  }
}
