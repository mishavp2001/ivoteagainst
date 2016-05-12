<?php
include('../includes/config.php');
$post = $_GET['post'];
$desc = $_GET['desc'];
$tags = $_GET['tags'];

if (!empty($post) && !empty($desc) ) {
    
    $squery="SELECT * from posts where title="."'".$post."'";
    $res = $mysqli->query($squery);
    if ($res && $res->num_rows !== 0) {
        if ($desc ==='undefined'){
            echo "Record is not set.";
        } else {
            $row = $res->fetch_assoc();
            $query="UPDATE posts set description = CONCAT(description,'-', '". $desc."') "."where id=".$row["id"];
            if ($mysqli->query($query) === TRUE) {
                echo "Record updaed successfully";
            } else {
                echo "Record failed to updaed successfully"."  " .$query ;
            }   
        }
        
    } else {
        $query="INSERT INTO posts (title, description, tags, url, votes) VALUES ('" . $post . "','" . $desc . "','" . $tags . "', '/forums/".  $post."', 0)";
        if (isset($post) && isset($desc) && $mysqli->query($query) === TRUE) {
            //create directory and install forum
            $dir = '../forums/'.$post;
    
            $file_to_write = 'index.html';
            $content_to_write = $post."-".$desc;
            
            if( is_dir($dir) === false )
            {
                mkdir($dir);
            }
            
            $file = fopen($dir . '/' . $file_to_write,"w");
            fwrite($file, $content_to_write);
            // closes the file
            echo "New record created successfully";
            fclose($file);
            define('IN_PHPBB', true);
            
            $phpbb_root_path = '../forums';
            $phpEx = substr(strrchr(__FILE__, '.') , 1);
            error_reporting(0);
            include ($phpbb_root_path . 'common.' . $phpEx);
            
            include ($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
            
            $user->session_begin();
            $auth->acl($user->data);
            $user->setup();
            $my_subject = "test";
    $my_text    = "testdata";
    // variables to hold the parameters for submit_post
    $poll = $uid = $bitfield = $options = ''; 
    generate_text_for_storage($my_subject, $uid, $bitfield, $options, false, false, false);
    generate_text_for_storage($my_text, $uid, $bitfield, $options, true, true, true);
    
    $data = array( 
        'forum_id'      => 2,
        'icon_id'       => false,
    
        'enable_bbcode'     => true,
        'enable_smilies'    => true,
        'enable_urls'       => true,
        'enable_sig'        => true,
    
        'message'       => $my_text,
        'message_md5'   => md5($my_text),
    
        'bbcode_bitfield'   => $bitfield,
        'bbcode_uid'        => $uid,
    
        'post_edit_locked'  => 0,
        'topic_title'       => $my_subject,
        'notify_set'        => false,
        'notify'            => false,
        'post_time'         => 0,
        'forum_name'        => '',
        'enable_indexing'   => true,
        );
        
    } else {
        echo "Error: " . $query . "<br>" ;
    }
    }
}    

?>
