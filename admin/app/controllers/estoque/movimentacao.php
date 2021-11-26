<?php

namespace Mubbi;

class ControllerEstoqueMovimentacao extends BaseController {
  public function index($data) {

    $data['action_movimentacao'] = $this->url->link('estoque/movimentacao/add');
    $data['url_upload_file'] = $this->url->link('estoque/movimentacao/upload_file');
    $data['url_delete_file'] = $this->url->link('estoque/movimentacao/delete_file');

    $data['categorias_produtos'] = $this->get_list();
    return $this->load->view('estoque/movimentacao', $data);
  }

  public function get_list() {
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

      $nota_fiscal = array();

      if (isset($this->request->post['imagem']) && is_array($this->request->post['imagem']) && sizeof($this->request->post['imagem']) > 0) {
        foreach ($this->request->post['imagem'] as $img) {
          $img_name = uniqid();
          if (strpos($img, ';base64,') !== false) {
            $web_capture_part = explode(";base64,", $img);
            $image_type_aux = explode("image/", $web_capture_part[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($web_capture_part[1]);
            $img_name = $img_name . '.png';
            $file = UPLOADS_DIR . 'nota_fiscal/' . $img_name;
            file_put_contents($file, $image_base64);  
          } else if (strpos($img, '.pdf') !== false) {
            $img_name = $img_name . '.pdf';
            $temp_file = UPLOADS_DIR . 'temp/' . $img;
            if (file_exists($temp_file)) {
              rename($temp_file, UPLOADS_DIR . 'nota_fiscal/' . $img_name);
            }
          }
          $nota_fiscal[] = $img_name;
        }
      }

      if (sizeof($nota_fiscal) > 0) {
        $movimentacao['nota_fiscal'] = json_encode($nota_fiscal);
      }

      $movimentacao = $this->model_estoque_movimentacao->add($movimentacao);
      
      
      $this->log->save('add_movimentacao', array(
        'new' => serialize($movimentacao),
      ));
      
      $this->session->data['success'] = array('key' => 'add_movimentacao');
      $this->response->json(array('error' => false));
    }
  }

  public function upload_file() {
    $path_upload = UPLOADS_DIR . 'temp/';
    if(isset($this->request->files['file']) && isset($this->request->files['file']['name']) && $this->request->files['file']['name'] != null) {
      $ext_file = strtolower(pathinfo($this->request->files['file']['name'], PATHINFO_EXTENSION));
      $img_name = 'temp_nota_fiscal_' . uniqid() . '.' . $ext_file;
      if (move_uploaded_file($this->request->files['file']['tmp_name'], $path_upload . $img_name)) {
        $this->response->json(array('error' => false, 'file' => $img_name));
        // $this->func->compressImage($path_upload . $img_name, $path_upload . 'compressed/' . $img_name, 75);
      }
    }
  }

  public function delete_file() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['file']) && $this->request->post['file'] !== '') {
        $file = $this->request->post['file'];
        if (file_exists(UPLOADS_DIR . 'temp/' . $file)) {
          unlink(UPLOADS_DIR . 'temp/' . $file);
          $this->response->json(array('error' => false));
        } else {
          $this->response->json(array('error' => true, 'msg' => 'Arquivo não encontrado'));
        }
      }
    }
  }
}

?>