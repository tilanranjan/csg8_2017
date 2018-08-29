<?php

class BooksHandler {
    private $conn;
    private $db;
    function __construct()
    {
        require_once dirname(__FILE__) . '/../DbConnect.php';
        // opening db connection
        $this->db = new DbConnect();
        $this->conn = $this->db->connect();
    }

    public function addBook($book){
            $stmtbook = null;
            $response = new SuccessResponse();

            $bookDate = date("Y-m-d");
            
            $this->db->beginTransaction();

            $stmtbook = $this->conn->prepare("INSERT INTO `book` (name,img_url,description) VALUES (?,?,?)");
            $stmtbook->bind_param('sss',
                $book->name,
                $book->img_url,
                $book->description
            );
            $isSuccess = $stmtbook->execute();
            $stmtbook->close();
            $this->db->commit();

            if($isSuccess){
                $response  = new SuccessResponse();
                $response->set_data($book);
            } else {
                $this->db->rollback();
                $response = new FailureResponse();
                $response->set_error_code(0);
                $response->set_message("Unable to add book");
            }

        return Serializer::serialize($response);
    }

    
    public function getBooks() {

        $successResponse = new SuccessResponse();
        
        $stmt = $this->conn->prepare("SELECT * from `book`");
        
        $isSuccess = $stmt->execute();
        
        $response = null;

        if($isSuccess){
            $response = new SuccessResponse();
            $booksRecordSet = $stmt->get_result();
            $books = array();
        
            while ($row = $booksRecordSet->fetch_assoc()){
                $books[] = $row;
            }
            // var_dump($books);exit;
            $response->set_data($books);
        } else {
            $response = new FailureResponse();
            $response->set_error_code(0);
            $response->set_message("Unable to execute get books query");
        }

        return Serializer::serialize($response);
    }

    public function deleteBook($id){
        $response = new SuccessResponse();

        try{
            $this->db->beginTransaction();
            $stmtBook = $this->conn->prepare("DELETE FROM `Book` where id={$id}");
            $isSuccess = $stmtBook->execute();
            $this->db->commit();
        } catch(Exception $e){
            $this->db->rollback();
        }

        if($isSuccess){
            $response  = new SuccessResponse();
        } else {
            $response = new FailureResponse();
            $response->set_error_code(0);
            $response->set_message("Unable to execute delete book query");
        }

        return Serializer::serialize($response);
    }
}

?>