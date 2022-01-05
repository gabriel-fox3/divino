<?php
namespace Mubbi;

use DateTime;
use Mubbi\BaseModel;

class ModelEventoEvento extends BaseModel{
  public function getAll($filtro_mes = null) {
    $w = array();
    if ($filtro_mes !== null) {
      if (is_array($filtro_mes)) {
        $tmp = array();
        foreach ($filtro_mes as $mes) {
          $mes = (int)$mes > 9 ? $mes : '0' . $mes;
          $tmp[] = sprintf('joined LIKE "%s"', '%' . $mes . '%');
        }
        $w[] = '(' . implode(' OR ', $tmp) . ')';
      }
    }

    $q = sprintf('SELECT * FROM evento');

    if (sizeof($w) > 0) {
      $q .= ' WHERE ' . implode(' AND ', $w);
    }

    return $this->db->query($q)->rows;
  }

  public function getById($idevento) {
    $q = sprintf('SELECT * FROM evento WHERE idevento = %s', (int)$idevento);
    return $this->db->query($q)->row;
  }

  public function getByStatus($status) {
    $q = sprintf('SELECT * FROM evento WHERE `status` = %s', (int)$status);
    return $this->db->query($q)->row;
  }

  public function add($data) {
    $change = $this->db->formatChange($data);

    $q = sprintf('INSERT INTO evento SET %s ', $change);
    $this->db->query($q);
    $data['idevento'] = $this->db->getLastId();
    return $data;
  }

  public function edit($data) {
    if (!isset($data['idevento'])) return false;
    $change = $this->db->formatChange($data);

    $q = sprintf('UPDATE evento SET %s WHERE idevento = %s', $change, (int)$data['idevento']);
    $this->db->query($q);
    return $data;
  }

  public function save($evento) {
    if (isset($evento['idevento']) && $evento['idevento'] !== null) {
      return $this->edit($evento);
    } else {
      return $this->add($evento);
    }
  }
  
  public function delete($idevento) {
    $q = sprintf('DELETE FROM evento WHERE idevento = %s', (int)$idevento);
    $this->db->query($q);
    return $this->db->countAffected() > 0 ? true : false;
  }
}
