<?php

namespace Mubbi;

class ControllerSobreQuem_somos extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/quemsomos');

    $this->document->setTitle('Quem somos - Base27');

    $data['conheca'] = $this->load->view('includes/conheca');
    $data['mantenedores'] = $this->load->view('includes/mantenedores');
    $data['membros'] = $this->load->view('includes/membros');
    $data['patrocinadores'] = $this->load->view('includes/patrocinadores');
    $data['seja_membro'] = $this->load->view('includes/seja_membro');

    $data['pagina'] = $this->func->getPage(4);    

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/quem_somos', $data));
  }
}
