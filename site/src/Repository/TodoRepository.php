<?php


namespace Amir_nadjib\Todo_list\Repository;
use PDO;

class TodoRepository implements TodoInterfaceRepository
{

    private PDO $pdo ;

    /**
     * TodoRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getUserByEmail (string $authEmail):array
    {
        $stmt = $this->pdo->prepare('select * from users where email = :mail ');
        $stmt->bindParam(':mail', $authEmail, PDO::PARAM_STR);
        $stmt->execute() ;
        if ($stmt->rowCount() == 1)
        {
            return $stmt->fetch() ;
        }
        return array() ;
    }

    public function getAllTasks ():array
    {
        return $this->pdo->query('select * from todos ')->fetchAll();
    }

    public function getAllUsers():array
    {
        return $this->pdo->query('select * from users')->fetchAll();
    }

    public function insertTask(string $currUser,string $userId, string $title, string $description,string $dueDate):int
    {
        $currDate = date("Y-m-d H:i:s");
        $this->pdo->query("INSERT INTO todos (id, created_by, assigned_to, title, description, created_at, due_date)
		VALUES(null,'$currUser','$userId','$title','$description','$currDate','$dueDate');") ;

        return $this->pdo->lastInsertId() ;
    }
    public function insertComment(string $taskId, string $createdBy,string $comment):string {
        $currDate = date("Y-m-d H:i:s");
        $this->pdo->query("insert into comments (id, task_id, created_by, created_at, comment) VALUES 
                (null,'$taskId','$createdBy','$currDate','$comment')") ;

        return  $comment ;
    }
    public function getComments (string $taskId):array
    {

        $val = intval($taskId);
        $comments = $this->pdo->prepare('select comment , email , created_at from comments inner join users  on users.id = comments.created_by  where task_id = :tid ');
        $comments->bindParam(':tid', $val, PDO::PARAM_INT);
        $comments->execute() ;
        return $comments->fetchAll() ;

    }

    public function getTaskById (string $tid):array
    {
        $id = intval($tid) ;
        $task = $this->pdo->prepare('select * from todos inner join users
		on users.id = todos.created_by where todos.id = :tid');
        $task->bindParam(':tid', $id, PDO::PARAM_INT);
        $task->execute() ;
        return  $task->fetch() ;
    }

    public function getUserById (string $uid):array
    {
        $val = intval($uid);
        $users = $this->pdo->prepare('select * from users where id = :uid ');
        $users->bindParam(':uid', $val, PDO::PARAM_INT);
        $users->execute() ;
        return $users->fetch() ;
    }
    public function getUserAssigned (string $uid):array{
      $id = intval($uid) ;
      $task = $this->pdo->prepare('select email from todos inner join users
		on users.id = todos.assigned_to where todos.id = :tid');
      $task->bindParam(':tid', $id, PDO::PARAM_INT);
      $task->execute() ;
        return $task->fetch();
  }
    public function filterByTask (string $hint):array{

        $tasks = $this->pdo->prepare('select id , title from todos where title like :hint');
        $tasks->bindParam(':hint', $hint, PDO::PARAM_STR);
        $tasks->execute();
        return $tasks->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filterByUser (string $hint):array{

        $tasks = $this->pdo->prepare('select todos.id , title from users inner join todos
                on users.id = todos.created_by where email like :hint');
        $tasks->bindParam(':hint', $hint, PDO::PARAM_STR);
        $tasks->execute();
        return $tasks->fetchAll(PDO::FETCH_ASSOC);
    }


}
