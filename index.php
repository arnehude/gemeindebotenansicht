<?php 
function read_dir(){
    $dirname = './pdf';
    $dirh = scandir($dirname, 1);
    $n = 0;
    foreach ($dirh as $key => $value) {
        if($value != '.' && $value != '..'){
            $elements[$n]['id'] = explode('_', $value, 3)[0];
            $elements[$n]['filename'] = $value;
            $elements[$n]['md5'] = md5($value);
            $elements[$n]['filepath'] = $dirname.'/'.$value;
            $elements[$n]['filesize'] = filesize($dirname.'/'.$value);
            $elements[$n]['filemtime'] = filemtime($dirname.'/'.$value);
            $n++;
        }
    }
    return $elements;
}

function makedropdown(){
    $pdfs = read_dir();
    $output = "";
    foreach ($pdfs as $single) {        
        $output .= "<a class=\"dropdown-item\" role=\"presentation\" href=\"/?gb=".$single['md5']."\">".str_replace("Gemeindebote Nr. ", "",str_replace(".pdf", "", str_replace("_", " ", $single['filename'])))."</a>";
    }
    echo $output;
}

function latest(){
    $pdfs = read_dir();
    $latest['id'] = 0;
    foreach ($pdfs as $pdf) {
        if($latest['id'] < $pdf['id']){
                $latest['id'] = $pdf['id'];
                $latest['md5'] = $pdf['md5'];
        }
    }
    return $latest;
}

function get_gb($gb){
    $pdfs = read_dir();
    foreach ($pdfs as $pdf) {
        if($pdf['md5']==$gb){
            $output = $pdf['filepath'];
        }
    }
    if(isset($output)){
        //echo '<div class="embed-responsive embed-responsive-21by9"><iframe class="embed-responsive-item" src="'.$output.'" allowfullscreen=""></iframe></div>';
        echo '<iframe class="embed-responsive-item" style="height:100%;width:98%; min-height:600px;min-width:98%" src="'.$output.'" allowfullscreen=""></iframe>';
    }
}

?>

<?php 
if(isset($_GET['gb'])&& $_GET['gb'] !== "" || $_GET['gb'] != NULL){
    $gb = $_GET['gb'];
}else{
    $latest = latest();
    $gb = $latest['md5'];
}?>
<!DOCTYPE html>
<html style="width: 100%;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Gemeindeboten der Ev.-Luth. Kirchengemeinde Jade</title>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=72d4b24fc6bd36406c352e129d670b64">
    </head>
<body style="max-width: 98%; margin: 0 auto;">
        <hr />
    <div class="row">
        <div class="col align-self-center">
            <div class="dropdown float-right"><button class="btn btn-secondary active btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Unsere Gemeindeboten</button>
                <div class="dropdown-menu dropdown-menu-right" role="menu"><?php makedropdown()?></div>
            </div>
        </div>
    </div>
    <hr />
    <div class="d-flex flex-row flex-grow-1 flex-shrink-1 justify-content-center visible" style="min-width: 100%;padding: 0;margin: 0;min-height: 600px;">
    <?php get_gb($gb)?>
    </div>       
    <script src="assets/js/jquery.min.js?h=1dd785e1de9a32e236b624ae268bb803"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js?h=2394c9ffd5558f411ffdc3326e9a8962"></script>
</body>
</html>
