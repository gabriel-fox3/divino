<?php

namespace Mubbi;

class ControllerSobreOrcamento extends BaseController {
  public function index($data = null) {

    $this->document->addStyle('pages/orcamento');

    $this->document->setTitle('Orçamento - Multibio Soluções Laboratoriais');
    $data['representadas'] = $this->func->getPage(1);  

    $data['seja_membro'] = $this->load->view('includes/seja_membro');

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['form_footer'])) {
        if(isset($this->request->post['g-recaptcha-response']) && !empty($this->request->post['g-recaptcha-response'])) {
          $secret = '6LfPgbYcAAAAAI4cIAVzoRxbrX9KQJvpvvMv9OpE';
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$this->request->post['g-recaptcha-response']);
          $responseData = json_decode($verifyResponse);
          if ($responseData->success) {
            $form = $this->request->post;
            unset($form['g-recaptcha-response']);
            
            @$req = array(
              'get' => $this->request->get,
              'info' => array(
                'user_agent' => $this->request->server['HTTP_USER_AGENT'], 
                'origin' => $this->request->server['HTTP_ORIGIN'], 
                'remote_addr' => $this->request->server['REMOTE_ADDR'], 
                'forwarded' => $this->request->server['HTTP_X_FORWARDED_FOR'], 
              )
            );
      
            $this->load->model('config/config');
            $status = $this->model_config_config->getByChave('gerais');
            $status = json_decode($status['val'], true);
            $status = $status['status'];
      
            $solicitacao = array(
              'joined' => date('Y-m-d H:i:s'),
              'form' => json_encode($form),
              'user' => json_encode($req),
              'status' => $status[0],
              'origem' => 'sobre/orcamento'
            );
  
            // pipefy
            // $this->pipefy->setPipe('contato');
            // $res = $this->pipefy->createCard($info);


            $this->load->model('solicitacao/solicitacao');
            $this->model_solicitacao_solicitacao->add($solicitacao);
            $data['success'] = 'Solicitação de contato enviada com sucesso!';
          } else {
            $data['error'] = 'Falha na verificação do reCaptcha.';
          }
        } else {
          $data['error'] = 'Falha na verificação do reCaptcha.';
        }
  
      }
    }

    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('sobre/orcamento', $data));
  }
}
