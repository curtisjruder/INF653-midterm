<?php
class Quote extends BaseModel{
    private $p_quote = "";
    private $p_id = -1;
    private $p_catId = -1;
    private $p_authorId = -1;

    public function __construct($db){
        parent::__construct($db, "No Quotes Found");    

        $data = json_decode(file_get_contents("php://input"));  
        if(!isset($data)) return;

        if(property_exists($data, 'id')) $this->p_id = htmlspecialchars(strip_tags($data->id));            
        if(property_exists($data, 'quote')) $this->p_quote = htmlspecialchars(strip_tags($data->quote));                        
        if(property_exists($data, 'categoryId')) $this->p_catId = htmlspecialchars(strip_tags($data->categoryId));    
        if(property_exists($data, 'authorId')) $this->p_authorId = htmlspecialchars(strip_tags($data->authorId));    
    }

    public function read(){
        $query = "Select q.id, quote, author, category from quotes q inner join authors a on a.id = q.authorId inner join categories c on c.id = q.categoryId";
        $arr = array();

        if($this->hasId()){
            $query = $query . " where q.id = ?";
            $arr = array($this->id);
        } else if($this->hasParameters()) {
            if($this->hasAuthorId() && $this->hasCategoryId()){
                $query = $query . " where a.id = ? AND c.id = ?";
                $arr = array($this->authorId, $this->categoryId);
            } else if($this->hasAuthorId()){
                $query = $query . " where a.id = ?";
                $arr = array($this->authorId);
            } else{
                $query = $query . " where c.id = ?";
                $arr = array($this->categoryId);
            }
        }
        $query = $query . " order by q.id";
        $this->echoResponse($query, $arr);
    }


    public function create(){ 
        if(!$this->isValidCreate()){
            $this->printMsg("Missing Required Parameters");
            return false;
        }
        
        if(!$this->isValid()) return;

        $query = "Insert into quotes(quote, authorId, categoryId) values (?, ?, ?)";
        $this->execute($query, array($this->p_quote, $this->p_authorId, $this->p_catId));

        $this->id = $this->getInsertId();
        $this->read();
    }

    public function delete(){
        if(!$this->isValidDelete()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        $query = "Delete from quotes where id=?";
        $this->execute($query, array($this->p_id));       
        $this->printMsg($this->p_authorId, "id");
    }

    public function update(){
        if(!$this->isValidUpdate()){
            $this->printMsg("Missing Required Parameters");
            return;
        }

        if(!$this->isValid()) return;

        $query = "Update quotes set quote=?, authorId=?, categoryId=?  where id=?";
        $this->execute($query, array($this->p_quote, $this->p_authorId, $this->p_catId, $this->p_id));

        $this->id = $this->p_id;
        $this->read();
    }

    private function isValidCreate(){
        return $this->p_quote != "" && $this->p_catId > 0 && $this->p_authorId > 0;
    }
    private function isValidUpdate(){        
        return $this->p_id > 0  && $this->isValidCreate();
    }
    private function isValidDelete(){
        return $this->p_id > 0;
    }

    
    private function isValid(){      
        if(!$this->hasData("Select * from categories where id=?", array($this->p_catId))){
            $this->printMsg("categoryId Not Found");
            return false;
        }

        if(!$this->hasData("Select * from authors where id=?", array($this->p_authorId))){
            $this->printMsg("authorId Not Found");
            return false;
        }

        return true;
    }
}
