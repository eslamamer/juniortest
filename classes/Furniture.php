<?php
    class Furniture extends Product{
        private $width;
        private $height;
        private $length;

        public function __construct($sku, $name, $price, $height, $width, $length){
            parent::__construct($sku, $name, $price, 3); 
            $this->setWidth($width);
            $this->setHeight($height);
            $this->setLength($length);
        }

        public function getWidth(){
            return $this->width;
        }
        public function getHeight(){
            return $this->height;
        }
        public function getLength(){
            return $this->length;
        }
        public function setWidth($width){
            if(isset($width)){
                if(is_numeric($width) && $width > 0){
                    $this->width = $width;
                }else{
                    throw new Exception("width must be a positive number");
                }
            }else{
                throw new Exception("width must be inserted");
            }
        }
        public function setHeight($height){
            if(isset($height)){
                if(is_numeric($height) && $height > 0){
                    $this->height = $height;
                }else{
                    throw new Exception("height must be a positive number");
                }
            }else{
                throw new Exception("height must be inserted");
            }
        }
        public function setLength($length){
            if(isset($length)){
                if(is_numeric($length) && $length > 0){
                    $this->length = $length;
                }else{
                    throw new Exception("length must be a positive number");
                }
            }else{
                throw new Exception("length must be inserted");
            }
        }
        public function save($con){
            try{
                parent::save($con);
                $id     = $this->getId();
                $width  = $this->getWidth();
                $height = $this->getHeight();
                $length = $this->getLength();
                $attr_stmt  = $con->prepare("insert into furniture(id, height, width, length) values(:id, :height, :width, :length)");
                $attr_stmt->bindParam(':id'    , $id    , PDO::PARAM_INT);
                $attr_stmt->bindParam(':height', $height, PDO::PARAM_STR);
                $attr_stmt->bindParam(':width' , $width , PDO::PARAM_STR);
                $attr_stmt->bindParam(':length', $length, PDO::PARAM_STR);
                if($attr_stmt){
                    $attr_stmt->execute();
                }
            }catch(PDOException $e){
                throw new Exception('save Error: ' .$e->getMessage());
            }
        }

        public static function fetchType($con){
            try{
                $stmt = $con->prepare("
                                    select 
                                            p.id, p.sku, p.name, p.price, p.type_id as type,
                                            f.height, f.width, f.length    
                                    from
                                            products p
                                    inner join
                                            furniture f
                                    on      p.id = f.id
            ");
                $stmt->execute();
                $furs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $furs;
            }catch(PDOException $e){
                throw new Exception('Error : '.$e->getMessage());
            }
         }
         public function display(){ 
            $proProps = parent::display();
            $proProps[] = "dimension: " . $this->getHeight() .'×'. $this->getWidth() .'×'. $this->getLength();
            return $proProps;
        }
         public static function selfCreate(array $data): Product{
            if (isset($data['sku'], $data['name'], $data['price'], $data['height'], $data['width'],  $data['length']))
                return new self($data['sku'], $data['name'], $data['price'], $data['height'], $data['width'],  $data['length']);
            else{
                throw new Exception("Missing data for Furniture creation");
            }
        }
    }
?>