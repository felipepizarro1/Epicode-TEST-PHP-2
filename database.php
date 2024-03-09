<?php
    namespace db {
        use PDO;
        class DB_PDO {
            // Classe con pattern Singleton
            private PDO $conn;
            private static ?DB_PDO $instance = null;

            private function __construct(array $config){
                // 'mysql:host=localhost; port=3306; dbname=biblioteca
                $this->conn = new PDO(
                                        $config['driver'].":host=".$config['host']."; port=".$config['port']."; dbname=".$config['database'].";", 
                                        $config['user'], 
                                        $config['password']);
                                        
                                        $this->createUsersTable();
            }
            private function createUsersTable() {
                $sql = "CREATE TABLE IF NOT EXISTS users (
                    id INT NOT NULL AUTO_INCREMENT,
                    username VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    PRIMARY KEY (id)
                )";
                $this->conn->exec($sql);
            }

            public static function getInstance(array $config){
                if(!static::$instance) {
                    static::$instance = new DB_PDO($config);
                }
                return static::$instance;
            }

            public function getConnection(){
                return $this->conn;
            }
//CRUD OPERATIONS:
            public function getAllUsers() {
                $sql = "SELECT * FROM users";
                $stmt = $this->conn->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            public function updateUser($id, $username, $password) {
                $sql = "UPDATE users SET username = :username, password = :password WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                return $stmt->execute();
            }
            
            public function deleteUser($id) {
                $sql = "DELETE FROM users WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id);
                return $stmt->execute();
            }


        }
    }