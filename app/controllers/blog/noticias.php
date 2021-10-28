<?php

namespace Mubbi;

use DateTime;

class ControllerBlogNoticias extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Blog IBL');

    $this->document->addStyle('pages/blog');

    $data['noticias'] = $this->getList();
    // echo "<pre>";
    //   print_r($data['noticias']);
    // echo "</pre>";
    // exit;

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);


    $this->response->setOutput($this->load->view('blog/list', $data));
  }

  public function getList($limit = null) {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('noticia/noticia');
      $noticias = $this->model_noticia_noticia->getByData(date('Y-m-d'), $limit);
      if (sizeof($noticias) > 0) {
        foreach($noticias as $key => $noticia) {
          if ($noticia['imagem'] !== '' && $noticia['imagem'] !== null) {
            $noticia['imagem'] = UPLOADS . 'noticias/' . $noticia['imagem'];
          } else {
            $noticia['imagem'] = ASSETS . 'img/no_photo.png';
          }
          if ($noticia['banner'] !== '' && $noticia['banner'] !== null) {
            $noticia['banner'] = UPLOADS . 'noticias/' . $noticia['banner'];
          } else {
            $noticia['banner'] = ASSETS . 'img/no_photo.png';
          }
          $noticia['data'] = DateTime::createFromFormat('Y-m-d', $noticia['data'])->format('d.m.Y');
          $noticia['autores'] = html_entity_decode($noticia['autores']);
          $noticias[$key] = $noticia;
        }
      }
      return $noticias;

    }
  }

  public function view($data = null) {

    $this->document->addStyle('pages/blog');

    if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
      $this->load->model('noticia/noticia');
      $noticia = $this->model_noticia_noticia->getById($this->request->get['id']);

      $this->document->setTitle($noticia['nome'] . ' - Blog IBL');

      if ($noticia['imagem'] !== '' && $noticia['imagem'] !== null) {
        $noticia['imagem'] = UPLOADS . 'noticias/' . $noticia['imagem'];
      } else {
        $noticia['imagem'] = ASSETS . 'img/no_photo.png';
      }
      if ($noticia['banner'] !== '' && $noticia['banner'] !== null) {
        $noticia['banner'] = UPLOADS . 'noticias/' . $noticia['banner'];
      }
      $noticia['obj'] = json_decode($noticia['obj'], true);
      $noticia['autores'] = html_entity_decode($noticia['autores']);
      $noticia['data'] = DateTime::createFromFormat('Y-m-d', $noticia['data'])->format('d.m.Y');
      $data['noticia'] = $noticia;
    }
      

    $data['noticias'] = $this->getList(3);


    $this->document->addStyle('pages/noticia_interna');

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $data['seja_membro'] = $this->load->view('includes/seja_membro');

    $this->response->setOutput($this->load->view('blog/view', $data));
  }
}
