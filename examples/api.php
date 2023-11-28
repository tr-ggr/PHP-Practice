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

function getLoginData(){
    $loggedin = $_GET["username"];
    $data = file_get_contents('../data/users.json');
    $decode = json_decode($data, true);

foreach ($decode as $item) {
if ($item["username"] == $loggedin) {
     return $item;
    break;
}
}

return [];
}


function loginUser(){
    $loginData = getLoginData();

    if($loginData == null){
        echo '<script>alert(1)</script>';
        return;
    }

    $str = '    
    <div class = "forms" style="padding: 2rem;display: flex;flex-direction: column;gap: 1rem;"> Hello '.
        $loginData["username"].'
        <form method = "post">

            <div class="input-group input-group-lg">
            <span class="input-group-text" id="inputGroup-sizing-lg">Title</span>
            <input type="text" class="form-control" id="title" name="title" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea1">Message:</label>
                <textarea class="form-control" id="body" name="body" rows="3"></textarea>
            </div> <br>

            <input type="submit" id="post" name="post" class="btn btn-primary btn-lg btn-block">

        </form>

    </div>';

    return $str;
}

function addPosts(){
    global $postsJSON;

    $loginData = getLoginData();

    $decode = file_get_contents($postsJSON);

    $decode = json_decode($decode, true);

    $end = end($decode);

    $new_Id = (int)$end["id"] + 1;

    $arr = array(
        "uid" => $loginData["id"],
        "id" => $new_Id,
        "title" => $_POST["title"],
        "body" => $_POST["body"]
    );

    array_push($decode, $arr);

    $jsonData = json_encode($decode);
        
    file_put_contents($postsJSON, $jsonData);

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

function postComments() {
    global $commentsJSON;
    $loginData = getLoginData();
    $data = file_get_contents($commentsJSON);
    $id = json_decode($data, true);
    $end = end($id);
    $new_Id = (int)$end["id"] + 1;
    $extra = array(
        "postId" => (int)$_POST['post_id'],
        "id" => $new_Id,
        "name" => $loginData['name'],
        "email" => $loginData['email'],
        "body" => $_POST['body']
    );                 

    $tempArray = json_decode($data);
    array_push($tempArray, $extra);
    $jsonData = json_encode($tempArray);
    
    file_put_contents($commentsJSON, $jsonData);
}


function getPosts(){

    $loginData = getLoginData();
    
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

        if($parr['uid']['id'] == $loginData["id"]){
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
                          <form method ="post">
                            <input type="hidden" name="post_id" value='.$postctr.'>
                            <input type="submit" id="delete" value="delete" name="delete" class="btn btn-danger btn-lg btn-block">
                          </form>
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

                    print_r($comm);

                    $str .=  '<div class="post-comment"  id = '.$commentctr.'>
                            <img src="https://ui-avatars.com/api/?rounded=true&name='.$comm['name'].'" alt="" class="profile-photo-sm">
                            <p>'.$comm['body'].'</p>
                            <form method ="post">
                                <input type="submit" id="delete" value="delete" name="delete" class="btn btn-danger btn-lg btn-block">
                            </form>
                          </div>';
                }
                        
                         
                          
            $str.='</div>
            <form method = "post">
            <div class="bg-light p-2">
            <input type = "hidden" name="post_id" value='.$postctr.'>
            <textarea class="form-control ml-1 shadow-none textarea" name="body"></textarea></div>
            <div class="mt-2 text-right"><input class="btn btn-primary btn-sm shadow-none" name = "comment" type="submit" value="Post Comment"></div>
        </form>
                      </div>
                    </div>
                </div>
            </div>';
        } else {
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
    <form method = "post">
    <div class="bg-light p-2">
    <input type = "hidden" name="post_id" value='.$postctr.'>
    <textarea class="form-control ml-1 shadow-none textarea" name="body"></textarea></div>
    <div class="mt-2 text-right"><input class="btn btn-primary btn-sm shadow-none" name = "comment" type="submit" value="Post Comment"></div>
</form>
              </div>
            </div>

        </div>


    </div>';
        }
        
    
 
    }
return $str;
}

?>