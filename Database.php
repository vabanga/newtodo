<?php

session_start();
$_SESSION['user_id'] = 1;

class Database{
    public $isConn;
    protected $datab;

    public function __construct($username = "root", $password = "", $host = "localhost", $dbname = "todo", $options = []){
        $this->isConn = TRUE;
        try {
            $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
            $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }


    public function Disconnect(){
        $this->datab = NULL;
        $this->isConn = FALSE;
    }


    public function getItems()
    {
        try {
            $itemsQuery = $this->datab->prepare("
            SELECT id, name, done
            FROM items
            WHERE user = :user
            ");
            $itemsQuery->execute([
                'user' => $_SESSION['user_id']
            ]);
            $item = $itemsQuery->rowCount() ? $itemsQuery : [];

            return $item;

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addItem(){
        if(isset($_POST['name'])) {
            $name = trim($_POST['name']);

            if(!empty($name)) {
                try {
                    $addedQuery = $this->datab->prepare("
                        INSERT INTO items (name, user, done, created)
                        VALUES (:name, :user, 0, NOW())
                    ");

                    $addedQuery->execute([
                        'name' => $name,
                        'user' => $_SESSION['user_id']
                    ]);

                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }
            }
        }

        header('Location: index.php');
    }



    public function markItem($item){


        $doneQuery = $this->datab->prepare("
            UPDATE items
            SET done = 1
            WHERE id = :item
            AND user = :user
        ");

        $doneQuery->execute([
            'item' => $item,
            'user' => $_SESSION['user_id']
        ]);




        header('Location: index.php');
    }

    public function delItem($item){


        $doneQuery = $this->datab->prepare("
        DELETE FROM items
        WHERE id =:item
        AND user = :user
        ");

        $doneQuery->execute([
            'item' => $item,
            'user' => $_SESSION['user_id']
        ]);

        header('Location: index.php');
    }
}



if(!isset($_SESSION['user_id'])){
    die('Вы не вошли в аккаунт.');
}

?>