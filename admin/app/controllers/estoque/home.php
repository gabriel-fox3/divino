<?php

namespace Mubbi;

class ControllerEstoqueHome extends BaseController {
  public function index() {

    $this->document->setTitle('Estoque');
    $this->document->addStyle('css/styles/estoque');

    $this->document->addStyle('js/plugins/iconpicker/css/fontawesome-iconpicker');
    $this->document->addScript('js/plugins/iconpicker/js/fontawesome-iconpicker.min');

    $data['user_info'] = $this->session->data['user_info'];
    
    $data['default_img'] = DEFAULT_IMG_USER;
    $data['categorias'] = $this->getListCategorias();

    if (isset($this->request->get['idc']) && $this->request->get['idc'] !== '') {
      $this->session->data['last_id_categoria'] = $this->request->get['idc'];
      $this->load->model('estoque/categoria_produto');
      $data['categoria'] = $this->model_estoque_categoria_produto->getById($this->request->get['idc']);
      $data['produtos'] = $this->getListProdutos($this->request->get['idc']);
      $data['back'] = $this->url->link('estoque/home');
    }
    

    $data['action_add_categoria'] = $this->url->link('estoque/home/add_categoria');
    $data['action_add_produto'] = $this->url->link('estoque/home/add_produto');

    $data['url_edit_categoria'] = $this->url->link('estoque/home/edit_categoria');
    $data['url_excluir_categoria'] = $this->url->link('estoque/home/delete_categoria');

    $data['url_edit_produto'] = $this->url->link('estoque/home/edit_produto');
    $data['url_excluir_produto'] = $this->url->link('estoque/home/delete_produto');

    
    $data['modal_add_categoria'] = $this->load->view('estoque/add_categoria', $data);
    $data['modal_add_produto'] = $this->load->view('estoque/add_produto', $data);
    
    $data['sidebar'] = $this->load->controller('common/sidebar', $data);
    $data['navbar'] = $this->load->controller('common/navbar', $data);
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('estoque/list', $data));
  }

  public function getListCategorias() {
    $this->load->model('estoque/categoria_produto');
    $this->load->model('estoque/produto');
    $categorias = $this->model_estoque_categoria_produto->getAll();
    if (sizeof($categorias) > 0) {
      foreach($categorias as $key => $val) {
        $categorias[$key]['produtos'] = $this->model_estoque_produto->getByIdcategoria_produto($val['idcategoria_produto']);
        $categorias[$key]['link'] = $this->url->link('estoque/home&idc=' . $val['idcategoria_produto']);
      }
    }
    return $categorias;
  }

  public function getListProdutos($idcategoria) {
    $this->load->model('estoque/produto');
    $produtos = $this->model_estoque_produto->getByIdcategoria_produto($idcategoria);
    if (sizeof($produtos) > 0) {
      foreach($produtos as $key => $val) {
        $val['imagem'] = $val['imagem'] !== NULL ? UPLOADS . 'produtos/' . $val['imagem'] : (ASSETS . 'img/no_photo.png');
        $produtos[$key] = $val;
        // $produtos[$key]['link'] = $this->url->link('estoque/home&idc=' . $val['idcategoria_produto']);
      }
    }
    return $produtos;
  }

  /* Categorias */
  public function add_categoria() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('estoque/categoria_produto');

      $categoria = array(
        'nome' => $this->request->post['nome'],
        'ativo' => isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1' ? '1' : '0'
      );

      if (isset($this->request->post['icone']) && $this->request->post['icone'] !== '') {
        $categoria['icone'] = $this->request->post['icone'];
      }

      $categoria = $this->model_estoque_categoria_produto->add($categoria);

      $this->log->save('add_categoria_produto', array(
        'new' => serialize($categoria)
      ));
      
      $this->session->data['success'] = array('key' => 'add_categoria');
      $this->response->redirect($this->url->link('estoque/home'));
    }
  }

  public function edit_categoria() {
    $this->load->model('estoque/categoria_produto');
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $data = array();
      $data['categoria'] = $this->model_estoque_categoria_produto->getById($this->request->get['id']);
      $data['action_edit_categoria'] = $this->url->link('estoque/home/edit_categoria');

      $this->response->json(array('result' => $this->load->view('estoque/edit_categoria', $data)));
    } else if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $categoria = $this->model_estoque_categoria_produto->getById($this->request->post['id']);
      $categoria_old = $categoria;

      if (isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1') {
        $categoria['ativo'] = 1;
      } else {
        $categoria['ativo'] = 0;
      }

      $categoria['nome'] = $this->request->post['nome'];

      if (isset($this->request->post['icone']) && $this->request->post['icone'] !== '') {
        $categoria['icone'] = $this->request->post['icone'];
      }

      $categoria = $this->model_estoque_categoria_produto->save($categoria);

      $this->log->save('edit_categoria_produto', array(
        'new' => serialize($categoria),
        'old' => serialize($categoria_old)
      ));
      
      $this->session->data['success'] = array('key' => 'edit_categoria');
      $this->response->redirect($this->url->link('estoque/home'));
    }
  }

  public function delete_categoria() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('estoque/categoria_produto');
      $categoria = $this->model_estoque_categoria_produto->getById($this->request->post['id']);
      $categoria['excluido'] = '1';
      $categoria['ativo'] = '0';
      $categoria = $this->model_estoque_categoria_produto->save($categoria);

      $this->session->data['success'] = array('key' => 'delete_categoria');
      $this->response->json(array('error' => false));
    }
  }

  /* Produtos */
  public function add_produto() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('estoque/produto');

      $produto = array(
        'nome' => $this->request->post['nome'],
        'categorias' => json_encode($this->request->post['categoria']),
        'qnt_ideal' => isset($this->request->post['qnt_ideal']) && $this->request->post['qnt_ideal'] !== '' ? $this->request->post['qnt_ideal'] : '0',
        'qnt_atual' => $this->request->post['qnt_atual'],
        'medida' => $this->request->post['medida'],
        'ativo' => isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1' ? '1' : '0'
      );

      $produto = $this->model_estoque_produto->add($produto);
      
      $path_upload = UPLOADS_DIR . 'produtos/';
      if(isset($this->request->files['imagem']) && isset($this->request->files['imagem']['name']) && $this->request->files['imagem']['name'] != null) {
        $ext_img = strtolower(pathinfo($this->request->files['imagem']['name'], PATHINFO_EXTENSION));
        if (in_array($ext_img , EXT_IMAGENS)) {
          if ($this->request->files['imagem']['size'] < MAX_SIZE_IMAGENS) {
            $img_name = $produto['idproduto'] . '_' . uniqid() . '.' . $ext_img;
            if (move_uploaded_file($this->request->files['imagem']['tmp_name'], $path_upload . $img_name)) {
              $produto['imagem'] = $img_name;
              $this->func->compressImage($path_upload . $img_name, $path_upload . 'compressed/' . $img_name, 75);
              
              $produto = $this->model_estoque_produto->save($produto);
            }
          }
        }
      }

      $this->log->save('add_produto', array(
        'new' => serialize($produto)
      ));
      
      $this->session->data['success'] = array('key' => 'add_produto');
      $this->response->redirect($this->url->link('estoque/home') . (isset($this->session->data['last_id_categoria']) ? '&idc=' . $this->session->data['last_id_categoria'] : ''));
    }
  }

  public function edit_produto() {
    $this->load->model('estoque/produto');
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $data = array();
      $data['categorias'] = $this->getListCategorias();
      $data['produto'] = $this->model_estoque_produto->getById($this->request->get['id']);
      $data['produto']['imagem'] = $data['produto']['imagem'] !== NULL ? UPLOADS . 'produtos/' . $data['produto']['imagem'] : ASSETS . 'img/no_photo.png';
      $data['action_edit_produto'] = $this->url->link('estoque/home/edit_produto');

      $this->response->json(array('result' => $this->load->view('estoque/edit_produto', $data)));
    } else if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $produto = $this->model_estoque_produto->getById($this->request->post['id']);
      $produto_old = $produto;

      $produto['nome'] = $this->request->post['nome'];
      $produto['categorias'] = json_encode($this->request->post['categoria']);
      $produto['qnt_ideal'] = isset($this->request->post['qnt_ideal']) && $this->request->post['qnt_ideal'] !== '' ? $this->request->post['qnt_ideal'] : '0';
      $produto['qnt_atual'] = $this->request->post['qnt_atual'];
      $produto['medida'] = $this->request->post['medida'];
      $produto['ativo'] = isset($this->request->post['ativo']) && $this->request->post['ativo'] == '1' ? '1' : '0';
      
      $path_upload = UPLOADS_DIR . 'produtos/';
      if(isset($this->request->files['imagem']) && isset($this->request->files['imagem']['name']) && $this->request->files['imagem']['name'] != null) {
        $ext_img = strtolower(pathinfo($this->request->files['imagem']['name'], PATHINFO_EXTENSION));
        if (in_array($ext_img , EXT_IMAGENS)) {
          if ($this->request->files['imagem']['size'] < MAX_SIZE_IMAGENS) {
            $img_name = $produto['idproduto'] . '_' . uniqid() . '.' . $ext_img;
            if (move_uploaded_file($this->request->files['imagem']['tmp_name'], $path_upload . $img_name)) {
              if ($produto['imagem'] !== NULL) {
                if (file_exists(UPLOADS_DIR . 'produtos/' . $produto['imagem'])) unlink(UPLOADS_DIR . 'produtos/' . $produto['imagem']);
                if (file_exists(UPLOADS_DIR . 'produtos/compressed/' . $produto['imagem'])) unlink(UPLOADS_DIR . 'produtos/compressed/' . $produto['imagem']);
              }
              $produto['imagem'] = $img_name;
              $this->func->compressImage($path_upload . $img_name, $path_upload . 'compressed/' . $img_name, 75);
              
            }
          }
        }
      }

      $produto = $this->model_estoque_produto->save($produto);

      $this->log->save('edit_produto', array(
        'new' => serialize($produto),
        'old' => serialize($produto_old)
      ));
      
      $this->session->data['success'] = array('key' => 'edit_produto');
      $this->response->redirect($this->url->link('estoque/home&idc=') . (isset($this->session->data['last_id_categoria']) ? '&idc=' . $this->session->data['last_id_categoria'] : ''));
    }
  }

  public function delete_produto() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('estoque/produto');
      $produto = $this->model_estoque_produto->getById($this->request->post['id']);
      $produto['excluido'] = '1';
      $produto['ativo'] = '0';
      $produto = $this->model_estoque_produto->save($produto);

      $this->session->data['success'] = array('key' => 'delete_produto');
      $this->response->json(array('error' => false));
    }
  }
}
