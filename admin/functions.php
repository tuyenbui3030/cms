
<?php
// Hàm sử dụng trong index.php
function recordCount($table)
{
    global $connection;
    $query = "SELECT * FROM $table";
    $select_all_post = mysqli_query($connection, $query);
    $result = mysqli_num_rows($select_all_post);
    comfirmQuery($result);
    return $result;
}
// Hàm sử dụng trong index.php
function checkStatus($table, $column, $status)
{
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status'";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);
    return mysqli_num_rows($result);
}
// Hàm sử dụng trong index.php
function checkUserRole($table, $column, $role)
{
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$role'";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);
    return mysqli_num_rows($result);
}
// Hàm kiểm tra user có phải là admin (dùng cho admin_header.php)
function is_admin($username)
{
    global $connection;
    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);
    $row = mysqli_fetch_array($result);
    if (isset($row['user_role'])) {
        if ($row['user_role'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    return false;
}
// Hàm chuyển trang
function redirect($location)
{
    header("Location:" . $location);
    exit();
}

 function ifItIsMethod($method=null){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){

        return true;

    }

    return false;

}
function isLoggedIn()
{
    if(isset($_SESSION['user_role']))
    {
        return true;
    }
    return false;
}
 function checkIfUserIsLoggedInAndRedirect($redirectLocation=null)
 {
     if(isLoggedIn())
     {
         redirect($redirectLocation);
     }
 }
// Hàm xác định username đã tồn tại (dùng cho registration.php)
function username_exists($username)
{
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
// Hàm xác định email đã tồn tại (dùng cho registration.php)
function email_exists($email)
{
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
//Hàm đăng kí user
function register_user($username, $email, $password)
{
    global $connection;

    $username   = mysqli_real_escape_string($connection, $username);
    $email      = mysqli_real_escape_string($connection, $email);
    $password   = mysqli_real_escape_string($connection, $password);
    $password   = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password, user_images) 
    VALUES ('','','subscriber','{$username}','{$email}','{$password}', '')";
    $register_user_query = mysqli_query($connection, $query);
    comfirmQuery($register_user_query);
}
//Hàm login cho login.php
function login_user($username, $password)
{
    global $connection;
    $username = trim($username);
    $username = trim($username);
    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
    while ($row = mysqli_fetch_array($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
        if (password_verify($password, $db_user_password)) {
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            redirect("/cms/admin");
            //header("Location: ../admin ");
        } else {
            return false;
            //redirect("/cms/index.php");
            //header("Location: ../index.php");
        }
    }
    return true;
}
// bảo mật
function escape($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, trim(strip_tags($string)));
}

function users_online()
{
    if (isset($_GET['onlineusers'])) {
        global $connection;
        if (!$connection) {
            session_start();
            include("../includes/db.php");
            $session = session_id();
            $time = time();
            $time_out_in_seconds = 60;
            $time_out = $time - $time_out_in_seconds;
            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);
            if ($count == NULL) {
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            $user_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
            echo $count_user = mysqli_num_rows($user_online_query);
        }
    }
}
users_online();
function comfirmQuery($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED ." . mysqli_error($connection));
    }
}
function insert_categories()
{
    global $connection;
    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
        if ($cat_title == "" || empty($cat_title)) {
            echo "<h3>This fiels should not be empty</h3>";
        } else {
            $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");
            mysqli_stmt_bind_param($stmt, 's', $cat_title);
            mysqli_stmt_execute($stmt);
            if (!$stmt) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
        mysqli_stmt_close($stmt);
    }
}

function findAllCategories()
{
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}
function deleteCategories()
{
    global $connection;
    if (isset($_GET['delete'])) {
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}
?>