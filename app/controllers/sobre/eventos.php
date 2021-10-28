<?php

namespace Mubbi;

use DateTime;

class ControllerSobreEventos extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Eventos Base27');

    $this->document->addStyle('pages/eventos');

    $data['eventos'] = $this->getList();

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('eventos/list', $data));
  }

  public function getList() {
    $this->load->model('api/eventos');
    $eventos = $this->model_api_eventos->getAll();

    $ret = array(
      'abertos' => array(),
      'exclusivos' => array()
    );

    if (sizeof($eventos) > 0) {
      foreach($eventos as $key => $evento) {

        $evento['descricao'] = substr(strip_tags($evento['description']), 0, 100);

        $evento['img'] = ASSETS . 'img/blog/b27inline.png';
        if ($evento['image_url'] !== null) {
          $evento['img'] = $evento['image_url'];
        }

        $evento['data_ev'] = DateTime::createFromFormat('Y-m-d H:i:s', $evento['initDate'])->format('d/m');
        $evento['dia_ev'] = $this->func->getDiaSemana(DateTime::createFromFormat('Y-m-d H:i:s', $evento['initDate'])->format('w'));

        if ($evento['purchaseDeadline'] !== null) {
          $evento['data_purchase'] = DateTime::createFromFormat('Y-m-d H:i:s', $evento['purchaseDeadline'])->format('d/m');
        }
       
        if ($evento['allInvite'] === 0) {
          $ret['abertos'][] = $evento;
        } else {
          $ret['exclusivos'][] = $evento;
        }
      }
    }
    // echo "<pre>";
    //   print_r($ret);
    // echo "</pre>";
    // exit;
    return $ret;
  }

  public function view($data = null) {
    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $this->load->model('api/eventos');

      $evento = $this->model_api_eventos->evento($this->request->get['id']);
      $evento['img'] = ASSETS . 'img/blog/b27inline.png';
      if ($evento['image_url'] !== null) {
        $evento['img'] = $evento['image_url'];
      }

      $evento['data_ev'] = DateTime::createFromFormat('Y-m-d H:i:s', $evento['initDate'])->format('d/m');
      $evento['dia_ev'] = $this->func->getDiaSemana(DateTime::createFromFormat('Y-m-d H:i:s', $evento['initDate'])->format('w'));

      if ($evento['purchaseDeadline'] !== null) {
        $evento['data_deadline'] = DateTime::createFromFormat('Y-m-d H:i:s', $evento['purchaseDeadline'])->format('d/m');
      }

      $data['evento'] = $evento;
      // echo "<pre>";
      //   print_r($data['evento']);
      // echo "</pre>";
      // exit;
    }


    $this->document->setTitle('Eventos');

    $this->document->addStyle('pages/eventointerna');


    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $data['conheca'] = $this->load->view('includes/conheca');


    $this->response->setOutput($this->load->view('eventos/interna', $data));
  }
}
