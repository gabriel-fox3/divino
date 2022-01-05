<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonSidebar extends BaseController {
  public function index() {

    $data['route'] = $this->request->get['route'];

    $this->load->language('common/sidebar');

    if (!$this->user->isLogged()) {
      $this->response->redirect($this->url->link('account/logout'), 307);
    }

    $data['sidebar_menu'] = $this->getMenu();

    
    
    $data['user_info'] = $this->session->data['user_info'];
    $data['user_info']['logo'] = DEFAULT_IMG_USER;
    
    $data['cronograma'] = $this->url->link('cronograma/home');
    $data['andamentocrono'] = $this->url->link('cronograma/andamento');
    $data['reuniao'] = $this->url->link('reuniao/home');

    return $this->load->view('common/sidebar', $data);
  }

  public function getMenu() {
    
    $items = array();

    // Dashboard
    $items[] = array(
      'text' => $this->language->get('text_dashboard'),
      'icon' => '<i class="align-middle" data-feather="home"></i>',
      'url' => $this->url->link('common/home'),
      'active' => array(
        'common/home'
      )
    );

    // Estoque
    // $estoque = array();

    // $estoque[] = array(
    //   'text' => $this->language->get('text_label_estoque'),
    //   'disabled' => true,
    //   'url' => '',
    //   'active' => '',
    //   'icon' => ''
    // );

    // $estoque[] = array(
    //   'text' => $this->language->get('text_my_estoque'),
    //   'icon' => '<i class="align-middle" data-feather="play"></i>',
    //   'url' => $this->url->link('reuniao/home'),
    //   'active' => array(
    //     'reuniao/home'
    //   )
    // );

    // $estoque[] = array(
    //   'text' => $this->language->get('text_all_estoque'),
    //   'icon' => '<i class="align-middle" data-feather="play"></i>',
    //   'url' => $this->url->link('reuniao/home/geral'),
    //   'active' => array(
    //     'reuniao/home/geral'
    //   )
    // );
    
    $items[] = array(
      'text' => $this->language->get('text_estoque'),
      'icon' => '<i class="fas fa-box-open"></i>',
      'active' => array(
        'estoque/home'
      ),
      'url' => $this->url->link('estoque/home')
    );

    $items[] = array(
      'text' => $this->language->get('text_cardapio'),
      'icon' => '<i class="fas fa-receipt"></i>',
      'active' => array(
        'cardapio/home'
      ),
      'url' => $this->url->link('cardapio/home')
    );

    $items[] = array(
      'text' => $this->language->get('text_financeiro'),
      'icon' => '<i class="fas fa-receipt"></i>',
      'active' => array(
        'financeiro/home'
      ),
      'url' => $this->url->link('financeiro/home')
    );

    $items[] = array(
      'text' => $this->language->get('text_eventos'),
      'icon' => '<i class="fas fa-star"></i>',
      'active' => array(
        'evento/home'
      ),
      'url' => $this->url->link('evento/home')
    );


    // // Cronogramas
    // $cronogramas = array(
    //   'text' => $this->language->get('text_cronogramas'),
    //   'icon' => '<i class="align-middle" data-feather="folder"></i>',
    //   'active' => array(
    //     'cronograma/home', 'cronograma/andamento', 'cronograma/gerenciar', 'cronograma/editor'
    //   ),
    //   'items' => array()
    // );

    //   $cronogramas['items'][] = array(
    //     'text' => $this->language->get('text_gerenciar'),
    //     'icon' => '<i class="align-middle" data-feather="play"></i>',
    //     'url' => $this->url->link('cronograma/home'),
    //     'active' => array(
    //       'cronograma/home', 'cronograma/editor'
    //     )
    //   );

    //   $cronogramas['items'][] = array(
    //     'text' => $this->language->get('text_andamento'),
    //     'icon' => '<i class="align-middle" data-feather="play"></i>',
    //     'url' => $this->url->link('cronograma/andamento'),
    //     'active' => array(
    //       'cronograma/andamento'
    //     )
    //   );

    //   $cronogramas['items'][] = array(
    //     'text' => $this->language->get('text_editar'),
    //     'icon' => '<i class="align-middle" data-feather="play"></i>',
    //     'url' => $this->url->link('cronograma/gerenciar'),
    //     'active' => array(
    //       'cronograma/gerenciar'
    //     )
    //   );

    //   $items[] = $cronogramas;

    

    return $items;
  }
}
