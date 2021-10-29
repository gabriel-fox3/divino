<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelEstoqueMovimentacao extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $q = sprintf('SELECT * FROM movimentacao');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idmovimentacao) {
    $q = sprintf('SELECT * FROM movimentacao WHERE idmovimentacao = %s', (int)$idmovimentacao);
    return $this->db->query($q)->row;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO movimentacao SET %s ', $change);
    $this->db->query($q);
    $data['idmovimentacao'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idmovimentacao'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE movimentacao SET %s WHERE idmovimentacao = %s', $change, (int)$data['idmovimentacao']);
    $this->db->query($q);
    return $data;
  }

  public function save($movimentacao) {
    if (isset($movimentacao['idmovimentacao']) && $movimentacao['idmovimentacao'] !== null) {
      return $this->edit($movimentacao);
    } else {
      return $this->add($movimentacao);
    }
  }
  
  public function delete($idmovimentacao) {
    $q = sprintf('DELETE FROM movimentacao WHERE idmovimentacao = %s', (int)$idmovimentacao);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
