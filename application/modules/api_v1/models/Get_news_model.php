<?php 
class Get_news_model extends CI_Model {

  //Generate token for user

	function select_news_sources($table){//select souce data
		$sourceImage = base_url().'backend_assets/img/';
		$this->db->select(NEWS_SOURCES.'.sourceId,newsSourceId,name,description,language,country,concat("'.$sourceImage.'",
        sourceImage) as source_image');
		$query = $this->db->get(NEWS_SOURCES);
		if($query->num_rows()){
			return $query->result();
		}
		return FALSE;
	}//end of functin..
}//end of class..

