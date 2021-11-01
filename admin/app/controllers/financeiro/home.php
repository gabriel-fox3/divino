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
    echo "<pre>";
      print_r($this->request->post);
    echo "</pre>";
    
  }
}