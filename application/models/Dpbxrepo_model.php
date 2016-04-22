<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dpbxrepo_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
    //Do your magic here
  }

  public function keys($id)
  {
      $query = $this->db->query('SELECT * FROM repo_empresa WHERE id='.$id);
      $ob =$query->result();
      $ob=$ob[0];
      return  $ob;
  }


  public function nuevorepo($id,$post,$descripcion,$autor ='sin autor',$rev='sin revision',$num_rev=0)
  {
    $data = array(
    'id_empresa'         => $id,
    'nombre_repositorio' => url_title($post,'dash', TRUE),
    'descripcion'        =>$descripcion,
    'autor'              =>$autor,
    'revision'           =>$rev,
    'num_revision'       =>$num_rev,
     );
    $this->db->insert('repositorio', $data);
  }

  public function history($post,$newname,$id_post,$user='')
  {
    $data = array(
    'anotacion'      => $post['nota_actual'],
    'id_repositorio' => $id_post,
    'archivo'        =>$newname,
    'user'           =>$user,
     );
    $this->db->insert('repositorio_history', $data);
  }
  public function getactual($NameRepo)
  {
    $this->db->select('descripcion,autor,id');
    $this->db->from('repositorio');
    $this->db->where('nombre_repositorio', $NameRepo);
    $query = $this->db->get(); 

    return $query->result();
  }


  public function historylist($id)
  {
     $this->db->from('repositorio_history');
     $this->db->where('id_repositorio', $id);
     $this->db->order_by('datetime_update', 'DESC');
     $query = $this->db->get(); 
     return $query->result();
  }

  public function update($NameRepo,$nota,$user='',$num)
  {
     $data = array(
          'descripcion' => $nota,
          'autor'       =>$user,
          'num_revision'=>$num,
    );

    $this->db->where('nombre_repositorio', $NameRepo);
    $this->db->update('repositorio', $data);
  }

  public function lista($id)
  {  
    $query = $this->db->query('SELECT * FROM repositorio WHERE id_empresa='.$id);
    return $query->result();

  }

  public function info($doc){
    $this->db->from('repositorio');
     $this->db->where('nombre_repositorio', $doc);
     $query = $this->db->get(); 
     return $query;
  }


}

/* End of file Dpbxrepo_model.php */
/* Location: ./application/models/Dpbxrepo_model.php */