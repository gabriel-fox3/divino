<?php

namespace Mubbi;

/**
* Default Controller
*/
class ControllerCommonHeader extends BaseController {
  public function index($data) {   
    // $data['menus'] = $this->load->controller('common/menus');

    $data['route'] = $this->request->get['route'];

    $this->load->model('config/config');
    $data['header_phone'] = $this->model_config_config->getByChave('gerais');
    $data['header_phone'] = json_decode($data['header_phone']['val'], true);
    $data['header_phone'] = isset($data['header_phone']['footer']['tel']) && $data['header_phone']['footer']['tel'] != '' ? $data['header_phone']['footer']['tel'] : null;

    $data['header_representadas'] = $this->func->getPage(1);  
    $data['header_departamentos'] = $this->load->controller('sobre/produtos/getProdutos');
    
    return $this->load->view('common/header', $data);
  }
}
