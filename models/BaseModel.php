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
        $this->noRecordsMsg = $msg;
        $this->conn = $db;
        if(isset($_GET['id'])){ $this->id = $_GET['id'];}
        if(isset($_GET['authorId'])) $this->authorId = $_GET['authorId'];
        if(isset($_GET['categoryId'])) $this->categoryId = $_GET['categoryId'];
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
        echo "\nechoResponse\n";
        $result = $this->execute($query, $params);

        echo "1\n";
        if($result->rowCount() == 0){
            echo "2\n";
            $this->printMsg();
            return;
        }

        echo "3\n";
        $arr = $this->convertToArray($result);

        echo "4\n";
        if($this->hasId()){
            echo "5\n";
            echo json_encode($arr[0]);
        } else{
            echo "6\n";
            echo json_encode($arr);
        }
    }

    public function execute($query, $params = array()){        
        echo "execute\n";
        
        print_r($this->conn);

        echo "\nexec2\n";
        if(isset($this->conn)){
            echo "Conn is set";
        } else{
            echo "Conn is NOT set";
        }


        $stmt = $this->conn->prepare($query);
        echo "\nexecute 1\n";
        for($i = 1; $i <= count($params); $i++){
            $stmt->bindParam($i, $params[$i-1]);
        }
        echo "execute 2\n";
        $stmt->execute();
        echo "execute 3\n";
        return $stmt;  
    }

    public function hasData($query, $params = array()){
        return $this->execute($query, $params)->rowCount() > 0;
    }

    private function convertToArray($result){
        echo "convertToArray\n";
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