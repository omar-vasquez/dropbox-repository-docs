<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller
{
    public function __construct()
    {
    	parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }
    // Call this method first by visiting http://SITE_URL/example/request_dropbox
    public function request_dropbox()
	{
		$params['key'] = 'upusybf4ob3ggcs';
		$params['secret'] = '8czu5myju6bafea';
    		$this->load->library('dropbox', $params);
    		$data = $this->dropbox->get_request_token(site_url("example/access_dropbox"));
    		$this->session->set_userdata('token_secret', $data['token_secret']);
    		redirect($data['redirect']);
	}
	//This method should not be called directly, it will be called after 
    //the user approves your application and dropbox redirects to it
	public function access_dropbox()
	{
      $params['key'] = 'upusybf4ob3ggcs';
      $params['secret'] = '8czu5myju6bafea';
        	$this->load->library('dropbox', $params);
        	$oauth = $this->dropbox->get_access_token($this->session->userdata('token_secret'));
        
          $this->session->set_userdata('oauth_token', $oauth['oauth_token']);
          $this->session->set_userdata('oauth_token_secret', $oauth['oauth_token_secret']);
          redirect('example/test_dropbox');
	}
	//Once your application is approved you can proceed to load the library
    //with the access token data stored in the session. If you see your account
    //information printed out then you have successfully authenticated with
    //dropbox and can use the library to interact with your account.
	public function test_dropbox()
	{
		$params['key'] = 'upusybf4ob3ggcs';
    $params['secret'] = '8czu5myju6bafea';
		$params['access'] = array('oauth_token'=>urlencode($this->session->userdata('oauth_token')),
								  'oauth_token_secret'=>urlencode($this->session->userdata('oauth_token_secret')));
		
        $this->load->library('dropbox', $params);
        $dbobj = $this->dropbox->account();
        //$this->dropbox->add('/carpeta3','C:/Users/jn/Documents/om.txt',array(),$root="dropbox");

        //$this->dropbox->move('carpeta3/omar/key.txt','carpeta3/omar/omar2.txt',$root='dropbox');
       $res = $this -> dropbox -> metadata('carpeta3/',array(), $root='dropbox');
       var_dump($res);
        var_dump($dbobj);
	}

   public function test(){
      var_dump($_FILES);
   }

   /** Aut. Omua ### Métodos para la manipulacion de archivos con la libreria codeigniter
   *  MOVE :método ->move('carpeta3/key.txt','carpeta3/omar/key.txt',$root='dropbox');
   *       (Primer parametro: el archivo a copiar, Segundo parámetro: El lugar a poner con el nuevo nombre)
   *       Nota: podemos usarlo para renombrar los archivos.
   *  ADD: ->add('/carpeta3','C:/Users/jn/Documents/om.txt',array(),$root="dropbox");
   *       (primer parámetro: lugar desde la raiz de dropbox, segundo parámetro: la direccion del archivos(pueden ser temporales)
   *       tercer parámetro: informacion extrá para enviar, default: array()).
   * METADATA: -> metadata('carpeta3/',array(), $root='dropbox');
   *        (primer parámetro, el direcctorio a revisar, default = array(), default=$root='dropbox')
   *       Nota: los datos son devuetos como objetos
   */
}

/* End of file example.php */
/* Location: ./application/controllers/welcome.php */