<?php

namespace App\Repository\UserRepository;

use PDO;
use App\Entity\ErrorLog\ErrorLog;
use App\Entity\UserEntity\UserEntity;
use App\Repository\AbstractRepository;


class UserRepository extends AbstractRepository
{
  
  /**
   * Method login
   *
   * @param string $username
   * @param string $password
   *
   * @return string
   */
  public function login(string $username, string $password): string
  {
    $user = false; 
 
    if ($this->getUserExist($username) > 0) {
      $user = new UserEntity($this->getUserInformation($username));
    }

    if ($user) {
      if ($this->isCombinaisonValide($user, $password, $confirmPassword=null)) {

        if ($user->getRoles() === 'ROLE_ADMIN') {
          ErrorLog::ajouterMessageAlerte("Bienvenue dans l'administration " . $user->getUserName() . " !", ErrorLog::COULEUR_VERTE);
          $_SESSION['USER'] = $username; 
          $_SESSION['USER_ID'] = $user->getId(); 
          $_SESSION['ROLE'] = ['role' => 'ROLE_ADMIN'];
          if (empty($_SESSION['token'])) {
            if (function_exists('random_bytes')) {
                $_SESSION['token'] = bin2hex(random_bytes(32));
            } else {
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
          return '/switch';

        } elseif ($user->getRoles() === 'ROLE_USER') {
          ErrorLog::ajouterMessageAlerte("Bon retour sur MindRace " . $user->getUserName() . " !", ErrorLog::COULEUR_VERTE);
          $_SESSION['USER'] = $username; 
          $_SESSION['USER_ID'] = $user->getId();
          $_SESSION['ROLE'] = ['role' => 'ROLE_USER'];
          if (empty($_SESSION['token'])) {
            if (function_exists('random_bytes')) {
                $_SESSION['token'] = bin2hex(random_bytes(32));
            } else {
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
          }
          return '/invitation';
        } else {
          ErrorLog::ajouterMessageAlerte("Tu n'as pas l'authorisation nécessaire petite tortue !", ErrorLog::COULEUR_ROUGE);
          return '/';
        }
      } else {
        ErrorLog::ajouterMessageAlerte("Login ou Mot de Passe invalide", ErrorLog::COULEUR_ROUGE);
        return '/login';
      }
    } else {
      ErrorLog::ajouterMessageAlerte("Cette tortue géniale est inexistante, veuillez en créer une !", ErrorLog::COULEUR_ROUGE);
      return '/login';
    } 

  }

  
  /**
   * Method isCombinaisonValide
   *
   * @param $user 
   * @param $password 
   * @param $confirmPassword 
   *
   * @return bool
   */
  public function isCombinaisonValide(mixed $user, string $password, $confirmPassword): bool
  {
    switch ($user->getRoles()) {
      case 'ROLE_ADMIN':
        $passwordBD = $user->getPassword();
        return password_verify($password, $passwordBD);
        break;
      case 'ROLE_USER':
        $passwordBD = $user->getPassword();
        return password_verify($password, $passwordBD) ?: false;
        break;
      case null:
        return ($password === $confirmPassword) ? true: false;
        break;
      default:  ErrorLog::ajouterMessageAlerte("Une erreur est survenue", ErrorLog::COULEUR_ROUGE);
    }
  }

  
  /**
   * Method register
   *
   * @param $user 
   * @param $username
   * @param $password 
   * @param $confirmPassword 
   * @param $mail 
   * @param $firstName
   * @param $lastName
   *
   * @return bool
   */
  public function register($user, $username, $password, $confirmPassword, $mail, $firstName, $lastName) :bool
  { 

      $pdo = self::$pdo;
      $req =
        "INSERT INTO users (username, password, mail, roles, firstName, lastName, createdAt, uniquId)
      VALUES (:username, :password, :mail, :roles, :firstName, :lastName, :createdAt, :uniquId)";
      $stmt = $pdo->prepare($req);
      $stmt->bindValue(":username", $user->setUserName($username), PDO::PARAM_STR);
      $stmt->bindValue(":password", $user->setPassword(password_hash($password, PASSWORD_DEFAULT)), PDO::PARAM_STR);
      $stmt->bindValue(":mail", $user->setMail($mail), PDO::PARAM_STR);
      $stmt->bindValue(":roles", $user->setRoles($user->getRoles()), PDO::PARAM_STR);
      $stmt->bindValue(":firstName", $user->setFirstName($firstName), PDO::PARAM_STR);
      $stmt->bindValue(":lastName", $user->setLastName($lastName), PDO::PARAM_STR);
      $stmt->bindValue(":createdAt", $user->setCreatedAt(date('Y-m-d H:i:s')), PDO::PARAM_STR);
      $stmt->bindValue(":uniquId", $user->setUniquId(random_int(0, 9999)), PDO::PARAM_INT);
      $stmt->execute();
      $isCreated = ($stmt->rowCount() > 0);
      $stmt->closeCursor();
      return $isCreated; 
  }

  
  /**
   * Method validation_register
   *
   * @param string $user
   * @param string $username 
   * @param string $password 
   * @param string $confirmPassword 
   * @param string $mail 
   * @param string $firstName 
   * @param string $lastName 
   *
   * @return string
   */
  public function validation_register($user, $username, $password, $confirmPassword, $mail, $firstName, $lastName) :string
  {
    if ($this->uniqueUsername($username)) {
      if($this->isCombinaisonValide($user, $password, $confirmPassword)) {
        if ($this->register($user, $username, $password, $confirmPassword, $mail, $firstName, $lastName)) {
          ErrorLog::ajouterMessageAlerte("Et voilà une nouvelle tortue géniale !!!", ErrorLog::COULEUR_VERTE);
          $_SESSION['USER'] = $username; $_SESSION['ROLE'] = ['role' => 'ROLE_USER'];
          return '/';
        } else {
          ErrorLog::ajouterMessageAlerte("Zut ! Erreur lors de la création de la tortue", ErrorLog::COULEUR_ROUGE);
          return '/register';
        }
      } else {
        ErrorLog::ajouterMessageAlerte("Les mots de passe ne sont pas identiques", ErrorLog::COULEUR_ORANGE);
        return '/register'; //string
      }
    } else {
      ErrorLog::ajouterMessageAlerte("Hum... Le nom de cette tortue est déjà utilisé.", ErrorLog::COULEUR_ROUGE);
      return '/register';
    }
  }

  
  /**
   * Method uniqueUserName
   *
   * @param string $username
   *
   * @return bool
   */
  public function uniqueUserName($username) :bool
  {
    $datas = $this->getUserInformation($username);
    $result = ($datas === []) ? true: false; 
    return $result;
  }

  
  /**
   * Method getUserExist
   *
   * @param string $username
   *
   * @return int
   */
  public function getUserExist($username) :int
  {
    $pdo = self::$pdo;
    $req = "SELECT COUNT(*) as exist FROM users WHERE username = :username";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $result['exist'];
  }

  
  /**
   * Method getUserInformation
   *
   * @param string $username
   *
   * @return array
   */
  public function getUserInformation($username) :array
  {
    $pdo = self::$pdo;
    $req = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    (!$datas) ? $datas = [] : $datas; 
    return $datas;
  }

  
  
  /**
   * Method findOne
   *
   * @param string $username
   *
   * @return array
   */
  public function findOne(string $username) :array
  {
    $pdo = self::$pdo;
    $req = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result;
  }

  /**
   * Method findOneById
   *
   * @param int $id
   *
   * @return array
   */
  public function findOneById(int $id)
  {
    $pdo = self::$pdo;
    $req = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":id",  $id, PDO::PARAM_INT); 
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result;
  }
  
  /**
   * Method findAll
   *
   * @return array
   */
  public function findAll() :array
  {
    $pdo = self::$pdo;
    $req = "SELECT * FROM users";
    $stmt = $pdo->prepare($req);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); //revoir avec un fetch + boucle
    $stmt->closeCursor();
    return $result;
  }

  
  /**
   * Method VerifAndUpdate
   *
   * @param string $user
   *
   * @return void
   */
  public function VerifAndUpdate(string $user) :void
  {
    $username = $_POST['username'];
    $mail = $_POST['mail'];
    $roles = $_POST['roles'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    
    if (empty($username)){
        $username = $user['username'];
    }
    if (empty($mail)){
        $mail = $user['mail'];
    }
    if (empty($roles)){
        $roles = $user['roles'];
    }
    if (empty($firstname)){
        $firstname = $user['firstName'];
    }
    if (empty($lastname)){
        $lastname = $user['lastName'];
    }
    $this->update($_GET['id'],$username,$mail,$roles,$firstname,$lastname);
    ErrorLog::ajouterMessageAlerte("Vos modifications ont été prises en compte", ErrorLog::COULEUR_ROUGE);
    header("Location: /admin/players");
  }


   
  /**
   * Method update
   *
   * @param int $id
   * @param string $username 
   * @param string $mail 
   * @param string $roles 
   * @param string $firstname
   * @param string $lastname
   *
   * @return array
   */
  public function update($id,$username,$mail,$roles,$firstname,$lastname)
  {
    $pdo = self::$pdo;
    $req = "UPDATE users 
    SET username = :username, mail = :mail, roles = :roles , firstName = :firstname , lastName = :lastname
    WHERE id = $id";
    $stmt = $pdo->prepare($req);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
    $stmt->bindValue(":roles", $roles, PDO::PARAM_STR);
    $stmt->bindValue(":firstname", $firstname, PDO::PARAM_STR);
    $stmt->bindValue(":lastname", $lastname, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    return $result;
  }

  
  /**
   * Method delete
   *
   * @param int $id
   *
   * @return void
   */
  public function delete(int $id) :void
  {
    $pdo = self::$pdo;
    $req = "DELETE FROM users WHERE id = $id";
    $stmt = $pdo->prepare($req);
    $stmt->execute();
    $stmt->closeCursor();
    ErrorLog::ajouterMessageAlerte("L'utilisateur a bien été supprimé", ErrorLog::COULEUR_VERTE);
    header("Location: /admin/players");
    
  }

  
  /**
   * Method userAutoComplete
   *
   * @param string $username
   *
   * @return array
   */
  public function userAutoComplete(string $username) :array
  {
    
    $pdo = self::$pdo;

    $req = "SELECT username 
            FROM users 
            WHERE username = :username 
            LIKE '%username%'
            ORDER BY id DESC
            LIMIT 10
            ";

    $query = $pdo->prepare($req);
    $query->bindValue("username",  $username, PDO::PARAM_STR); 
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    return $result;
  }

    
  /**
   * Method pagination
   *
   * @param int $currentPage
   *
   */
  public function pagination($currentPage){
    $pdo = self::$pdo;
    
    // On détermine le nombre total d'articles
    $sql = 'SELECT COUNT(*) AS nb_users FROM `users`;';
    $query = $pdo->prepare($sql);
    $query->execute();
    $result = $query->fetch();
    
    $nbArticles = (int) $result['nb_users'];
    
    // On détermine le nombre d'articles par page
    $parPage = 12;
    
    // On calcule le nombre de pages total
    $pages = ceil($nbArticles / $parPage);
    
    // Calcul du 1er article de la page
    $premier = ($currentPage * $parPage) - $parPage;
    
    $sql = 'SELECT * FROM `users` LIMIT :premier, :parpage;';
    
    // On prépare la requête
    $query = $pdo->prepare($sql);
    
    $query->bindValue(':premier', $premier, PDO::PARAM_INT);
    $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    $query->execute();
    
    // On récupère les valeurs dans un tableau associatif
    $articles = $query->fetchAll(PDO::FETCH_ASSOC);

    $tab[0] = $articles;
    $tab[1] = $pages;
    
    // return $articles;
    return $tab;
  }
}
