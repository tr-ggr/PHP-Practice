<?php


// users JSON
$usersJSON = '../data/users.json';

// posts JSON
$postsJSON = '../data/posts.json';

// comments JSON
$commentsJSON = '../data/comments.json';



// function get users from json
function registerUser() {
        $data = file_get_contents('../data/users.json');
        $id = json_decode($data, true);
        $end = end($id);
        $new_Id = (int)$end["id"] + 1;
        $extra = array(
            'id' => $new_Id,
            'name'               =>     $_POST['name'],
            'username'          =>     $_POST["username"],
            'email'          =>     $_POST["email"],
            'address'     =>     array(
                'street' => $_POST['street'],
                'barangay' => $_POST['barangay'],
                'city' => $_POST['city']
       ));                 
        $tempArray = json_decode($data);
        array_push($tempArray, $extra);
        $jsonData = json_encode($tempArray);
        
        file_put_contents('../data/users.json', $jsonData);
}



function loginUser(){
    $users = getUsersData();

    $loginData = $_POST['username'];

    foreach($users as $user){
        if($user['username'] == $loginData){
            // header("Location: examples\header.php?username=".$loginData);
            echo  '<script> location.replace("index.php?username='.$loginData.'"); </script>';
        }
    }

    if($loginData == null){
        return;
    }


}

function getUsersData() {
    global $usersJSON;
    if (!file_exists($usersJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($usersJSON);
    return json_decode($data, true);
}

// function get posts from json
function getPostsData() {
    global $postsJSON;
    if (!file_exists($postsJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($postsJSON);
    return json_decode($data, true);
}

// function get comments from json
function getCommentsData() {
    global $commentsJSON;
    if (!file_exists($commentsJSON)) {
        echo 1;
        return [];
    }

    $data = file_get_contents($commentsJSON);
    return json_decode($data, true);
}

function getPosts(){

  
    $users = getUsersData();
    
    $posts = getPostsData();
    
    $comments = getCommentsData();

    // print_r($loginData);
    
    $postsarr = array();
    
    foreach($posts as $post){
        foreach($users as $user){
            if($user['id'] == $post['uid']){
                $post['uid'] = $user;
                
                break;
            }
        }
        $post['comments'] = array();
        foreach($comments as $comment){
            if($post['id']==$comment['postId']){
                $post['comments'][] = $comment;
            }
        }
        $postarr[] = $post;
    }
    $str = "";

    $postctr = 0;
    $commentctr = 0;
    foreach($postarr as $parr){
        $postctr++;


        // print_r($parr);

        $str.='<!-- start of post -->
    <div class="row">
        <div class="col-md-12">
            <div class="post-content">

              <div class="post-container"  id = '.$postctr.'>
                <img src="https://ui-avatars.com/api/?rounded=true&name='.$parr['uid']['name'].'" alt="user" class="profile-photo-md pull-left">
                <div class="post-detail">
                  <div class="user-info">
                    <h5><a href="timeline.html" class="profile-link">'. $parr['uid']['name'] .'</a></h5>
                  </div>
                  <div class="reaction">
                    <!--<a class="btn text-green"><i class="fa fa-thumbs-up"></i> 13</a>
                    <a class="btn text-red"><i class="fa fa-thumbs-down"></i> 0</a>-->
                  </div>
                  <div class="line-divider"></div>
                  <div class="post-text">
                    <h3>'.$parr['title'].'</h3>
                    <p>'.$parr['body'].'</p>
                  </div>
                  <div class="line-divider"></div>';
        foreach($parr['comments'] as $comm){

            $commentctr++;
            $str .=  '<div class="post-comment" id = '.$commentctr.'>
            <img src="https://ui-avatars.com/api/?rounded=true&name='.$comm['name'].'" alt="" class="profile-photo-sm">
            <p>'.$comm['body'].'</p>
            </div>';
        }
        
               

                
                  
                 
                  
    $str.='</div>
                  </div>
            </div>

        </div>


    </div>';
        
        
    
 
    }
return $str;
}

?>