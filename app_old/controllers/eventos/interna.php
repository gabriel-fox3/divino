<?php

namespace Mubbi;

class ControllerEventosInterna extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Eventos');

    $this->document->addStyle('pages/eventointerna');


    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $data['conheca'] = $this->load->view('includes/conheca');

    $this->response->setOutput($this->load->view('eventos/interna', $data));
  }
}
