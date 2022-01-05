<?php

namespace Mubbi;

use DateTime;
use Brick\Money\Money;

class ControllerEventoHome extends BaseController {
  public function index() {
    $this->document->setTitle('Eventos');

    $data['url_novo_evento'] = $this->url->link('evento/home/novo');
    $data['url_finalizar_evento'] = $this->url->link('evento/home/finalizar_evento');

    $this->document->addStyle('css/styles/evento');

    $data['evento'] = $this->get_list();
    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('evento/home', $data));
  }

  public function get_list() {
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $this->load->model('evento/evento');
      $evento = $this->model_evento_evento->getByStatus('1');

      if ($evento) {
        $evento['inicio'] = explode(' ', $evento['data_inicio']);
        $evento['final'] = explode(' ', $evento['data_final']);
      }


      return $evento;
    }
  }

  public function novo() {
    date_default_timezone_set('America/Sao_Paulo');
    
    $this->load->model('evento/evento');
    if ($this->request->server['REQUEST_METHOD'] == 'GET') {
      $data = array();

      $data['iniciar'] = false;
      if (isset($this->request->get['iniciar']) && $this->request->get['iniciar'] == true) {
        $data['iniciar'] = true;
      }

      $data['datas'] = array(
        'inicio' => date('Y-m-d'),
        'hora_inicio' => ((int)date('H') + 1) . ':00'
      );

      $data['datas']['final'] = DateTime::createFromFormat('Y-m-d', $data['datas']['inicio']);
      $data['datas']['final'] = $data['datas']['final']->add(date_interval_create_from_date_string('1 day'));
      $data['datas']['final'] = $data['datas']['final']->format('Y-m-d');

      $data['action_add_evento'] = $this->url->link('evento/home/novo');
  
      $this->response->json(array('result' => $this->load->view('evento/novo', $data)));
    } else if ($this->request->server['REQUEST_METHOD'] == 'POST') {

      $evento = array();
      $evento['data_inicio'] = $this->request->post['data_inicio'] . ' ' . $this->request->post['hora_inicio'] . ':00';
      $evento['data_final'] = $this->request->post['data_final'] . ' ' . $this->request->post['hora_final'] . ':00';

      $dti = DateTime::createFromFormat('Y-m-d H:i:s', $evento['data_inicio']);
      $evento['nome'] = 'Evento no dia ' . $dti->format('d/m/Y');
      if (isset($this->request->post['nome']) && $this->request->post['nome'] !== '') {
        $evento['nome'] = $this->request->post['nome'];
      }

      $evento['descricao'] = $this->request->post['descricao'];
      if (isset($this->request->post['cobrar_entrada']) && $this->request->post['cobrar_entrada'] == '1') {
        $evento['valor_entrada'] = $this->request->post['valor_entrada'];
        $evento['valor_entrada'] = str_replace('.', '', $evento['valor_entrada']);
        $evento['valor_entrada'] = str_replace(',', '.', $evento['valor_entrada']);
        $evento['valor_entrada'] = Money::of($evento['valor_entrada'], 'BRL');
        $evento['valor_entrada'] = $evento['valor_entrada']->getMinorAmount()->toInt();
      }
      
      $evento['status'] = '0';
      if (isset($this->request->post['iniciar']) && $this->request->post['iniciar'] == '1') {
        $evento['status'] = '1';
      }

      $evento = $this->model_evento_evento->add($evento);


      $this->log->save('add_evento', array(
        'new' => serialize($evento)
      ));
      
      $this->session->data['success'] = array('key' => 'add_evento');
      $this->response->redirect($this->url->link('evento/home'));
    }
  }

  public function finalizar_evento() {
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['idevento']) && $this->request->post['idevento'] !== '') {
        $idevento = $this->request->post['idevento'];
        $this->load->model('evento/evento');

        $evento = $this->model_evento_evento->getById($idevento);
        $evento['status'] = '2';

        $evento = $this->model_evento_evento->save($evento);

        $this->log->save('finalizar_evento', array(
          'new' => serialize($evento)
        ));
        
        $this->response->json(array('error' => false));
      }
    }
  }
}

?>