<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dpbxrepo extends CI_Controller
{
    public static $dirname = '/direcctorio-iso';
    public function __construct()
    {
      parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Dpbxrepo_model');
        $this->empresa = 1;
        $this->user = 'omar vasquez';
    }
    //Definir el  directorio de dropbox
    public static function directorio(){return $dir='/directorio-iso';}
    public static function empresa(){return $empresa;}
    // Call this method first by visiting http://SITE_URL/example/request_dropbox
    public function request_dropbox()
  {
        $ob = $this->Dpbxrepo_model->keys($this->empresa);
        $params['key']    = $ob->key;
        $params['secret'] = $ob->key_secret;
        $this->load->library('dropbox', $params);

        $data             = $this->dropbox->get_request_token(site_url("dpbxrepo/access_dropbox"));
        $this->session->set_userdata('token_secret', $data['token_secret']);
        redirect($data['redirect']); 
        
  }
  //This method should not be called directly, it will be called after 
    //the user approves your application and dropbox redirects to it
  public function access_dropbox()
  {
        $ob = $this->Dpbxrepo_model->keys($this->empresa);
        $params['key']    = $ob->key;
        $params['secret'] = $ob->key_secret;
        $this->load->library('dropbox', $params);
        $oauth            = $this->dropbox->get_access_token($this->session->userdata('token_secret'));
      
        $this->session->set_userdata('oauth_token', $oauth['oauth_token']);
        $this->session->set_userdata('oauth_token_secret', $oauth['oauth_token_secret']);
        redirect('dpbxrepo/inicio');
}
  //Once your application is approved you can proceed to load the library
    //with the access token data stored in the session. If you see your account
    //information printed out then you have successfully authenticated with
    //dropbox and can use the library to interact with your account.
  private  function dpbxconexion()
  {
      $ob = $this->Dpbxrepo_model->keys($this->empresa);
      $params['key']    = $ob->key;
      $params['secret'] = $ob->key_secret;
      $params['access']    = array('oauth_token'=>urlencode($this->session->userdata('oauth_token')),
      'oauth_token_secret' =>urlencode($this->session->userdata('oauth_token_secret')));
      $this->load->library('dropbox', $params);
      #$this->dropbox->create_folder(Dpbxrepo::directorio(),$root='dropbox');
      $ob=$this->dropbox->account();
      if (!isset($ob->error)) 
        # code...
        return  $ob;
      else 
      redirect('dpbxrepo/request_dropbox');
  }

    #hacemos una consulta al directorio
    #
    #crear objeto y un nuevo array para almacenar el arbol de contenidos por carpeta
    #exploracion de direcorios $DIR
    #informacion para los archivos temporales.
    #Asigamos el nombre el folder

  private function listadirectorios($REPO){
      $DIR       =  $this-> dropbox -> metadata($REPO,array(), $root='dropbox');
      $REGISTROS = $this->Dpbxrepo_model->lista($this->empresa);
      $OBJECT    =array();
      $i = 0;
  foreach ($DIR->contents as $key => $value) {
       $DATA = array();
       if ($value->icon == 'folder') {

       $temp           = $this-> dropbox -> metadata($value->path,array(), $root='dropbox');
       $indice         = explode('/',$temp->path);
       $DATA['nombre'] =  $indice[count($indice)-1];

       foreach ($REGISTROS as $key => $value) {
          if ($value->nombre_repositorio==$DATA['nombre']) 
            $DATA['nota']= $value->descripcion;
          else 
            $DATA['nota']= 'sin descripcion';
          }

       #creamos el contenedor de documentos
       $DATA['docs']=array();
         foreach ($temp->contents as $key => $val) {

          if ($val->icon=='folder') {
               $DATA['history'] = $val->path;   
               $DATA['info']  = str_replace('/history', '', $val->path);
              }else{
                $ext          = explode('.',$val->path);
                $DATA['docs'] =$val->path;
                $DATA['date'] =$val->modified;
                $DATA['ext']  =$ext[1];
           } 
        }#fin de foreach de busqueda de contenido
         $OBJECT[$i]=(object) $DATA;
         $i++;
       }
    }
    return $OBJECT;
  }

  #consulta de la subcarpetas
  public function direcctorio($direction){
        $history = $this-> dropbox 
           -> metadata($direction,array(), $root='dropbox');
        //**listando el historial de la carpeta history*+//
             if ($history->contents) {
                  # comprovar que halla datos...
                  foreach ($history->contents as $k => $v) {
                  # Agregar en un array la lista para el historial...
                  if ($v->icon=='folder') {
                    $contenido[]=$v->path;
                    
                  }
                 }
             }else $contenido[]='Sin datos';
         //**fin listando el historial de la carpeta history*+//
    return (object) $contenido;
  }

    #consulta de la subcarpetas
   public function history($direction){
        $history = $this-> dropbox 
           -> metadata($direction,array(), $root='dropbox');
        //**listando el historial de la carpeta history*+//
             if ($history->contents) {
                  # comprovar que halla datos...
                  foreach ($history->contents as $k => $v) {
                  # Agregar en un array la lista para el historial...
                  if ($v->icon!='folder') {
                    $contenido[$k]['directorio']=$v->path;
                    $indice         = explode('/',$v->path);
                    $contenido[$k]['name'] = strtoupper( $indice[count($indice)-1]);
                    $contenido[$k]['date'] = $v->modified;
                  }
                 }
             }else $contenido=null;
         //**fin listando el historial de la carpeta history*+//
    return (object) $contenido;
  }


  public function newrepo()  {
     
     $this->dpbxconexion();
     $base   = $_POST['directorio'];
     $path   = $_FILES['inputFile']['name'];
     $ext    = pathinfo($path, PATHINFO_EXTENSION);
     $name   = pathinfo($path, PATHINFO_FILENAME);

    if (!empty($_POST['repositorio-name'])) 
       {
         $slug  = url_title($_POST['repositorio-name'], 'dash', TRUE);
       }
      else
      {
        $slug  = url_title($name, 'dash', TRUE);
      }

     $data   = str_replace('\\', '/', $_FILES['inputFile']['tmp_name']);
     $dest   = $base.'/'.$slug.'/';
     $conv   = $base.'/'.$slug.'/'.$slug.'.'.$ext;
     $nomtmp = pathinfo($_FILES['inputFile']['tmp_name'],PATHINFO_BASENAME);

     $this->Dpbxrepo_model->nuevorepo($this->empresa,$slug,$_POST['repositorio-descripcion']);
     $this->dropbox->add($dest,$data,array(),$root="dropbox");
     $this->dropbox->move($dest.$nomtmp,$conv,$root='dropbox');     
     $this->dropbox->create_folder($base.'/'.$slug.'/'.'history',$root="dropbox");
     redirect('dpbxrepo/inicio'.$base); 

    }

  public function update($value='')
  {
   $this->dpbxconexion();
   $DOCNOW   = $_POST['dir_name'];
   $DIRHIS   = $_POST['dir_history'];
   $FHupdate =date('@d-m-Y@H:i:s'); 
   $DocName  = strtolower($_POST['doc_name']);
   $ext      = pathinfo($DocName,PATHINFO_EXTENSION);
   $postactual=  $this->Dpbxrepo_model->getactual(pathinfo($DocName,PATHINFO_FILENAME));

   $newName  = $postactual[0]->num_revision.'-rev'.pathinfo($DocName,PATHINFO_FILENAME).$FHupdate.'@.'.$ext;
   $nomtmp   = pathinfo($_FILES['inputFile']['tmp_name'],PATHINFO_BASENAME);
   $data     = str_replace('\\', '/', $_FILES['inputFile']['tmp_name']);   
   $NEWDIR   = str_replace('/history', '', $DIRHIS);
   $NEWEXT   = pathinfo($DocName,PATHINFO_FILENAME).'.'.
                pathinfo($_FILES['inputFile']['name'],PATHINFO_EXTENSION);


   $this->dropbox->move($DOCNOW,$DIRHIS.'/'.$newName,$root='dropbox');  
   $this->dropbox->add($NEWDIR,$data,array(),$root="dropbox");
   $this->dropbox->move($NEWDIR.'/'.$nomtmp,$NEWDIR.'/'.$NEWEXT,$root='dropbox');

   $this->Dpbxrepo_model->update($RepoName,$_POST['nota'],$this->user,$postactual[0]->id+1); 
   $this->Dpbxrepo_model->history($_POST,$newName,$postactual[0]->id,$postactual[0]->autor);
   redirect('dpbxrepo/inicio'.$NEWDIR); 
  }

  public function dowload($value='')
  {
     $this->dpbxconexion();
     $path = $_POST['download_file'];
     $ob=$this->dropbox->media($path,$root='dropbox');
     redirect($ob->url);
  }

  public  function info($dir='', $doc='')
  {
     $INFO=$this->Dpbxrepo_model->info($doc);
     $OB=$this->dropbox-> metadata($dir,array(), $root='dropbox');
     var_dump($OB);
     return $INFO;
  }


   public function inicio($value='',$folder='',$name='',$history=''){
    $inforempresa= $this -> dpbxconexion();
    $data['empresa']=$inforempresa;
    
    if ($history!='') {
          $LINK = '/'.$value.'/'.$folder.'/'.$name.'/'.$history;
          $data['historial'] =  $this->history($LINK);
          $data['directorio'] = $LINK;
          $data['arbol'] ='<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'">'.$value.'</a>&nbsp;/&nbsp;'.
          '<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'/'.$folder.'">'.$folder.'</a>'.
          '&nbsp;/&nbsp;'.$name.'-Historial';
          $this->load->view('dropbox/history_dpbx',$data);
    } elseif ($name!='') {
          $LINK = '/'.$value.'/'.$folder;
          $data['info'] = $this->info($LINK,$name);
          //$data['listado']    =  $this->listadirectorios($LINK);
          $data['arbol']      ='&nbsp;/&nbsp;<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'">'.$value.'</a>&nbsp;/&nbsp;'.
           '<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'/'.$folder.'">'.$folder.'</a>'.
          '&nbsp;/&nbsp;'.
           '<a href="'.base_url().
          'index.php/dpbxrepo/inicio/$value/$folder/$name">'.$name.'</a>';
          //$data['directorio'] = $LINK; 
          $this->load->view('dropbox/info_dpbx',$data); 
    } elseif ($folder!='') {
          $LINK = '/'.$value.'/'.$folder;
          $data['listado']    =  $this->listadirectorios($LINK);
          $data['arbol']      ='&nbsp;/&nbsp;<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'">'.$value.'</a>&nbsp;/&nbsp;'.$folder; 
          $data['directorio'] = $LINK; 
          $this->load->view('dropbox/form_dpbx_principal',$data);
    } else {
          $data['listado'] =  $this->direcctorio($value);
          $data['arbol'] ='&nbsp;/&nbsp;<a href="'.base_url().
          'index.php/dpbxrepo/inicio/'.$value.'">'.$value.'</a>'.$folder; 
          $this->load->view('dropbox/folder_dpbx',$data);
    }
  }

  public function NewModulo() { 
    #--> lista de carpeta a crear..
    $CARPETAS = ['procedimientos','formatos','instructivos','manuales'];
    if (isset($_POST['name_modulo'])) {
      $this -> dpbxconexion();
      $SLUG  = url_title($_POST['name_modulo'], 'dash', TRUE);
      foreach ($CARPETAS as $nombre) {
         $this->dropbox->create_folder('/'.$SLUG.'/'.$nombre,$root="dropbox");
      }
    } else {
     redirect('dpbxrepo/inicio');
    }
     redirect('dpbxrepo/inicio');
  }

  public function buscar($modulo='',$folder='',$name=''){
    $slug = url_title($name, 'dash', FALSE);
    $ERROR = false; 
    $this -> dpbxconexion();
    $path='/'.$modulo.'/'.$folder;
    $RESULT = $this-> direcctorio($path);
    foreach ($RESULT as $b) {
      $mystring = $b;
      $findme   = $slug;
      $pos = strpos($mystring, $findme);
      if ($pos !== false) {
        $ERROR = TRUE; 
      } 
    }
    if (empty($name)) {
       $ERROR = TRUE; 
    }
        $resultado = ($ERROR) ? 'error' : 'correcto' ;
        echo $resultado;
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