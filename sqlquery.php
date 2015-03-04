<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <script src="frontend.js"></script>
    </head>
    <body>
    <?php
//      Get search term from html page
    $query1= strip_tags(stripslashes(($_POST['query1'])));
    $query2= strip_tags(stripslashes(($_POST['query2'])));
    $id1= getId($query1);
    $id2= getId($query2);
    echo "$query1 has an id of $id1 <br/> $query2 has an id of $id2 <br/>";

//      Take away spaces because those make the code break
    for($i=0; $i<strlen($query1); $i++) {
        $query1 = str_replace(" ", "_",$query1);
    }
    for($i=0; $i<strlen($query2); $i++) {
        $query2 = str_replace(" ", "_",$query2);
    }
//////////////////////////////////////////////////////////////////////////////////////////////////
//      Run make_query function on both of the queries and store the result in an array
    $array1 = make_query($query1);
    $array2 = make_query($query2);
//////////////////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['btn_submit']=="Plain Text")
{
//      find the intersection of the arrays (this lets you see which links are in common)
//      this array is filled with page id's
    $array3 = array_intersect($array1, $array2);
    for ($counter=0; $counter<5; $counter++){
        do{
            $ranval = array_rand($array3);
            $val = $array3[$ranval];
            echo "&nbsp $val &nbsp";
            $status= getStatus($val);
        } while($status != 200);
        echo "<br/>";       
        $page = getPlainText($val);
        print($page);    
        echo'<div style="background-color:#000;"><br/></div>';
        echo'<div style="background-color:#000;"><br/></div>';
    }
}
else if ($_REQUEST['btn_submit']=="Explain")
{
    echo "<br/>";
//      find the intersection of the arrays (this lets you see which links are in common)
//      this array is filled with page id's

    $dot = ". ";
    $post = array();

    for($i=0; $i<strlen($query1); $i++) {
        $query1 = str_replace("_", " ",$query1);
        }

    for($i=0; $i<strlen($query2); $i++) {
        $query2 = str_replace("_", " ",$query2);
        }

    $array3 = array_intersect($array1, $array2);
    do{
        do{
            $ranval = array_rand($array3);
            $val = $array3[$ranval];
            echo "$val &nbsp";
            $status= getStatus($val);
        } while($status != 200);   
        echo "<br/>";
        $page = getPlainText($val);
    
        $post[0]= stripos($page,$query1);
        $post[1]= stripos($page,$query2);
        $post[2]= strripos($page,$query1);
        $post[3]= strripos($page,$query2);

    } while(($post[0]===false) || ($post[1]===false));
        
    $val1= max($post);
    $val2= min($post);

    $firsthalf=substr($page,0,$val2);
    $secondhalf=substr($page,$val1);


    $firstperiod=strripos($firsthalf,$dot);
    $secondperiod=stripos($secondhalf,$dot);
    $diff= $secondperiod+$val1-$firstperiod +1;
    $firstperiod++;

    $substring= substr($page, $firstperiod, $diff);

    print($substring);
    echo "<br/> <br/>";
    $page1 = getPlainText($id1);
    

    /*do {
        $occurence= stripos($page1, $query2);
        echo "$occurence <br/>";
        $occ1=strripos($page1, $query2);
        $occ2=stripos($page1, $query2);
        $occurence += $occurence;
        $page1= substr($page1, $occurence);
    } while ($occ1!==$occ2);*/
    echo "<br/> <br/>";
    //print($page1);
    echo "<br/> <br/>";
    $cursorposition=0;
    $occurence= stripos($page1,$query2);
    $occ1=strripos($page1, $query2);
    
    do{
        echo "$occurence<br/><br/>";
        
        $firsthalf= substr($page1,$cursorposition,$occurence);
        $secondhalf= substr($page1,$occurence);

        if ($cursorposition===0){
            $firstperiod=0;
        }
        $firstperiod=strripos($firsthalf,$dot);
        
        if ($cursorposition !==0){
            $firstperiod++;    
        }
        
        $secondperiod= stripos($secondhalf,$dot);
        $secondperiod= $secondperiod+ $occurence;
        $diff= $secondperiod-$firstperiod;

        $cursorposition+= $secondperiod;
        $occurence= stripos($page1, $query2, $cursorposition);
        $explanation1 = substr($page1, $firstperiod, $diff);
        
        print($explanation1);
        echo "<br/> <br/>";
    } while ($occurence !== $occ1); 
    

    /* $initpost= 0;
    do{ //if there's a dot in our soruce text do
        $position = stripos ($page, $dot); //find first dot position
        $offset = $position + 1; //prepare offset
        $position2 = stripos ($page, $dot, $offset); //find second dot using offset
        $twolines = substr($page, $initpost, $position2); //put two first sentences under $first_two
        $initpost= $offset++;
        $cond1= stripos($twolines,$query1);
        $cond2= stripos($twolines,$query2);
        echo $twolines . '.'."<br/"; //add a dot
    }while(($cond1===false)&&($cond2===false)); */

}
else if ($_REQUEST['btn_submit']=="Test"){
    $dot=". ";
    $query2= "Gold";
    $page1 = getPlainText($id1);
    echo "<br/> <br/>";
    echo $page1;

    $cursorposition=0;
    $occurence= stripos($page1,$query2);
    $occ1=strripos($page1, $query2);
    
    for($i=0;$i<3;$i++){
    echo "$occurence<br/><br/>";

    $firsthalf= substr($page1,$cursorposition,$occurence);
    $secondhalf= substr($page1,$occurence);

    if ($cursorposition===0){
        $firstperiod=0;
    }
    $firstperiod=strripos($firsthalf,$dot);

    if ($cursorposition !==0){
        $firstperiod++;    
    }

    $secondperiod= stripos($secondhalf,$dot);
    $secondperiod= $secondperiod+ $occurence;
    $diff= $secondperiod-$firstperiod;

    $cursorposition+= $secondperiod;
    $occurence= stripos($page1, $query2, $cursorposition);
    $explanation1 = substr($page1, $firstperiod, $diff);

    print($explanation1);
    echo "<br/> <br/>";

    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
//      Functions       //
///////////////////////////////////////////////////////////////////////////////////////////////////////   
///////////////////////////////////////////////////////////////////////////////////////////////////////
//      gets art_id of query
    function getId($query) {
//          open connection to mysql database
        $link = new mysqli("downey-n2.cs.northwestern.edu", "wikification", "Wikific@tion", "wikapidia0p3");
//          if it can't connet, let user know
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }
//          this query uses the wiki page title and returns the page id
//          the noncommented lines are there in order to make the query properly run
        $p_query="select art_id from en_article  where art_title= '" . $query . "'";
        $result = mysqli_query($link, $p_query);
        if (!$result) {
            echo "Could not successfully run query ($p_query) from DB: " . mysqli_error($link);
            exit;
        }
//          store page id as $cvalue
        while($row = mysqli_fetch_assoc($result)) {
            foreach($row as $cname => $cvalue) {
            }
        }
//          returns art_id
        return $cvalue;
//          closes link to database
        mysqli_close($link);
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////   
///////////////////////////////////////////////////////////////////////////////////////////////////////
    function getLinks($id) {
//          open connection to mysql database
        $link = new mysqli("downey-n2.cs.northwestern.edu", "wikification", "Wikific@tion", "wikapidia0p3");
//          if it can't connet, let user know
        if (!$link) {
            die('Could not connect: ' . mysql_error());
        }

//          this query uses the page id and gets back all fo the inlinks to the searched page
//          the noncommented lines are there in order to make the query properly run     
        $p_query="select from_id from en_article_link where to_id= " . $id;
        $result = mysqli_query($link, $p_query);
        if (!$result) {
            echo "Could not successfully run query ($p_query) from DB: " . mysqli_error($link);
            exit;
        }
//          initialize counter and array which will store the inlinks
        $icount = 0;
        $array = array(1);
        while($row = mysqli_fetch_assoc($result)) {
            foreach($row as $cname => $id) {
//                  store an inlink in the array at location $icount
                $array[$icount] = $id;
                $icount++;
            }
        }
//          return the array just calculated
        return $array;
//          close link to database
        mysqli_close($link);        
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////   
///////////////////////////////////////////////////////////////////////////////////////////////////////
//      this function takes in a search term and returns all of the wiki pages that link to that search term
    function make_query($query) {
        $cvalue= getId($query);
        $result= getLinks($cvalue);
        return $result;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////   
///////////////////////////////////////////////////////////////////////////////////////////////////////
//      black box function
    function get_request($request, &$error = null) {
        $session = curl_init();
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_URL, $request);
        curl_setopt($session, CURLOPT_USERAGENT, 'Wikifier UI');
        curl_setopt($session, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($session, CURLOPT_TIMEOUT, 60);
      //curl_setopt($session, CURLOPT_COOKIEFILE, 'cookies.tmp');
      //curl_setopt($session, CURLOPT_COOKIEJAR, 'cookies.tmp');


        $response = curl_exec($session);
        curl_close($session);

        // Get HTTP Status code from the response
        $status_code = array();
            preg_match('/\d\d\d/', $response, $status_code);
            switch( $status_code[0] ) {
                case 200:
                $error = null;
                break;
                case 503:
                $error = 'Service unavailable. An internal problem prevented us from returning data to you.';
                break;
                case 403:
                $error = 'Forbidden. You do not have permission to access this resource, or are over your rate limit.';
                break;
                case 400:
                $error = 'Bad request. The parameters passed to the service did not match as expected.';
                break;
                default:
                $error = 'Unexpected HTTP status of:' . $status_code[0];
            }
        return strstr($response, '{"');
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////    
//      takes in a page id and returns the json object
    function get_page($id, &$success = null, &$error = null) {
        $request = 'http://websail-fe.cs.northwestern.edu:8080/wikifier/resource/article/'.$id;
        $response = get_request($request, $error);
        if($error != null) {
            $success = false;
            return null;
        }
        $response = json_decode($response);
        if ($response->status != 200) {
            $error = $response->message;
            $success = false;
            return null;
        }
        $success = true;
        return $response->response;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////    
//      Returns the plainText from the JSON Object
    function getPlainText($id) {
        $json_response = get_page($id);
            $pt = $json_response->plainText;
            return $pt;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////    
//      Returns apart of the JSON Object to find the status of the JSON Object

    function getStatus($id, &$success = null, &$error = null) {
        $request = 'http://websail-fe.cs.northwestern.edu:8080/wikifier/resource/article/'.$id;
        $response = get_request($request, $error);
        if($error != null) {
            $success = false;
            return null;
        }
        $response = json_decode($response);
        $success = true;
        return $response->status;
    }
    ?> 
    </body>
</html>



