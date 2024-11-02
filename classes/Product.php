<?php
    abstract class Product{
        protected $id;
        protected $sku;
        protected $name;
        protected $price;
        protected $type;
        
    public function __construct($sku, $name, $price, $type){
        $this->setSKU($sku);
        $this->setName($name);
        $this->setPrice($price);
        $this->setType($type);
    }

    public function getId(){
        return $this->id;
    }
    public function getSku(){
        return $this->sku;
    }
    public function getName(){
        return $this->name;
    }
    public function getPrice(){
        return $this->price;
    }
    public function getType(){
        return $this->type;
    }

    public function setId($id){
        $this->validateNumericField($id, 'ID');
        $this->id = $id;
    }
    public function setSKU($sku){
        $this->validateStringField($sku, 'SKU');
        $this->sku = $sku;
    }

    public function setName($name){
        $this->validateStringField($name, 'Name');
        $this->name = $name;
    }

    public function setPrice($price){
        $this->validateNumericField($price, 'Price');
        $this->price = $price;
    }

    public function setType($type){
        if(isset($type) && $type > 0){
            $this->type = $type;
        }else{
            throw new Exception("select valid type");
        }
    }

    public function display() {
        return [
            $this->getId(),
            $this->getSku(),
            $this->getName(),
            $this->getPrice()
        ];
    }

    public function save($con){
        try{
            $sku  = $this->getSku();
            $name = $this->getName();
            $price= $this->getPrice();
            $type = $this->getType();
            //if(!$this->isSkuExist($sku, $con)){
                $stmt = $con->prepare("insert into products(sku, name, price, type_id) values(:nsku, :nname, :nprice, :type_id)");
                $stmt->bindParam(':nsku'   , $sku  , PDO::PARAM_STR);
                $stmt->bindParam(':nname'  , $name , PDO::PARAM_STR);
                $stmt->bindParam(':nprice' , $price, PDO::PARAM_STR);
                $stmt->bindParam(':type_id', $type , PDO::PARAM_INT);
                $stmt->execute();
                $this->setId($con->lastInsertId());
           // }else{
            //    throw new Exception("existing SKU");
            //} 
        }catch(Exception $e){
            throw new Exception("save error". $e->getMessage());
        }   
    }

    protected function validateStringField(string $Field, string $FieldName): void{
        if(!isset($Field) || trim($Field) === ""){
            throw new invalidArgumentException("$FieldName cannot be empty, insert text value");
        }
    }

    protected function validateNumericField(&$Field, string $FieldName){
        if(isset($Field) && trim($Field) !== ""){
            if(!is_numeric($Field) || $Field < 0){
                throw new InvalidArgumentException("$FieldName must be positive number");
            }
        }else{
            throw new Exception("$FieldName cannot be empty");
        }
    }

    protected abstract static function fetchType($con);
    protected abstract static function selfCreate(array $data): Product;
    } 
?>