<?php

namespace Mubbi;

class ControllerCommonHome extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Home');
    $this->document->addStyle('css/styles/evento');
    $this->document->addStyle('css/styles/home');

    $data['user_info'] = $this->session->data['user_info'];

    $data['titulo_dashboard'] = sprintf($this->language->get('titulo_dashboard'), $data['user_info']['nome']);

    $data['evento'] = $this->get_list();

    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('common/home', $data));
  }

  public function get_list() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('evento/evento');
      $evento = $this->model_evento_evento->getByStatus('1');

      if ($evento) {
        $evento['inicio'] = explode(' ', $evento['data_inicio']);
        $evento['final'] = explode(' ', $evento['data_final']);
      }


      return $evento;
    }
  }
}
