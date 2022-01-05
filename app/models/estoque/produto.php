<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelEstoqueProduto extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $w[] = 'excluido = 0';
    if ($ativos == true) $w[] = 'ativo = 1';

    $q = sprintf('SELECT * FROM produto');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idproduto) {
    $q = sprintf('SELECT * FROM produto WHERE idproduto = %s', (int)$idproduto);
    return $this->db->query($q)->row;
  }

  public function getByIdcategoria_produto($idcategoria_produto) {
    $w = array();
    $w[] = "categorias LIKE '%\"$idcategoria_produto\"%'";
    // $w[] = 'ativo = "1"';
    $w[] = 'excluido = "0"';
    $sql = sprintf('SELECT * FROM produto WHERE %s', implode(' AND ', $w));
    return $this->db->query($sql)->rows;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO produto SET %s ', $change);
    $this->db->query($q);
    $data['idproduto'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idproduto'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE produto SET %s WHERE idproduto = %s', $change, (int)$data['idproduto']);
    $this->db->query($q);
    return $data;
  }

  public function save($produto) {
    if (isset($produto['idproduto']) && $produto['idproduto'] !== null) {
      return $this->edit($produto);
    } else {
      return $this->add($produto);
    }
  }
  
  public function delete($idproduto) {
    $q = sprintf('DELETE FROM produto WHERE idproduto = %s', (int)$idproduto);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
