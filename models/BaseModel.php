<?php
class BaseModel{
    private $conn;
    private $noRecordsMsg;

    private $data;

    public $id = -1;
    public $authorId = -1;
    public $categoryId = -1;
    public $inputText = "";

    public function __construct($db, $msg){
        echo "Inside parent class\n";
        $this->noRecordsMsg = $msg;
        echo "P1\n";
        $this->conn = $db;
        echo "P2\n";
        if(isset($_GET['id'])){ $this->id = $_GET['id'];}
        echo "P3\n";
        if(isset($_GET['authorId'])) $this->authorId = $_GET['authorId'];
        echo "P4\n";
        if(isset($_GET['categoryId'])) $this->categoryId = $_GET['categoryId'];
        echo "P5\n";
    }

    public function hasParameters(){
        return $this->hasId() || $this->hasAuthorId() || $this->hasCategoryId();
    }
    public function hasId(){
        return ($this->id > 0);
    }
    public function hasAuthorId(){
        return $this->authorId > 0;
    }
    public function hasCategoryId(){
        return $this->categoryId > 0;
    }

    public function printMsg($msg = "", $key = "message"){
        if($msg == "") $msg = $this->noRecordsMsg;
        echo json_encode([$key => $msg]);
    }

    public function echoResponse($query, $params = array()){
        $result = $this->execute($query, $params);

        if($result->rowCount() == 0){
            $this->printMsg();
            return;
        }

        $arr = $this->convertToArray($result);

        if($this->hasId()){
            echo json_encode($arr[0]);
        } else{
            echo json_encode($arr);
        }
    }

    public function execute($query, $params = array()){        
        $stmt = $this->conn->prepare($query);
        
        for($i = 1; $i <= count($params); $i++){
            $stmt->bindParam($i, $params[$i-1]);
        }

        $stmt->execute();

        return $stmt;  
    }

    public function hasData($query, $params = array()){
        return $this->execute($query, $params)->rowCount() > 0;
    }

    private function convertToArray($result){
        $arr = array();
        $keys = null;

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            if($keys == null){$keys = array_keys($row);}

            $item;
            for($i = 0; $i < count($row); $i++){
                $item[$keys[$i]] = $row[$keys[$i]];
            }

            array_push($arr, $item);
        }

        return $arr;
    }
    public function getInsertId(){
        return $this->conn->lastInsertId();
    }
}