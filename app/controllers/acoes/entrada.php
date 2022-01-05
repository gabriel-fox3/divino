<?php

namespace Mubbi;

use Libern\QRCodeReader\QRCodeReader;

class ControllerAcoesEntrada extends BaseController {
  public function index() {

    $this->load->language('common/home');

    $this->document->setTitle('Entrada');
    $this->document->addStyle('css/styles/entrada');
    $this->document->addScript('js/plugins/webcam');

    $data['url_check_image'] = $this->url->link('acoes/entrada/qrcode');
    
    // $QRCodeReader = new QRCodeReader();
    // $qrcode_text = $QRCodeReader->decode(ASSETS . 'qrcode.png');
    // echo $qrcode_text;

    
    $data['sidebar'] = $this->load->controller('common/sidebar');
    $data['navbar'] = $this->load->controller('common/navbar');
    $data['header'] = $this->load->controller('common/header', $data);
    $data['footer'] = $this->load->controller('common/footer', $data);

    $this->response->setOutput($this->load->view('acoes/entrada', $data));
  }

  public function qrcode() {
    
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      if (isset($this->request->post['b64']) && $this->request->post['b64'] !== '') {
        
        $b64 = html_entity_decode($this->request->post['b64']);
        $filename = UPLOADS_DIR . 'temp/' . uniqid() . '.jpg';

        file_put_contents($filename, base64_decode(explode(',', $b64)[1]));

        $QRCodeReader = new QRCodeReader();
        $qrcode_text = $QRCodeReader->decode($filename);
        unlink($filename);

        $this->response->json(array('error' => false, 'msg' => $qrcode_text));
      }
    }
    
  }

}
?>