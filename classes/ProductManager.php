<?php    
    spl_autoload_register(function($name){
        require $name.'.php';
    });

    class ProductManager {
        private $con;
        private $factory;

        public function __construct(){
            $db            = Database::getInstance();
            $this->con     = $db->getConnection();
            $this->factory = new ProductFactory($this->con);
        }

               
        public function save($data){
            try{
                if($this->isSkuExist($data['sku'])){
                    throw new Exception("SKU is already exist");
                }
                $this->con->beginTransaction();
                $pro = $this->factory->createProduct($data);
                $pro->save($this->con);
                $this->con->commit();
            }catch(PDOException $e){
                $this->con->rollBack();
                throw new Exception('Save Error:' .$e->getMessage());
            }catch(Exception $e){
                $this->con->rollBack();
                throw new Exception('Unexpected Error:' .$e->getMessage());
            }
        }

        public function fetchproducts(){
            try{
                $products = [];
                $objsProps = [];
                foreach($this->factory->getProTypes() as $type){
                    $products = array_merge($products, $type::fetchType($this->con));
                }
                usort($products, function($a, $b){
                    return $a['id'] <=> $b['id'];
                });
                foreach($products as $pro){
                        $obj = $this->factory->createProduct($pro);
                        $obj->setId($pro['id']);
                        $objsProps[] =  $obj->display();
                   }
                    return $objsProps;
            }catch(Exception $e){
                throw new Exception('Error: '.$e->getMessage());
            }
        }  

        public function deleteProduct($id){
            try{
                if(isset($id)){
                    $this->con->beginTransaction();
                    $stmt = $this->con->prepare("delete from products where id = :rid");
                    $stmt->bindParam(':rid', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $this->con->commit();
                }  
            }catch(PDOException $e){
                $this->con->rollBack();
                throw new Exception('Delete Error: '.$e->getMessage());
            }catch(Exception $e){
                $this->con->rollBack();
                throw new Exception('Unexpected Error: '.$e->getMessage());
            }
        }

        public function isSkuExist($sku){
            try{
                $stmt =$this->con->prepare("select count(*) from products where sku = :sku");
                $stmt->bindParam(':sku', $sku, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->fetchColumn();
                return $count > 0;
            }catch(PDOException $e){
                throw new Exception('Error' .$e->getMessage());
            }
        }

        public function validateAttr(string $attr, array $input, array &$data, array &$errors){
            if (isset($input[$attr]) && trim($input[$attr]) !== ""){
                if(is_numeric($input[$attr]) && $input[$attr] > 0){
                    $data[$attr] = htmlspecialchars(filter_var($input[$attr], FILTER_SANITIZE_SPECIAL_CHARS));
                }else{
                    $errors [] = "$attr must be positive number"; 
                }
            }else{
                $errors [] = "$attr is required"; 
            }
        }    

        public function pageTitle(){
            global $pageTitle;
            return isset($pageTitle) ? htmlspecialchars($pageTitle) : htmlspecialchars('Product List');
        } 
    }
?>