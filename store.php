<?php
include "database.php";

class User
{
    protected int $id;
    protected string $email;
    protected string $password;
    protected string $date_of_birth;

    public function get_id()
    {
        return $this->id;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function get_date_of_birth()
    {
        return $this->date_of_birth;
    }

    public function set_email(string $email)
    {
        $this->email = $email;
    }

    public function set_password(string $password)
    {
        $this->password = $password;
    }

    public function set_date_of_birth(string $date_of_birth)
    {
        $this->date_of_birth = $date_of_birth;
    }
}

class Article
{
    protected int $id;
    protected string $title;
    protected string $content;
    protected int $rating;
    protected string $image;
    protected int $user_id;

    // create function 

    public function get_id()
    {
        return $this->id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function get_rating()
    {
        return $this->rating;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    // set function

    public function set_title(string $title)
    {
        if (strlen($title) > 32) {
            $this->title = substr($title, 0, 32);
        } else {
            $this->title = $title;
        }
    }

    public function set_content(string $content)
    {
        $this->content = $content;
    }

    public function set_rating(int $rating)
    {
        if ($rating < 0 || $rating > 5) {
            $this->rating = 0;
        } else {
            $this->rating = $rating;
        }
    }

    public function set_image(?string $image)
    {
        $this->image = $image;
    }
}

class Comment
{
    protected int $id;
    protected string $title;
    protected string $content;
    protected int $rating;
    protected int $article_id;

    // get function

    public function get_id()
    {
        return $this->id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function get_rating()
    {
        return $this->rating;
    }

    public function get_article_id()
    {
        return $this->article_id;
    }

    public function set_title(string $title)
    {
        if (strlen($title) > 32) {
            $this->title = substr($title, 0, 32);
        } else {
            $this->title = $title;
        }
    }

    public function set_content(string $content)
    {
        $this->content = $content;
    }

    public function set_rating(int $rating)
    {
        if ($rating < 0 || $rating > 5) {
            $this->rating = 0;
        } else {
            $this->rating = $rating;
        }
    }
}

// save central article and comments 

class StateInstance
{
    protected array $articles = [];
    protected array $comments = [];

    public function __construct()
    {
        $this->fetch_articles();
        $this->fetch_comments();
    }

    public function get_current_user()
    {
        if ($this->is_logged_in())
            return $this->get_user($_SESSION["userid"]);

        return false;
    }

    public function register(string $email, string $name, string $password, string $date_of_birth)
    {
        global $pdo;
        $sql = "INSERT INTO user (email,name,password,date_of_birth)
                VALUES (:email,:name,:password,:date_of_birth)";

        try {
            $pdo->prepare($sql)->execute([
                "email" => $email,
                "name" => $name,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "date_of_birth" => DateTime::createFromFormat('m/d/Y', $date_of_birth)->format("Y-d-m")
            ]);

            $user = $this->get_user($pdo->lastInsertId());
        } catch (PDOException $e) {
            echo "Datenbankfehler: " . $e->getMessage();
        }

        if (!isset($user) || $user === false) {
            return false;
        }

        $_SESSION["userid"] = $user->get_id();
        return true;
    }

    public  function login(string $name, string $password)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE name = :name");
            $stmt->execute(["name" => $name]);
            $user = $stmt->fetchObject("User");
        } catch (PDOException $e) {
            echo "Datenbankzugriff fehlgeschlagen: " . $e->getMessage();
        }

        if (!isset($user) || $user === false) {
            return false;
        }

        if (password_verify($password, $user->get_password())) {
            $_SESSION["userid"] = $user->get_id();
            return true;
        }

        return false;
    }

    public function logoff()
    {
        if ($this->is_logged_in())
            unset($_SESSION["userid"]);
    }

    public function get_user(int $id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $user = $stmt->fetchObject("User");

        if ($user !== false) {
            return $user;
        }

        return false;
    }

    public function check_username(string $name)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE name = :name");
            $stmt->execute(['name' => $name]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            echo "Datenbankfehler: " . $e->getMessage();
        }

        if (!isset($user) || $user === false) {
            return false;
        }

        return true;
    }

    public function is_logged_in()
    {
        return isset($_SESSION["userid"]);
    }

    public function fetch_comments()
    {
        global $pdo;
        $comments = $pdo->query("SELECT * FROM comments")->fetchAll(PDO::FETCH_CLASS, "Comment");

        if ($comments !== false) {
            $this->comments = $comments;
        }
    }


    public function fetch_articles()
    {
        global $pdo;
        $articles = $pdo->query("SELECT * FROM articles")->fetchAll(PDO::FETCH_CLASS, "Article");

        if ($articles !== false) {
            $this->articles = $articles;
        }
    }

    public function add_article(string $title, string $content, string $image)
    {
        global $pdo;

        if (!$this->is_logged_in()) {
            return false;
        }

        try {
            $sql = "INSERT INTO articles (title, content, rating, image, user_id)
                    VALUES (:title, :content, :rating, :image, :user_id)";

            $pdo->prepare($sql)->execute([
                "title" => $title,
                "content" => $content,
                "rating" => 0,
                "image" => $image,
                "user_id" => $_SESSION["userid"],
            ]);

            $this->fetch_articles();
            $article = $this->get_article($pdo->lastInsertId());
        } catch (PDOException $e) {
            echo "Datenbankfehler: " . $e->getMessage();
        }

        if (!isset($article)) {
            unlink($image);
            return false;
        }

        return true;
    }

    public function get_articles()
    {
        $this->fetch_articles();
        return $this->articles;
    }

    public function get_article(int $id)
    {
        return current(array_filter($this->get_articles(), function ($article) use ($id) {
            return $article->get_id() === $id;
        }));
    }

    public function delete_article($id){
        global $pdo;
        try{
            $sql = "DELETE FROM articles WHERE id=:id";

            $pdo->prepare($sql)->execute([
                "id" => $id
            ]);

            $this->fetch_comments();
        } catch (PDOException $e) {
            echo "Datenbankfehler: " . $e->getMessage();
        }
    }

    public function add_comment(string $title, string $content, int $rating, int $article_id)
    {
        global $pdo;
        try {
            $sql = "INSERT INTO comments (title, content, rating, article_id)
                    VALUES (:title, :content, :rating, :article_id)";

            $pdo->prepare($sql)->execute([
                "title" => $title,
                "content" => $content,
                "rating" => $rating,
                "article_id" => $article_id,
            ]);

            $this->fetch_comments();
            $comment = $this->get_comment($pdo->lastInsertId());
        } catch (PDOException $e) {
            echo "Datenbankfehler: " . $e->getMessage();
        }

        if (!isset($comment)) {
            return false;
        }

        return true;
    }

    public function get_comments_by_article_id(int $article_id)
    {
        return array_filter($this->comments, function ($comment) use ($article_id) {
            return $comment->get_article_id() === $article_id;
        });
    }

    public function get_comments()
    {
        $this->fetch_comments();
        return $this->comments;
    }

    public function get_comment(int $id)
    {
        return current(array_filter($this->comments, function ($comment) use ($id) {
            return $comment->get_id() === $id;
        }));
    }

    public function set_articles(array $articles)
    {
        $this->articles = $articles;
    }

    public function set_comments(array $comments)
    {
        $this->comments = $comments;
    }
}

class State
{
    private static ?StateInstance $instance = null;

    public static function get_instance(): StateInstance
    {
        if (static::$instance === null) {
            static::$instance = new StateInstance();
        }

        return static::$instance;
    }

    private function __construct()
    {
    }
}

if (!session_id()) session_start();

if (!isset($_SESSION["state"])) {
    $state = State::get_instance();
    $_SESSION["state"] = serialize($state);
}

$state = unserialize($_SESSION["state"]);
