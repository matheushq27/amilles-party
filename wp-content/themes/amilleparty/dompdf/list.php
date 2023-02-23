<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php

$postArray = [];
    $post = get_post(7);
    if($post)
    {
        $categoryPost = get_the_terms($post->ID, 'category_list');
        $data = get_post_meta(7, 'guests', true);
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

    echo '<h1>Lista de Presença</h1>';

    $arrayGuest = [];

    foreach($data as $d){
      if($d['guestType'] == 'guest'){
        array_push($arrayGuest, $d);
      }
    }

    //var_dump($arrayGuest);

    // foreach($data as $d){
    //   echo $d['name'].'<br>';
    //   echo $d['surname'].'<br>';
    //   echo $d['guestType'].'<br>';
    //   echo $d['category'].'<br>';
    //   echo '------------------------------';
    //   echo '<br>';
    // }

?>

<table>
  <thead>
  <tr>
    <th>Nome</th>
    <th>Idade</th>
    <th>Tipo</th>
  </tr>
  </thead>
  
  <?php  foreach($data as $d): ?>
  <tr>
    <td><?= $d['name'].' '.$d['surname'] ?></td>
    <td><?= $d['category'] == 'adult' ? 'Adulto' : 'Adolescente (10 a 17 anos)' ?></td>
    <td><?= $d['guestType'] == 'guest' ? 'Convidado' : 'Acompanhante' ?></td>
  </tr>
  <tr>
  <?php  endforeach; ?>
 
</table>
    
</body>
</html>