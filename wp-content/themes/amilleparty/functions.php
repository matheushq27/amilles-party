<?php
require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/SMTP.php');
require_once('PHPMailer/Exception.php');
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//use Dompdf\Dompdf;

function amille_party_load_scripts()
{
    
    wp_enqueue_style('bootstrap', get_template_directory_uri().'/assets/css/bootstrap.min.css', array(), '1.0', 'all');
    wp_enqueue_style('glider', get_template_directory_uri().'/assets/css/glider.min.css', array('bootstrap'), '1.0', 'all');
    wp_enqueue_style('main', get_template_directory_uri().'/assets/css/main.css', array('bootstrap'), '1.0', 'all');
    wp_enqueue_style('home', get_template_directory_uri().'/assets/css/home.css', array('main'), '1.0', 'all');
    wp_register_style('confirm-presence', get_template_directory_uri().'/assets/css/confirm-presence.css', array('bootstrap', 'main'), '1.0', 'all');
    wp_register_style('feed', get_template_directory_uri().'/assets/css/feed.css', array('bootstrap', 'main'), '1.0', 'all');


    // wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital@0;1&display=swap', array(), null);
    wp_enqueue_script('bootstrap-bundle', get_template_directory_uri().'/assets/js/bootstrap.bundle.min.js', array(), '1.0', true);
    wp_enqueue_script('glider', get_template_directory_uri().'/assets/js/glider.min.js', array('bootstrap-bundle'), '1.0', true);
    wp_enqueue_script('phosphor-icons', get_template_directory_uri().'/assets/js/phosphor-icons.js', array('bootstrap-bundle'), '1.0', true);
    wp_enqueue_script('main', get_template_directory_uri().'/assets/js/main.js', array('bootstrap-bundle', 'glider'), '1.0', true);
    wp_register_script('confirm-presence', get_template_directory_uri().'/assets/js/classes/ConfirmPresence.js', array('bootstrap-bundle', 'jquery', 'js-pdf'), '1.0', true);
    wp_register_script('presence', get_template_directory_uri().'/assets/js/classes/Presence.js', array('bootstrap-bundle', 'jquery'), '1.0', true);
    wp_register_script('js-pdf', get_template_directory_uri().'/assets/js/jspdf.umd.min.js', array('jquery'), '1.0', true);
    wp_register_script('feed', get_template_directory_uri().'/assets/js/classes/Feed.js', array('jquery'), '1.0', true);
}


add_action('wp_enqueue_scripts', 'amille_party_load_scripts');

register_nav_menus(
    array(
        'amille_party_main_menu' => 'Menu Principal'
    )
);

function add_class_li($classes, $item, $args){
    if(isset($args->li_class)){
        $classes[] = $args->li_class;
    }
    if(isset($args->active_class) && in_array('current-menu-item', $classes)){
        $classes[] = $args->active_class;
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'add_class_li', 10, 3);

function add_anchor_class($attr, $item, $args){
    if(isset($args->a_class)){
        $attr['class'] = $args->a_class;
    }
    return $attr;
}

add_action('wp_ajax_modal_feed', 'modal_feed');
add_action('wp_ajax_nopriv_modal_feed', 'modal_feed');

add_action('wp_ajax_layout_feed', 'layout_feed');
add_action('wp_ajax_nopriv_layout_feed', 'layout_feed');

add_filter('nav_menu_link_attributes', 'add_anchor_class', 10, 3);

add_action('wp_ajax_insert_guest', 'insert_guest');
add_action('wp_ajax_nopriv_insert_guest', 'insert_guest');

add_action('wp_ajax_layout_list_guests', 'layout_list_guests');
add_action('wp_ajax_nopriv_layout_list_guests', 'layout_list_guests');

add_action('wp_ajax_delete_list', 'delete_list');
add_action('wp_ajax_nopriv_delete_list', 'delete_list');

add_action('wp_ajax_like_post', 'like_post');
add_action('wp_ajax_nopriv_like_post', 'like_post');

add_action('wp_ajax_register_comment', 'register_comment');
add_action('wp_ajax_nopriv_register_comment', 'register_comment');

add_action('wp_ajax_get_users', 'get_users');
add_action('wp_ajax_nopriv_get_users', 'get_users');

add_action('wp_ajax_send_email', 'send_email');
add_action('wp_ajax_nopriv_send_email', 'send_email');

add_action('wp_ajax_register_user', 'register_user');
add_action('wp_ajax_nopriv_register_user', 'register_user');


function insert_guest(){

    $idList = $_POST['idList'];
    $resp = '';
    $guestMain = [];
    $guestNameEmail = '';
    $arrayCategorys = [];

    foreach($_POST['obj'] as $key => $data){
        if($data['guestType'] == 'guest')
        {
            $guestMain = array(
                'ID' => $idList,
                'post_title'    => wp_strip_all_tags($data['name'].' '.$data['surname']),
                'post_status'   => 'publish',
                'post_type' => 'amille_guest_list',
            );
            $guestNameEmail = $data['name'].' '.$data['surname'];
            $arrayCategorys = [$data['guestType'], $data['category']];
        }
    }
  
    $post_id = wp_insert_post( $guestMain );
    $arrayPostMeta = [];

    if($post_id){
        wp_set_object_terms($post_id, $arrayCategorys, 'category_list');

        foreach($_POST['obj'] as $key => $data){
            if($data['guestType'] != 'guest')
            {
                array_push($arrayPostMeta, $data);
            }
        }

        $resp = update_post_meta($post_id, 'guests', $arrayPostMeta);
    }

    if($resp){
        $msg = "Lista cadastrada com sucesso!";
        if($idList)
        {
            $msg = "Lista atualizada com sucesso!";
        }
        send_email($guestNameEmail);
        die_json_status_code(['msg' =>  $msg, 'idList' => $post_id], 200);
    }else{
        die_json_status_code(['msg' => 'Erro ao cadastrar lista!'], 404);
    }
}

function die_json_status_code(array $array, int $statusCode)
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($statusCode);
    die(json_encode($array));
}

function layout_list_guests(){
    $postArray = [];
    $post = get_post( $_POST['idList']);
    if($post)
    {
        $categoryPost = get_the_terms($post->ID, 'category_list');
        $data = get_post_meta($_POST['idList'], 'guests', true);
    }else{
        die_json_status_code(['msg' => 'Erro ao consultar convidado'], 404);
    }

    $guestType =  '';
    $category =  '';

    if($categoryPost){
        foreach($categoryPost as $cat){
        
            if($cat->slug == 'adult' || $cat->slug == 'adolescent')
            {
                $category = $cat->slug;
            }else{
                $guestType = $cat->slug;
            }
        }
    }    

    $postArray = ['id' => $post->ID, 'name' => $post->post_title, 'surname' => '', 'guestType' => $guestType,'category' => $category];

    array_push($data, $postArray);
    
    return die_json_status_code($data, 200);
}

function delete_list(){
    $delete = wp_delete_post($_POST['idList'], true);
    if($delete){
        die_json_status_code(['msg' =>  'Lista deletada com sucesso!'], 200);
    }else{
        die_json_status_code(['msg' => 'Erro ao deletar lista!'], 404);
    }
}


function like_post(){

    $usersLikes = get_post_meta($_POST['postId'], 'likes_feed', true);

    $idSent = $_POST['userLike']['id'];
    $hasInTheArray = false;
    $newArray = [];

    if(!empty($usersLikes))
    {
        foreach($usersLikes as $user )
        {
            if($user['id'] == $idSent)
            {
                $hasInTheArray = true;
            }
        }
        
        
        if($hasInTheArray)
        {
            if(count($usersLikes) == 1)
            {
                $newArray = '';
            }
            else
            {
                foreach($usersLikes as $user )
                {
                    if($user['id'] != $idSent)
                    {
                        array_push($newArray, $user);
                    }
                }
            }
            
        }
        else
        {
            $newArray = $usersLikes;
            array_push($newArray, $_POST['userLike']);
        }

    }
    else
    {
        array_push($newArray, $_POST['userLike']); 
    }


    $resp = update_post_meta($_POST['postId'], 'likes_feed', $newArray);
    if($resp){
        die_json_status_code(['resp' =>  'success'], 200);
    }else{
        die_json_status_code(['resp' => 'error'], 404);
    }
}

function register_comment()
{
    // $currentUsers = get_post_meta($_POST['postId'], 'feed_comment', true);

    // if(is_array($currentUsers))
    // {
    //     array_push($currentUsers, $_POST['users']);
    // }
    // else
    // {
    //     $currentUsers = [];
    //     array_push($currentUsers, $_POST['users']);
    // }

    // $resp = update_post_meta($_POST['postId'], 'feed_users',  $currentUsers);
    // if($resp){
    //     die_json_status_code(['resp' =>  'success'], 200);
    // }else{
    //     die_json_status_code(['resp' => 'error'], 404);
    // }

    

    $array = array(
        'comment_post_ID' => $_POST['postId'],
        'comment_author' => $_POST['name'],
        'comment_content' => $_POST['comment']
    );

    $resp = wp_insert_comment($array);

    if($resp){
        die_json_status_code(['resp' =>  'success'], 200);
    }else{
        die_json_status_code(['resp' => 'error'], 404);
    }
}

function register_user()
{

    // $array = array(
    //     'comment_post_ID' => 187,
    //     'comment_author' => 'Matheus Henrique',
    //     'comment_content' => 'Um comentário aqui para não ficar vazio'
    // );
    // $resp = wp_insert_comment($array);

    // wp_send_json(array('id' => $resp));


    global $wpdb;
    $table_name = $wpdb->prefix.'users_feed';

    $name = $_POST['name'];

    $wpdb->insert(
        $table_name,
        array(
            'name' => $name
        )
    );

    wp_send_json(array('id' => $wpdb->insert_id));
    
}

// function get_users(){
//     $currentUsers = get_post_meta($_POST['postId'], 'feed_users', true);

//     if($currentUsers){
//         die_json_status_code($currentUsers, 200);
//     }else{
//         die_json_status_code(['resp' => 'error'], 404);
//     }
// }


 

function send_email($guestName){

    $dompdf = new Dompdf();
    $qtdAdult = 0;
    $qtdAdolescent = 0;
    $postArray = [];
    $the_posts = get_posts(array('post_type' => 'amille_guest_list', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1));

    //print_r(json_encode($the_posts));
    //die();
    
    $array = [];

    foreach($the_posts as $post){
        $categoryPost = get_the_terms($post->ID, 'category_list');
        
        if($categoryPost){
        foreach($categoryPost as $cat){
        
            if($cat->slug == 'adult' || $cat->slug == 'adolescent')
            {
                $category = $cat->slug;
            }else{
                $guestType = $cat->slug;
            }
        }
        }
        
        $data = get_post_meta($post->ID, 'guests', true);
        if(!$data){
        $data = [];
        }
        $postArray = ['id' => $post->ID, 'name' => $post->post_title, 'surname' => '', 'guestType' => $guestType,'category' => $category];
        array_unshift($data, $postArray); 
        array_push($array, $data);
    }
    
    $layout = '
    <style type="text/css">
    table { background-color:#white;border-collapse:collapse; }
    table th { background-color:#9932CC;color:white;width:50%; }
    table td, table th { padding:5px;border:1px solid #9932CC; }
    table { margin-bottom: 15px; width: 100%;}
    h1{ text-align: center; color: #9932CC; }
    span{
        margin-right: 20px;
    }
    </style>
    ';

    $layout .= '<h1>Lista de Convidados</h1>';

    foreach($array as $item){

        $layout .= '
        <table>
        <thead>
            <tr>
            <th>Nome</th>
            <th>Idade</th>
            <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
        ';
        foreach($item as $d):
    
            if($d['category'] == 'adult')
            {
                $qtdAdult++;
            }else{
                $qtdAdolescent++;
            }
          
          $name = $d['name'].' '.$d['surname'];
          $category = $d['category'] == 'adult' ? 'Adulto' : 'Adolescente (10 a 17 anos)';
          $guestType = $d['guestType'] == 'guest' ? 'Convidado' : 'Acompanhante';
    
          $layout .= '
          <tr>
            <td>'.$name.'</td>
            <td>'.$category.'</td>
            <td>'.$guestType.'</td>
          </tr>';
        endforeach;
        $layout .= '</tbody></table>';
    }

    $total = $qtdAdolescent + $qtdAdult;
$layout .= "<div>
<span><strong>Total Adultos: </strong>{$qtdAdult}</span>
<span><strong>Total Adolescentes: </strong>{$qtdAdolescent}</span>
<span><strong>Total Convidados: </strong>{$total}</span>
</div>";
$dompdf->loadHtml($layout);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

//header('Content-type: application/pdf');
//echo $dompdf->output();
$pdf = $dompdf->output();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'amilleribeiro3@gmail.com';
        $mail->Password = 'plvltzogebzycihb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
     
        $mail->setFrom('amilleribeiro3@gmail.com', "Lista de Convidados");
        $mail->addAddress('amilleribeiro3@gmail.com');
        $mail->addStringAttachment($pdf, 'lista-de-presenca.pdf');

        $mail->isHTML(true);
        $mail->Subject = "{$guestName} e seus acompanhantes acabam de entrar na sua lista de convidados";
        $mail->Body = 'Lista de Convidados atualizada';
        $mail->AltBody = 'Lista de Convidados atualizada';
        $mail->send();

        // if($mail->send()) {
        //     die_json_status_code(['msg' =>  'Email enviado com sucesso'], 200);
        // } else {
        //     die_json_status_code(['msg' => 'Email nao enviado'], 404);
        // }

    } catch (Exception $e) {
        //die_json_status_code(['msg' => 'Erro'], 404);
    }

}

include 'feed/layout-feed.php';
include 'feed/modal-feed.php';
