<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelCardapioCategoria_cardapio extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $w[] = 'excluido = 0';
    if ($ativos == true) $w[] = 'ativo = 1';

    $q = sprintf('SELECT * FROM categoria_cardapio');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idcategoria_cardapio) {
    $q = sprintf('SELECT * FROM categoria_cardapio WHERE idcategoria_cardapio = %s', (int)$idcategoria_cardapio);
    return $this->db->query($q)->row;
  }

  public function getByChave($chave) {
    $w = array();
    $w[] = "chave = '$chave'";
    $sql = sprintf('SELECT * FROM categoria_cardapio WHERE %s', implode(' AND ', $w));
    return $this->db->query($sql)->row;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO categoria_cardapio SET %s ', $change);
    $this->db->query($q);
    $data['idcategoria_cardapio'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idcategoria_cardapio'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE categoria_cardapio SET %s WHERE idcategoria_cardapio = %s', $change, (int)$data['idcategoria_cardapio']);
    $this->db->query($q);
    return $data;
  }

  public function save($categoria_cardapio) {
    if (isset($categoria_cardapio['idcategoria_cardapio']) && $categoria_cardapio['idcategoria_cardapio'] !== null) {
      return $this->edit($categoria_cardapio);
    } else {
      return $this->add($categoria_cardapio);
    }
  }
  
  public function delete($idcategoria_cardapio) {
    $q = sprintf('DELETE FROM categoria_cardapio WHERE idcategoria_cardapio = %s', (int)$idcategoria_cardapio);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
