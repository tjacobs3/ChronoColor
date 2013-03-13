<?php
    require_once '/simple_html_dom.php';

    function getUrl($url, $method='', $vars='') {
        echo "<b>FETCHING PAGE: </b> {$url} </br>";
        $ch = curl_init();
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";

        // basic curl options for all requests
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($ch, CURLOPT_HEADER,  0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'D:\Program Files\Github\ImageAnalyzer\cookies\cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'D:\Program Files\Github\ImageAnalyzer\cookies\cookies.txt');
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $buffer = curl_exec($ch);
        curl_close($ch);
        return $buffer;
    }
    
    function grab_image($url,$saveto){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'D:\Program Files\Github\ImageAnalyzer\cookies\cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'D:\Program Files\Github\ImageAnalyzer\cookies\cookies.txt');
        $raw=curl_exec($ch);
        curl_close ($ch);
        if(file_exists($saveto)){
            unlink($saveto);
        }
        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
    }
    
    $mysqli = new mysqli("localhost", "root", "", "chronocolor");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
 
    
    function insertArtwork($connection, $name, $medium, $year, $file_loc, $thumb_loc, $location, $dimension) {
        $connection->query("INSERT INTO Image(artist_id, name, medium, year, file_location, thumb_location, location, dimension) VALUES (1, '$name', '$medium', '$year', '$file_loc', '$thumb_loc', '$location', '$dimension')");
    }
    
    $loginUrl = 'https://picasso.shsu.edu/frontlogin.php'; //action from the login form
    $loginFields = array('frontusername'=>'*******', 'frontpassword'=>'*******', 'submit'=>'Log In'); //login form field names and values

    $login = getUrl($loginUrl, 'post', $loginFields); //login to the site
?>
<html>
    <head>
    </head>
    <body>
        <?php 
        for($i = 1959; $i <= 1973; $i++) {
            for($j = 1; $j <= 4; $j++) {
                $remotePageUrl = "https://picasso.shsu.edu/index.php?view=ArtworkDisplay&year={$i}&category=painting&quarter={$j}";

                $remotePage = getUrl($remotePageUrl); //get the remote page
                $html = new simple_html_dom();
                $html->load($remotePage);

                $pageCount = max(1, count($html->find(".PageLinks td")));
                for($k = 1; $k <= $pageCount; $k++) {
                    if($k > 1) {
                        $remotePageUrl = "https://picasso.shsu.edu/index.php?view=ArtworkDisplay&year={$i}&category=painting&quarter={$j}&page={$k}";
                        $remotePage = getUrl($remotePageUrl); //get the remote page
                        $html = new simple_html_dom();
                        $html->load($remotePage);
                    }
                
                    foreach($html->find('.Pic a') as $element) {
                        $title = ""; //
                        $medium = "";
                        $year = "";
                        $file_loc = ""; //
                        $thumb_loc = ""; //
                        $location = "";
                        $dimension = "";
                        
                        echo "THUMB SRC: " .  str_replace("..", "https://picasso.shsu.edu", $element->find('img', 0)->src) . "<br />";
                        $thumbPath  = str_replace("..", "https://picasso.shsu.edu", $element->find('img', 0)->src);
                        $thumb_loc = explode("/", $thumbPath);
                        $thumb_loc = "picasso/thumb/" . $thumb_loc[count($thumb_loc) - 1];
                        grab_image($thumbPath, $thumb_loc);
                        echo "LINK: " . "https://picasso.shsu.edu/" . $element->href . '<br />';

                        $childPage = getUrl("https://picasso.shsu.edu/" . $element->href);
                        $html2 = new simple_html_dom();
                        $html2->load($childPage);
                        
                        echo "IMG SRC: " .  str_replace("..", "https://picasso.shsu.edu", $html2->find("#ImgHolder img", 0)->src) . "<br />";
                        $imgPath = str_replace("..", "https://picasso.shsu.edu", $html2->find("#ImgHolder img", 0)->src);
                        $file_loc = explode("/", $imgPath);
                        $file_loc = "picasso/" . $file_loc[count($file_loc) - 1];
                        grab_image($imgPath, $file_loc);
                        foreach($html2->find('.Container tr') as $element2) {
                            $text = $element2->plaintext;
                            $pieces = explode(":", $text);
                            $pieces[0] = trim($pieces[0]);
                            $pieces[1] = trim($pieces[1]);
                            if(strtolower($pieces[0]) == "title") $title = $pieces[1];
                            if(strtolower($pieces[0]) == "medium") $medium = $pieces[1];
                            if(strtolower($pieces[0]) == "dimension") $dimension = $pieces[1];
                            if(strtolower($pieces[0]) == "location") $location = $pieces[1];
                            if(strtolower($pieces[0]) == "date") {
                                $year = substr(preg_replace("/[^0-9]/", "", $pieces[1]), -4) . "-1-1";
                            }
                            
                            echo $pieces[0] . ": " . trim($pieces[1]) . "<br />";
                        }
                        insertArtwork( $mysqli, $title, $medium, $year, $file_loc, $thumb_loc, $location, $dimension);
                        echo "insertArtwork( connection, {$title}, {$medium}, {$year}, {$file_loc}, {$thumb_loc}, {$location}, {$dimension}) <br />";
                        echo "<br />";
                    }
                    sleep (1.5);
                }
            }
       } 
       
       ?>
    </body>
</html>