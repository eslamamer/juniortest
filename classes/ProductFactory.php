<?php
    class ProductFactory{
        private $proTypes = [];

        public function __construct($con){
            $this->fetchTypes($con);
        }

        public function getProTypes(){
            return $this->proTypes;
        }
        
        public function fetchTypes($con){
            try{
                $stmt = $con->prepare("select * from products_types");
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($rows as $row){
                    if(!array_key_exists($row['id'], $this->proTypes)){
                        $this->proTypes[$row['id']] = $row['type'];   
                    }
                }
            }catch(PDOException $e){
                throw new Exception("fetch type exception: ". $e.getMessage());
            }  
        }

        public function createProduct($data){
            try{
                if(is_numeric($data['type']) && $data['type'] > 0){
                    $className = $this->proTypes[$data['type']];
                    if(class_exists($className)){
                        return $className::selfCreate($data);
                    }  
                }else{
                    throw new Exception('Select valied type');
                } 
            }catch(Exception $e){
                throw new Exception('create product exception: '. $e->getMessage());
            }         
        }
    }
?>