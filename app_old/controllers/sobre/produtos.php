<?php

namespace Mubbi;

class ControllerSobreProdutos extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/produtos');

    $this->document->setTitle('Produtos - Multibio Soluções Laboratoriais');

    $data['planos'] = $this->load->view('includes/planos', $data);

    $data['departamentos'] = $this->getProdutos();
    $data['selected_d'] = isset($this->request->get['d']) && $this->request->get['d'] !== '' ? $this->request->get['d'] : null;
    // echo "<pre>";
    //   print_r($data['departamentos']);
    // echo "</pre>";
    // exit;

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/produtos', $data));
  }

  public function getProdutos() {
    $this->load->model('produto/departamento');
    $this->load->model('produto/produto');
    $this->load->model('produto/bloco');
    $this->load->model('arquivo/arquivo');


    $departamentos = $this->model_produto_departamento->getAll(true);
    if (sizeof($departamentos) > 0) {
      foreach($departamentos as $key => $departamento) {
        if ($departamento['imagem'] == null) {
          $produto_imagem = $this->model_produto_produto->getByImagem($departamento['iddepartamento']);
          if ($produto_imagem) {
            $departamentos[$key]['imagem'] = UPLOADS . $produto_imagem['arquivo'];
          } else {
            $departamentos[$key]['imagem'] = DEFAULT_NO_PHOTO;
          }
        } else {
          $imagens = json_decode($departamento['imagem'], true);
          $departamentos[$key]['imagem'] = UPLOADS . $imagens['arquivo'];
        }
        $departamentos[$key]['produtos'] = $this->model_produto_produto->getByIddepartamento($departamento['iddepartamento']);

        if (sizeof($departamentos[$key]['produtos']) > 0) {
          foreach($departamentos[$key]['produtos'] as $i => $produto) {
            $imagens = $this->model_arquivo_arquivo->getByIdproduto($produto['idproduto']);
            $produto = $this->func->getProduto($produto['idproduto']);
            $produto['imagens'] = $imagens;
            $departamentos[$key]['produtos'][$i] = $produto;
          }
        }
      }
    }

    return $departamentos;
    // echo "<pre>";
    //   print_r($departamentos);
    // echo "</pre>";
    // exit;

    
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

  public function view() {
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
}
