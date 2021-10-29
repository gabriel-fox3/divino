<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelCardapioProduto_cardapio extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $w[] = 'excluido = 0';
    if ($ativos == true) $w[] = 'ativo = 1';

    $q = sprintf('SELECT * FROM produto_cardapio');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idproduto_cardapio) {
    $q = sprintf('SELECT * FROM produto_cardapio WHERE idproduto_cardapio = %s', (int)$idproduto_cardapio);
    return $this->db->query($q)->row;
  }

  public function getByIdcategoria_cardapio($idcategoria_cardapio) {
    $w = array();
    $w[] = "categoria = '$idcategoria_cardapio'";
    // $w[] = 'ativo = "1"';
    $w[] = 'excluido = "0"';
    $sql = sprintf('SELECT * FROM produto_cardapio WHERE %s', implode(' AND ', $w));
    return $this->db->query($sql)->rows;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO produto_cardapio SET %s ', $change);
    $this->db->query($q);
    $data['idproduto_cardapio'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idproduto_cardapio'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE produto_cardapio SET %s WHERE idproduto_cardapio = %s', $change, (int)$data['idproduto_cardapio']);
    $this->db->query($q);
    return $data;
  }

  public function save($produto_cardapio) {
    if (isset($produto_cardapio['idproduto_cardapio']) && $produto_cardapio['idproduto_cardapio'] !== null) {
      return $this->edit($produto_cardapio);
    } else {
      return $this->add($produto_cardapio);
    }
  }
  
  public function delete($idproduto_cardapio) {
    $q = sprintf('DELETE FROM produto_cardapio WHERE idproduto_cardapio = %s', (int)$idproduto_cardapio);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
