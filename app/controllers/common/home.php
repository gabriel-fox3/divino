<?php

namespace Mubbi;

use DateTime;

class ControllerCommonHome extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Multibio Soluções Laboratoriais');

    $this->document->addStyle('pages/home');
    $this->document->addStyle('pages/eventos');

    $data['eventos'] = $this->getEventos();

    $data['home_departamentos'] = $this->load->controller('sobre/produtos/getProdutos');
    $data['home_representadas'] = $this->func->getPage(1);  
    $data['home_empresa'] = $this->func->getPage(4);

    // echo "<pre>";
    //   print_r($data['home_departamentos']);
    // echo "</pre>";
    // exit;

    $data['conheca'] = $this->load->view('includes/conheca');
    $data['mantenedores'] = $this->load->view('includes/mantenedores');
    $data['membros'] = $this->load->view('includes/membros');
    $data['patrocinadores'] = $this->load->view('includes/patrocinadores');
    $data['seja_membro'] = $this->load->view('includes/seja_membro');

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('common/home', $data));
  }

  public function getEventos() {
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
    
    return $ret;
  }

  public function getList($limit = null) {
    $this->load->model('api/noticias');
    $noticias = $this->model_api_noticias->getAll($limit);

    if (isset($noticias) && is_array($noticias) && sizeof($noticias) > 0) {
      foreach($noticias as $key => $noticia) {
        $noticia['data'] = DateTime::createFromFormat('Y-m-d H:i:s', $noticia['createdAt'])->format('d/m/Y \à\s H:i');
        $noticia['img'] = ASSETS . 'img/blog/b27inline.png';;
        if ($noticia['image_url'] !== null) {
          $noticia['img'] = $noticia['image_url'];
        }
        $noticias[$key] = $noticia;
      }
    }

    return $noticias;
  }
}
