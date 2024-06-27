<?php
ini_set('session.cookie_samesite','None');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");
session_start();
if (isset($_SESSION["subject"])) {
    if ($_SESSION["subject"] == "admin") {
        if (isset($_GET["meetingId"])) {
            $api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmFwcGVhci5pbiIsImF1ZCI6Imh0dHBzOi8vYXBpLmFwcGVhci5pbi92MSIsImV4cCI6OTAwNzE5OTI1NDc0MDk5MSwiaWF0IjoxNzE5MzEyNTk5LCJvcmdhbml6YXRpb25JZCI6MjQ0MDc2LCJqdGkiOiI0NWIxMmE3Yy1hYTBlLTRkMDAtYTE2ZC1mYTM3NGEzZDU1ODgifQ.-HW0uLTWzuTQ8w-6ALBu7SFQhrxxFJAkeR9dPz5KH2g";
            $url = 'https://api.whereby.dev/v1/meetings/' . urlencode($_GET["meetingId"]);
            $options = array(
                'http' => array(
                    'header'  => array(
                        "Authorization: Bearer ". $api_key,
                    ),
                    'method' => 'DELETE',
                ),
            );
            $context  = stream_context_create($options);
            echo file_get_contents($url, false, $context);                        
        } else if (isset($_POST["duration"]) && isset($_POST["meetingName"])) {
            $api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmFwcGVhci5pbiIsImF1ZCI6Imh0dHBzOi8vYXBpLmFwcGVhci5pbi92MSIsImV4cCI6OTAwNzE5OTI1NDc0MDk5MSwiaWF0IjoxNzE5MzEyNTk5LCJvcmdhbml6YXRpb25JZCI6MjQ0MDc2LCJqdGkiOiI0NWIxMmE3Yy1hYTBlLTRkMDAtYTE2ZC1mYTM3NGEzZDU1ODgifQ.-HW0uLTWzuTQ8w-6ALBu7SFQhrxxFJAkeR9dPz5KH2g";
            $url = 'https://api.whereby.dev/v1/meetings';
            $data = array(
                'endDate' => gmdate("Y-m-d\TH:i:s.v\Z", strtotime(gmdate("Y-m-d H:i:s") . " +" . $_POST["duration"] . " minutes")),
                "roomMode" => "group",
                "roomNamePattern" => "human-short",
                "roomNamePrefix" => preg_replace("/[ _]/", "-", strtolower($_POST["meetingName"])),
                "templateType" => "viewerMode",
                "fields" => ["hostRoomUrl"]
            );
            $options = array(
                'http' => array(
                    'header'  => array(
                        "Content-type: application/json",
                        "Authorization: Bearer ". $api_key,
                    ),
                    'method'  => 'POST',
                    'content' => json_encode($data),
                ),
            );
            print_r($options);
            $context  = stream_context_create($options);
            echo file_get_contents($url, false, $context);            
        } else {
            $api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmFwcGVhci5pbiIsImF1ZCI6Imh0dHBzOi8vYXBpLmFwcGVhci5pbi92MSIsImV4cCI6OTAwNzE5OTI1NDc0MDk5MSwiaWF0IjoxNzE5MzEyNTk5LCJvcmdhbml6YXRpb25JZCI6MjQ0MDc2LCJqdGkiOiI0NWIxMmE3Yy1hYTBlLTRkMDAtYTE2ZC1mYTM3NGEzZDU1ODgifQ.-HW0uLTWzuTQ8w-6ALBu7SFQhrxxFJAkeR9dPz5KH2g";
            $url = 'https://api.whereby.dev/v1/meetings?fields=hostRoomUrl';
            $data = array(
                "fields" => ["hostRoomUrl"]
            );
            $options = array(
                'http' => array(
                    'header'  => array(
                        "Content-type: application/json",
                        "Authorization: Bearer ". $api_key,
                    ),
                    'method'  => 'GET',
                ),
            );
            $context  = stream_context_create($options);
            echo file_get_contents($url, false, $context);            
        }
    } else if (in_array($_SESSION["subject"], array("science", "physics", "chemistry", "biology"))) {
        $api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmFwcGVhci5pbiIsImF1ZCI6Imh0dHBzOi8vYXBpLmFwcGVhci5pbi92MSIsImV4cCI6OTAwNzE5OTI1NDc0MDk5MSwiaWF0IjoxNzE5MzEyNTk5LCJvcmdhbml6YXRpb25JZCI6MjQ0MDc2LCJqdGkiOiI0NWIxMmE3Yy1hYTBlLTRkMDAtYTE2ZC1mYTM3NGEzZDU1ODgifQ.-HW0uLTWzuTQ8w-6ALBu7SFQhrxxFJAkeR9dPz5KH2g";
        $url = 'https://api.whereby.dev/v1/meetings';
        $options = array(
            'http' => array(
                'header'  => array(
                    "Authorization: Bearer ". $api_key,
                ),
                'method'  => 'GET',
            ),
        );
        $context  = stream_context_create($options);
        echo file_get_contents($url, false, $context);            
    }
}
?>