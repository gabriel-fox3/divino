<?php

namespace Mubbi;

class ControllerSobreProdutos_Interna extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/produtos');

    
    $data['produto'] = $this->getProduto();
    $this->document->setTitle($data['produto']['produto']['nome'] . ' - Multibio Soluções Laboratoriais');

    // echo "<pre>";
    //   print_r($data['produto']);
    // echo "</pre>";
    // exit;

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/produto_interna', $data));
  }

  public function getProduto() {
    $this->load->model('produto/departamento');
    $this->load->model('produto/produto');
    $this->load->model('produto/bloco');
    $this->load->model('arquivo/arquivo');


    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      if (isset($this->request->get['id']) && $this->request->get['id'] !== '') {
        $idproduto = $this->request->get['id'];
        $imagens = $this->model_arquivo_arquivo->getByIdproduto($idproduto);
        $produto = $this->func->getProduto($idproduto);
        $produto['imagens'] = $imagens;
        return $produto;
      }
    }

    
  }
}
