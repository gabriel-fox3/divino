<?php

namespace Mubbi;

use Brick\Money\Money;
// https://github.com/brick/money

class ControllerCardapioHome extends BaseController {
  public function index($data = null) {

    $this->document->setTitle('Cardápio');
    $this->document->addStyle('css/styles/cardapio');
    
    $data['categorias_cardapio'] = $this->getListCategorias();

    // echo "<pre>";
    //   print_r($data['categorias_cardapio']);
    // echo "</pre>";
    // exit;

    $data['action_add_categoria'] = $this->url->link('cardapio/home/add_categoria');
    $data['modal_add_categoria'] = $this->load->view('cardapio/add_categoria', $data);

    $data['categorias_produtos'] = $this->get_categorias_produtos();
    $data['action_add_produto'] = $this->url->link('cardapio/home/add_produto');
    $data['modal_add_produto'] = $this->load->view('cardapio/add_produto', $data);


        
    $data['sidebar'] = $this->load->controller('common/sidebar', $data);
    $data['navbar'] = $this->load->controller('common/navbar', $data);
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('cardapio/list', $data));
  }

  public function getListCategorias() {
    $this->load->model('cardapio/categoria_cardapio');
    $this->load->model('cardapio/produto_cardapio');
    $this->load->model('estoque/produto');
    $categorias = $this->model_cardapio_categoria_cardapio->getAll();
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
          }
        }
        $categorias[$key]['produtos'] = $produtos;
        $categorias[$key]['link'] = $this->url->link('cardapio/home&idc=' . $val['idcategoria_cardapio']);
      }
    }
    return $categorias;
  }

  /* Categorias */
  private function get_categorias_produtos() {
    $this->load->model('estoque/categoria_produto');
    $this->load->model('estoque/produto');
    // $produtos = $this->model_estoque_produto->getBy
    $categorias = $this->model_estoque_categoria_produto->getAll(true);
    if (sizeof($categorias) > 0) {
      foreach($categorias as $key => $categoria) {
        $categoria['produtos'] = $this->model_estoque_produto->getByIdcategoria_produto($categoria['idcategoria_produto']);

        if (sizeof($categoria['produtos']) > 0) {
          foreach($categoria['produtos'] as $k => $produto) {
            $produto['imagem'] = $produto['imagem'] !== null ? UPLOADS . 'produtos/' . $produto['imagem'] : DEFAULT_NO_PHOTO;
            $categoria['produtos'][$k] = $produto;
          }
        }

        $categorias[$key] = $categoria;
      }
    }

    return $categorias;
  }

  public function add_categoria() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('cardapio/categoria_cardapio');

      $count = $this->model_cardapio_categoria_cardapio->getAll();

      $categoria = array(
        'nome' => $this->request->post['nome'],
        'ativo' => isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1' ? '1' : '0',
        'ordem' => sizeof($count)
      );

      $categoria = $this->model_cardapio_categoria_cardapio->add($categoria);

      $this->log->save('add_categoria_cardapio', array(
        'new' => serialize($categoria)
      ));
      
      $this->session->data['success'] = array('key' => 'add_categoria');
      $this->response->redirect($this->url->link('cardapio/home'));
    }
  }


  /* Produtos */
  public function add_produto() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('cardapio/produto_cardapio');

      $count = $this->model_cardapio_produto_cardapio->getByIdcategoria_cardapio($this->request->post['categoria']);

      $preco = str_replace('.', '', $this->request->post['preco']);
      $preco = str_replace(',', '.', $preco);
      $preco = Money::of($preco, 'BRL');
      $preco = $preco->getMinorAmount()->toInt();

      $produto_cardapio = array(
        'nome' => $this->request->post['nome'],
        'categoria' => $this->request->post['categoria'],
        'preco' => $preco,
        'descricao' => $this->request->post['descricao'],
        'ativo' => (isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1' ? '1' : '0'),
        'ordem' => (string)sizeof($count)
      );

      $produtos = $this->request->post['produtos'];
      $obj = $this->request->post['obj'];

      $errors = array();

      if (sizeof($produtos) > 0) {
        foreach($produtos as $idproduto) {
          if (!isset($obj[$idproduto]) || $obj[$idproduto] == '' || (float)$obj[$idproduto] == 0) {
            $produto = $this->model_estoque_produto->getById($idproduto);
            $errors[] = 'Informe a quantidade de <b>' . $produto['nome'] . '</b>.';
            continue;
          }
        }
      }

      if (sizeof($errors) > 0) {
        $this->response->json(array('error' => true, 'errors' => $errors));
        exit;
      }

      $produto_cardapio['produtos'] = json_encode($this->request->post['obj']);

      $produto_cardapio = $this->model_cardapio_produto_cardapio->add($produto_cardapio);

      $this->log->save('add_produto_cardapio', array(
        'new' => serialize($produto)
      ));
      
      $this->session->data['success'] = array('key' => 'add_produto');
      $this->response->redirect($this->url->link('cardapio/home'));
    }
  }
}