<?php

    define('API_KEY', 'MY_ID_KEY');
    define('SECRET_KEY', 'MY_SECRET_KEY');
    $Sig = base64_encode(hash_hmac('sha256', 'date: "'.date('r').'"', SECRET_KEY, true));

    $result = '';

    $headers = [
        'Accept: application/json',
        'Accept-Encoding: gzip, deflate',
        'Cache-Control: no-cache',
        'Content-Type: application/json; charset=utf-8',
        'Host: http://localhost:8000',
        'Date: "'.date('r').'"',
        'X-Api-Key: '.API_KEY,
        'Authorization: Signature keyId="'.API_KEY.'",algorithm="hmac-sha256",headers="date",signature="'.$Sig.'"'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if(isset($_GET['addsubscriberslist'])) {
        $name = $_POST['sl-name'];
        $fields = array(
            'name' => $name
        );
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriberlist/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['editsubscriberslist'])) {
        $id = $_POST['sl-id'];
        $name = $_POST['sl-name'];
        $fields = array(
            'name' => $name
        );

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriberlist/".$id."/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['deletesubscriberslist'])) {
        $id = $_POST['sl-id'];

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriberlist/".$id."/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['subscriberslists'])) {
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriberlist/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['subscribers'])) {
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriber/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = preg_replace(array('#,#', '#{#'), array(',<br />', '{<br />'), $server_output);
        $result = preg_replace(array('#}#'), array('<br />}'), $result);
    }

    if(isset($_GET['addsubscriber'])) {
        $email = $_POST['s-email'];
        $info = $_POST['s-info'];
        $lists = $_POST['s-lists'];
        $fields = array(
            'email' => $email,
            'info' => $info,
            'lists' => explode(',', $lists)
        );

        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriber/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['editsubscriber'])) {
        $id = $_POST['s-id'];
        $email = $_POST['s-email'];
        $info = $_POST['s-info'];
        $lists = $_POST['s-lists'];
        $fields = array(
            'email' => $email,
            'info' => $info,
            'lists' => explode(',', $lists)
        );

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriber/".$id."/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }

    if(isset($_GET['deletesubscriber'])) {
        $id = $_POST['s-id'];

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/newsletter/subscriber/".$id."/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $result = $server_output;
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Tazebao API test</title>
    <meta charset='utf-8' />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <style type="text/css">
        .section {
            padding: 10px;
            background: #f6f6f6;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default">
      <div class="container">
        <h1>Tazebao API test</h1>
      </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2>Lists</h2>
                <ul>
                    <li><a href="?subscriberslists">Subscribers lists</a></li>
                    <li><a href="?subscribers">Subscribers</a></li>
                </ul>
                <h2>Result</h2>
                <?php echo $result ?>
            </div>
            <div class="col-md-4">
                <h2>Actions subscriber list</h2>
                <div class="section">
                    <h4>Add subscribers list</h4>
                    <form method='POST' action="?addsubscriberslist" class="form-horizontal">
                      <div class="form-group">
                        <label for="slname" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sl-name" id="slname" placeholder="List Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="add" />
                        </div>
                      </div>
                    </form>
                </div>

                <div class="section">
                    <h4>Edit subscribers list</h4>
                    <form method='POST' action="?editsubscriberslist" class="form-horizontal">
                      <div class="form-group">
                        <label for="slid" class="col-sm-2 control-label">Id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sl-id" id="slid" placeholder="List ID">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="slname" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sl-name" id="slname" placeholder="List Name">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="edit" />
                        </div>
                      </div>
                    </form>
                </div>

                <div class="section">
                    <h4>Delete subscribers list</h4>
                    <form method='POST' action="?deletesubscriberslist" class="form-horizontal">
                      <div class="form-group">
                        <label for="slid" class="col-sm-2 control-label">Id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sl-id" id="slid" placeholder="List ID">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="delete" />
                        </div>
                      </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <h2>Actions subscriber</h2>
                <div class="section">
                    <h4>Add subscriber</h4>
                    <form method='POST' action="?addsubscriber" class="form-horizontal">
                      <div class="form-group">
                        <label for="semail" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" name="s-email" id="semail" placeholder="E-mail">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="sinfo" class="col-sm-2 control-label">Info</label>
                        <div class="col-sm-10">
                          <textarea name="s-info"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="slists" class="col-sm-2 control-label">Lists</label>
                        <div class="col-sm-10">
                          <input type="text" name="s-lists" />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="add" />
                        </div>
                      </div>
                    </form>
                </div>

                <div class="section">
                    <h4>Edit subscriber</h4>
                    <form method='POST' action="?editsubscriber" class="form-horizontal">
                      <div class="form-group">
                        <label for="sid" class="col-sm-2 control-label">Id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="s-id" id="sid" placeholder="ID">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="semail" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="s-email" id="semail" placeholder="E-mail">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="sinfo" class="col-sm-2 control-label">Info</label>
                        <div class="col-sm-10">
                          <textarea name="s-info"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="slists" class="col-sm-2 control-label">Lists</label>
                        <div class="col-sm-10">
                          <input type="text" name="s-lists" />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="edit" />
                        </div>
                      </div>
                    </form>
                </div>

                <div class="section">
                    <h4>Delete subscriber</h4>
                    <form method='POST' action="?deletesubscriber" class="form-horizontal">
                      <div class="form-group">
                        <label for="slid" class="col-sm-2 control-label">Id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="s-id" id="sid" placeholder="ID">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-default" value="delete" />
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

