<?php
class Author extends BaseModel{ 
    private $p_author = "";
    private $p_authorId = -1;
    
    public function __construct($db){
        parent::__construct($db, "authorId Not Found");   
        
        $data = json_decode(file_get_contents("php://input"));   
        
        if(!isset($data)) return;

        if(property_exists($data, 'id')) $this->p_authorId = htmlspecialchars(strip_tags($data->id));    
        if(property_exists($data, 'author')) $this->p_author = htmlspecialchars(strip_tags($data->author));        
    }
   
    public function read(){
        $query = "Select * from authors";
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

        $query = "Update authors set author=? where id=?";
        $this->execute($query, array($this->p_author, $this->p_authorId));

        $this->id = $this->p_authorId;
        $this->read();
    }



    public function create(){    
        if(!$this->isValidCreate()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        $query = "Insert into authors (author) values (?)";
        $this->execute($query, array($this->p_author));

        $this->id = $this->getInsertId();
        $this->read();
    }

    public function delete(){
        if(!$this->isValidDelete()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        $query = "Delete from authors where id=?";
        $this->execute($query, array($this->p_authorId));       
        $this->printMsg($this->p_authorId, "id");
    }

    private function isValidCreate(){
        return $this->p_author != "";
    }
    private function isValidUpdate(){        
        return $this->p_authorId > 0  && $this->p_author != "";
    }
    private function isValidDelete(){
        return $this->p_authorId > 0;
    }

    private function isValid(){      
        if(!$this->hasData("Select * from authors where id=?", array($this->p_authorId))){
            $this->printMsg("authorId Not Found");
            return false;
        }

        return true;
    }
}