<?php
    class Book extends Product{
        
        private $weight;

        public function __construct($sku, $name, $price, $weight){
            parent::__construct($sku, $name, $price, 1);
            $this->setWeight($weight);
        }

        public function setWeight($weight){
            if(isset($weight)){
                if(is_numeric($weight) && $weight > 0){
                    $this->weight = $weight;
                }else{
                    throw new Exception("weight must be a positive number");
                }
            }else{
                throw new Exception("weight must be inserted");
            }
        }

        public function getWeight(){
            return $this->weight;
        }

        public function save($con){
            try{
                parent::save($con);
                $id =$this->getId();
                $weight = $this->getWeight();
                $attr_stmt  = $con->prepare("insert into books(id, weight) values(:id, :weight)");
                $attr_stmt->bindParam(':id'    , $id    , PDO::PARAM_INT );
                $attr_stmt->bindParam(':weight', $weight, PDO::PARAM_STR);
                $attr_stmt->execute();
            }catch(Exception $e){
                throw new Exception('save Error: ' .$e->getMessage());
            }
        }    
        
        public static function fetchType($con){
            try{
                $pro_stmt = $con->prepare("select
                                                    p.id, p.sku, p.name, p.price, p.type_id as type,
                                                    b.weight
                                            from
                                                    products p
                                            inner join
                                                    books b
                                            on      p.id = b.id
                                        ");
                $pro_stmt->execute();
                $books = $pro_stmt->fetchAll(PDO::FETCH_ASSOC);

                return $books;
            }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
         }

         public function display() {
            $proProps = parent::display();
            $proProps[]= "weight: " . $this->getWeight()." KG";
            return $proProps;
        }
        
        public static function selfCreate(array $data): Product{
            if(isset($data['sku'], $data['name'], $data['price'], $data['weight'])){
                return new self($data['sku'], $data['name'], $data['price'], $data['weight']);
            } else {
                throw new Exception("Missing data for book creation");
            }
        }

        
    }
?>