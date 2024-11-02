<?php
    class DVD extends Product{
        private $size; 

        public function __construct($sku, $name, $price, $size){
            parent::__construct($sku, $name, $price, 2);
            $this->setSize($size);
        }

        public function setSize($size){
           if(isset($size)){
                if(is_numeric($size) && $size > 0){
                    $this->size = $size;
                }else{
                    throw new Exception("size must be positive number");
                }
            }else{
                throw new Exception("size must be inserted");
            }
        }

        public function getSize(){
            return $this->size;
        }
        public function save($con){
            try{
                parent::save($con);
                $id   = $this->getId();
                $size = $this->getSize();
                $attr_stmt  = $con->prepare("insert into dvds(id, size) values(:id, :size)");
                $attr_stmt->bindParam(':id'  ,$id   , PDO::PARAM_INT);
                $attr_stmt->bindParam(':size',$size , PDO::PARAM_STR);
                $attr_stmt->execute();
            }catch(Exception $e){
                throw new Exception("save error:". $e.getMessage());
            }
        }

        public static function fetchType($con){
            try{
                $stmt= $con->prepare("
                                        select 
                                            p.id, p.sku, p.name, p.price, p.type_id as type,
                                            d.size
                                        from
                                                products p
                                        inner join
                                                dvds d
                                        on      p.id = d.id
                                ");
                $stmt->execute();
                $dvds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $dvds;
                }catch(PDOException $e){
                    throw new Exception('Error: ' . $e->getMessage());
                }
            }

        public function display(){
            $proProps = parent::display();
            $proProps [] = "size: ".$this->size." MB";
            return $proProps;
        }

        public static function selfCreate(array $data): Product{
            if(isset($data['sku'], $data['name'], $data['price'], $data['size'])){
                return new self($data['sku'], $data['name'], $data['price'], $data['size']);
            }else {
                throw new Exception("Missing data for DVD creation");
            }
        }
    }
?>