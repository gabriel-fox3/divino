<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelFinanceiroFinanceiro extends BaseModel{
  public function getAll($ativos = false) {
    $w = array();
    $q = sprintf('SELECT * FROM financeiro');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }
    return $this->db->query($q)->rows;
  }

  public function getById($idfinanceiro) {
    $q = sprintf('SELECT * FROM financeiro WHERE idfinanceiro = %s', (int)$idfinanceiro);
    return $this->db->query($q)->row;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO financeiro SET %s ', $change);
    $this->db->query($q);
    $data['idfinanceiro'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idfinanceiro'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE financeiro SET %s WHERE idfinanceiro = %s', $change, (int)$data['idfinanceiro']);
    $this->db->query($q);
    return $data;
  }

  public function save($financeiro) {
    if (isset($financeiro['idfinanceiro']) && $financeiro['idfinanceiro'] !== null) {
      return $this->edit($financeiro);
    } else {
      return $this->add($financeiro);
    }
  }
  
  public function delete($idfinanceiro) {
    $q = sprintf('DELETE FROM financeiro WHERE idfinanceiro = %s', (int)$idfinanceiro);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
