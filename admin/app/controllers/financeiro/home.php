<?php

namespace Mubbi;
use Brick\Money\Money;

class ControllerFinanceiroHome extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('css/styles/financeiro');
    $this->document->addScript('node_modules/currency.js/dist/currency.min');

    $data['produtos'] = $this->get_list();
    // echo "<pre>";
    //   print_r($data['produtos']);
    // echo "</pre>";
    // exit;

    $data['action_add_entrada'] = $this->url->link('financeiro/home/add_entrada');

    $data['modal_entrada'] = $this->load->view('financeiro/entrada', $data);


    $data['sidebar'] = $this->load->controller('common/sidebar', $data);
    $data['navbar'] = $this->load->controller('common/navbar', $data);
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('financeiro/list', $data));
  }

  public function get_list() {
    $this->load->model('cardapio/categoria_cardapio');
    $this->load->model('cardapio/produto_cardapio');
    $this->load->model('estoque/produto');
    $categorias = $this->model_cardapio_categoria_cardapio->getAll(true);
    if (sizeof($categorias) > 0) {
      foreach($categorias as $key => $val) {
        $produtos = $this->model_cardapio_produto_cardapio->getByIdcategoria_cardapio($val['idcategoria_cardapio']);
        if (sizeof($produtos) > 0) {
          foreach($produtos as $i => $produto) {
            $status_produto_cardapio = true;

            $preco = Money::ofMinor($produto['preco'], 'BRL');
            $items_produto = json_decode($produto['produtos'], true);
            $obj_produto = array();
            if (sizeof($items_produto) > 0) {
              foreach($items_produto as $e => $info) {
                $produto_estoque = $this->model_estoque_produto->getById($e);
                $status = false;
                if ((float)$produto_estoque['qnt_atual'] >= (float)$info['qnt']) $status = true;

                $obj_produto[] = array(
                  'produto' => $produto_estoque,
                  'qnt' => $info['qnt'],
                  'status' => $status
                );

                if ($status == false) $status_produto_cardapio = false;
              }
            }

            $produtos[$i]['status_geral'] = $status_produto_cardapio;
            $produtos[$i]['info'] = $obj_produto;
            $produtos[$i]['preco'] = $preco->formatTo('pt_BR');
            $produtos[$i]['preco_safe'] = $produto['preco'];
          }
        }
        $categorias[$key]['produtos'] = $produtos;
        $categorias[$key]['link'] = $this->url->link('cardapio/home&idc=' . $val['idcategoria_cardapio']);
      }
    }
    return $categorias;
  }

  public function add_entrada() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {

      echo "<pre>";
        print_r($this->request->post);
      echo "</pre>";
      exit;

      $this->load->model('financeiro/financeiro');
      $errors = array();

      $total = Money::of(0, 'BRL');

      $financeiro = array(
        'idusuario' => $this->user->getIdusuario(),
        'joined' => date('Y-m-d H:i:s'),
        'tipo' => 'entrada',
        'valor' => '0',
        'descricao' => $this->request->post['descricao_geral'],
        'manual' => '1',
        'obj' => array()
      );

      $obj = array();

      $financeiro = $this->model_financeiro_financeiro->add($financeiro);
   
      // movimentacao estoque
      if (isset($this->request->post['chk_cardapio']) && $this->request->post['chk_cardapio'] == 'on') {
        $this->load->model('estoque/movimentacao');
        $this->load->model('estoque/produto');
        $this->load->model('cardapio/produto');
  
      
        $produtos = $this->request->post['produtos'];


  
        if (sizeof($errors) > 0) {
          $this->model_financeiro_financeiro->delete($financeiro['idfinanceiro']);
          $this->response->json(array('error' => true, 'errors' => $errors));
          exit;
        }
  
        $movimentacao['obj'] = json_encode($movimentacao['obj']);
        $movimentacao['produtos'] = json_encode($movimentacao['produtos']);

        
        $movimentacao = $this->model_estoque_movimentacao->add($movimentacao);
        
        $obj['cardapio'] = $movimentacao;
        
        $this->log->save('add_financeiro_movimentacao_estoque', array(
          'new' => serialize($movimentacao),
        ));

      }

      if (isset($this->request->post['chk_personalizadas']) && $this->request->post['chk_personalizadas'] == 'on') {
        foreach ($this->request->post['entrada'] as $entrada) {
          
        }
      }
    }
    
  }
}