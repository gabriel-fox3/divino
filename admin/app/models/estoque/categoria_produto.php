<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelEstoqueCategoria_produto extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $w[] = 'excluido = 0';
    if ($ativos == true) $w[] = 'ativo = 1';

    $q = sprintf('SELECT * FROM categoria_produto');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idcategoria_produto) {
    $q = sprintf('SELECT * FROM categoria_produto WHERE idcategoria_produto = %s', (int)$idcategoria_produto);
    return $this->db->query($q)->row;
  }

  public function getByChave($chave) {
    $w = array();
    $w[] = "chave = '$chave'";
    $sql = sprintf('SELECT * FROM categoria_produto WHERE %s', implode(' AND ', $w));
    return $this->db->query($sql)->row;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO categoria_produto SET %s ', $change);
    $this->db->query($q);
    $data['idcategoria_produto'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idcategoria_produto'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE categoria_produto SET %s WHERE idcategoria_produto = %s', $change, (int)$data['idcategoria_produto']);
    $this->db->query($q);
    return $data;
  }

  public function save($categoria_produto) {
    if (isset($categoria_produto['idcategoria_produto']) && $categoria_produto['idcategoria_produto'] !== null) {
      return $this->edit($categoria_produto);
    } else {
      return $this->add($categoria_produto);
    }
  }
  
  public function delete($idcategoria_produto) {
    $q = sprintf('DELETE FROM categoria_produto WHERE idcategoria_produto = %s', (int)$idcategoria_produto);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
