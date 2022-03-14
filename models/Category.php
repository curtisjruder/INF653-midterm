<?php
class Category extends BaseModel{
    private $p_cat = "";
    private $p_catId = -1;
    

    public function __construct($db){
        parent::__construct($db, "categoryId Not Found");       

        $data = json_decode(file_get_contents("php://input"));    
        
        if(!isset($data)) return;

        if(property_exists($data, 'id')) $this->p_catId = htmlspecialchars(strip_tags($data->id));    
        if(property_exists($data, 'category')) $this->p_cat = htmlspecialchars(strip_tags($data->category));        
    }

    public function read(){
        $query = "Select * from categories";
        $arr = array();

        if($this->hasId()){
            $query = $query . " where id = ?";
            $arr = array($this->id);
        }   

        $this->echoResponse($query, $arr);
    }

    public function update(){
        if(!$this->isValidUpdate()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        if(!$this->isValid()) return;

        $query = "Update categories set category=? where id=?";
        $this->execute($query, array($this->p_cat, $this->p_catId));

        $this->id = $this->p_catId;
        $this->read();
    }

    public function create(){    
        if(!$this->isValidCreate()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        $query = "Insert into categories (category) values (?)";
        $this->execute($query, array($this->p_cat));

        $this->id = $this->getInsertId();
        $this->read();
    }

    public function delete(){
        if(!$this->isValidDelete()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        $query = "Delete from categories where id=?";
        $this->execute($query, array($this->p_catId));       
        $this->printMsg($this->p_catId, "id");
    }

    private function isValidCreate(){
        return $this->p_cat != "";
    }
    private function isValidUpdate(){        
        return $this->p_catId > 0  && $this->p_cat != "";
    }
    private function isValidDelete(){
        return $this->p_catId > 0;
    }

    private function isValid(){      
        if(!$this->hasData("Select * from categories where id=?", array($this->p_catId))){
            $this->printMsg("categoryId Not Found");
            return false;
        }

        return true;
    }
}