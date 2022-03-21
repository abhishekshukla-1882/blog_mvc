<?php
namespace App\Controllers;

use App\Libraries\Controller;

class Pages extends Controller
{
    public function __construct()
    {
        //empty func
    }

    public function index()
    {
        
        $users = $this->model('Posts')::find('all');
        $data =[
            'users' => $users
        ];
        $this->view('pages/home',$data);

    }
    public function blog()
    {
        // echo "blog p";
        $con = $this->model('Users')::find('all');
        $data = [
            'user' => $con
        ];
        $this->view('pages/bloging',$data);
    }
    public function check(){
        if(isset($_POST['submit'])){
            $postdata = $_POST ?? array();
            // echo "<pre>";
            // print_r($postdata);
            // echo "</pre>";
            $email=$postdata['email'];
            $password = $postdata['password'];

            // $users = $this->model('Users');
            $data = $this->model('Users')::find_by_username($email);
            if($data->username == $email && $data->password == $password){
                if(!isset($_SESSION['log'])){
                    $_SESSION['log'] = array();
                    $user_detail = array(
                        'username'=> $data->username,
                        'id'=> $data->user_id,
                        'user_is'=>$data->user_is
                    );
                    $_SESSION['log'] = $user_detail;
                    // array_push($_SESSION['log'],$data->username);
                    // array_push($_SESSION['log'],$data->user_id);
                    print_r($_SESSION['log']);

                }
                else if($_SESSION['log']){
                    // array_push($_SESSION['log'],$data->username);
                    // array_push($_SESSION['log'],$data->user_id);
                    // print_r($_SESSION['log']);
                    $user_detail = array(
                        'username'=> $data->username,
                        'id'=> $data->user_id
                    );
                    $_SESSION['log'] = $user_detail;
                    // array_push($_SESSION['log'],$data->username);
                }
                // header("Location: /views/login/profile");
                $this->view('pages/login/profile',$data);
            }else{
                $this->view('pages/login/header');
                $this->view('pages/login/main');
                $this->view('pages/login/footer');

            }
           
    }
}
    public function login()
    {
        // $postdata = $_POST ?? array();
        // // echo "<pre>";
        // print_r($postdata['email']);
        // echo "<pre>";

        // $user = $this->model('Users')::find_by_username('admin');

        // if ($user->user_id) {
        //     $_SESSION['userdata'] = array(
        //         'user_id'=>$user->user_id,
        //         'username'=>$user->username,
        //         'email'=>$user->email);
            
        //     header("Location: /public/admin/dashboard");
        // }

        $this->view('pages/login/header');
        $this->view('pages/login/main');
        $this->view('pages/login/footer');
    }

    public function register()
    {
        $postdata = $_POST ?? array();
        
        if(isset($postdata['username']) && isset($postdata['password'])){
            //print_r($postdata);
            $users = $this->model('Users');
            $users->username = $postdata['username'];
            $users->password = $postdata['password'];
            // echo "<pre>";
            // print_r($users);
            // echo "</pre>";
            $users->save();
        }
        $data['users'] =  $this->model('Register')::all(); 
        // print_r($data);
        $this->view('pages/registration');
    }
    public function post()
    {
        $postdata = $_POST ?? array();
        // echo "<pre>";
        // print_r($_SESSION['log']);
        // echo "</pre>";
        // $namee = $_SESSION['log'][]
        $user_idd = $_SESSION['log']['id'];
        echo $user_idd;


        if(isset($postdata['title']) && isset($postdata['Content'])){
            // print_r($postdata);
            echo 'hi'. $postdata['title'];
            $posts = $this->model('Posts');
            $posts->title = $postdata['title'];
            $posts->user_id = $user_idd;
            $posts->content = $postdata['Content'];
            $posts->save();

        }
        $data['posts'] =  $this->model('Posts')::all();
         
        $this->view('pages/post');

    }
    public function dashboard(){
        
        $users = $this->model('Posts')::find('all');
        $data =[
            'users' => $users
        ];
        // print_r($users);
        $this->view('pages/dashboard',$data);
        
    }
    public function auth(){
        // echo "yha";
        // if(isset($_POST['submit'])){
            // echo "aya";
        $v = $_POST['submit'];
        $id = $_POST['idd'];
        // echo $v."".$id;
        $user = $this->model('Posts')::find_by_post_id($id);
        $user->status = $v;
        $user->save();
        $users = $this->model('Posts')::find('all');
        $data =[
            'users' => $users
        ];
        $this->view('pages/dashboard',$data);
    }
    public function edit_post(){
        if(isset($_SESSION['log'])){
            $id = $_SESSION['log']['id'];
            $data = $this->model('Posts')::find_all_by_user_id($id);
            // echo "<pre>";
            // print_r($user);
            // echo "</pre>";
            $this->view('pages/your_post',$data);
        }
        
    }
    public function edit(){
        $postdata = $_POST ?? array();
        // echo "<pre>";
        // print_r($postdata);
        // echo "</pre>";

        if(isset($_POST['edit'])){
            $val = $_POST['edit'];
            $val2 = $_POST['idd'];
            $data = $this->model('Posts')::find_by_post_id($val2);

            // echo $val2;
            // echo $val;
            $this->view('pages/edit',$data);
        }
        if(isset($_POST['delete'])){
            // echo "delete";
            // $val = $_POST['edit'];
            $val2 = $_POST['idd'];
            $data = $this->model('Posts')::find_by_post_id($val2);
            $data->delete();
            $this->view('pages/edit',$data);


        }
        // $data = $this->model('Posts')::find_by_post_id($postdata['idd']);
        // $data->title = $postdata['title'];
        // $data->content = $postdata['content'];
 
    }
    public function update(){
        $postdata = $_POST ?? array();
        // echo "<pre>";
        // print_r($postdata);
        // echo "</pre>";
        // if($_POST['submit']){
        $title = $_POST['title'];
        $content = $_POST['Content'];
        $id = $_POST['idd'];
        $data =$this->model('Posts')::find_by_post_id($id);
        $data->title = $title;
        $data->content = $content;
        $data->save();
        $this->view('pages/edit',$data);

        // }
    }
}
