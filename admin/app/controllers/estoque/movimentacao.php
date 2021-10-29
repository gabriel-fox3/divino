<?php

namespace Mubbi;

class ControllerEstoqueMovimentacao extends BaseController {
  public function index($data) {

    $data['action_movimentacao'] = $this->url->link('estoque/movimentacao/add');
    $data['categorias_produtos'] = $this->get_list();
    return $this->load->view('estoque/movimentacao', $data);
  }

  private function get_list() {
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

  public function add() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('estoque/movimentacao');
      $this->load->model('estoque/produto');

      $errors = array();

      $movimentacao = array(
        'idusuario' => $this->user->getIdusuario(),
        'joined' => date('Y-m-d H:i:s')
      );
      
      $movimentacao['lancar'] = $this->request->post['lancar'];
      $movimentacao['descricao'] = $this->request->post['descricao'];
      $movimentacao['produtos'] = $this->request->post['produtos'];
      $movimentacao['obj'] = $this->request->post['obj'];

      if (sizeof($movimentacao['produtos']) > 0) {
        foreach($movimentacao['produtos'] as $idproduto) {
          $produto = $this->model_estoque_produto->getById($idproduto);
          $produto_old = $produto;

          if (!isset($movimentacao['obj'][$idproduto]) || $movimentacao['obj'][$idproduto] == '' || (float)$movimentacao['obj'][$idproduto] == 0) {
            $errors[] = 'Informe a quantidade de ' . $movimentacao['lancar'] . ' de <b>' . $produto['nome'] . '</b>.';
            continue;
          }   

          if ($movimentacao['lancar'] == 'entrada') {
            $produto['qnt_atual'] = (float)$produto['qnt_atual'] + (float)$movimentacao['obj'][$idproduto];
          } else if ($movimentacao['lancar'] == 'saida') {
            $produto['qnt_atual'] = (float)$produto['qnt_atual'] - (float)$movimentacao['obj'][$idproduto];
          }
          $produto = $this->model_estoque_produto->save($produto);
          $this->log->save('movimentacao_edit_qnt_produto', array(
            'new' => serialize($produto),
            'old' => serialize($produto_old)
          ));
        }
      }

      if (sizeof($errors) > 0) {
        $this->response->json(array('error' => true, 'errors' => $errors));
        exit;
      }

      $movimentacao['obj'] = json_encode($movimentacao['obj']);
      $movimentacao['produtos'] = json_encode($movimentacao['produtos']);

      $movimentacao = $this->model_estoque_movimentacao->add($movimentacao);
      
      
      $this->log->save('add_movimentacao', array(
        'new' => serialize($movimentacao),
      ));
      
      $this->session->data['success'] = array('key' => 'add_movimentacao');
      $this->response->json(array('error' => false));
    }
  }
}

?>