<?php 
$error = FALSE;
if(!file_exists('config.php')){
    $error = TRUE;
    $message = 'It seems like <strong>config.php</strong> does not exist. Please copy and rename the file <strong>config.php.sample</strong> to <strong>config.php</strong> and provide valid API Keys. API keys can be obtained from'.
            ' <a href="http://www.pro.whitepages.com/lp/search-by-api-signup/">http://www.pro.whitepages.com/lp/search-by-api-signup/</a>';
} else {
    require_once('config.php');
    $message = '';
    if(!isset($API_KEY) || empty($API_KEY)){
        $message .= ' $API_KEY is empty.';
        $error = TRUE;
    }
    if($error == TRUE){
        $message .= ' Please edit <strong>config.php</strong>, keys can be obtained from <a href="http://www.pro.whitepages.com/lp/search-by-api-signup/">http://www.pro.whitepages.com/lp/search-by-api-signup/</a>';
    }
} ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <title>Whitepages Importer</title>
        <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="js/lib/jquery.cookie.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs/dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/s/bs/dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0/datatables.min.js"></script>
<style>
    .main-container {
        padding-top:20px;
    }
    .content-center {
        text-align: center;
    }
    
    #controlPanel {
        margin-top: 20px;
        margin-bottom: 10px;
    }
</style>
    </head>
    <body>
        <div class="container-fluid main-container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="content-center">
                        <form class="form-inline">
                            <div class="form-group">
                              <label for="exampleInputName2"></label>
                              <input type="text" class="form-control input-lg" id="input-search-term" placeholder="Search For.." data-toggle="tooltip" data-placement="bottom" title="Please enter person name" required >
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail2"></label>
                              <input type="text" class="form-control input-lg" id="input-search-location" placeholder="City">
                            </div>
                            <div class="form-group">
                              <label for="postal-code"></label>
                              <input type="text" class="form-control input-lg" id="input-search-postal-code" placeholder="Postal Code" data-toggle="tooltip" data-placement="bottom" title="5- or 9- digit US or 6-digit Canadian zipcode eg: 92019 840656008 or S3D 3F3">
                            </div>
                            <button type="submit" class="btn btn-default btn-lg" id="btn-search-submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            <div clas="clearfix"></div>
                            <div class="form-group" id="controlPanel"></div>
                        </form>
                    </div>
                    <div class="alert alert-danger alert-dismissible hidden" role="alert">
                        <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <!--<strong>Error !</strong> --><div id="error-message"></div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>ZIP</th>
                        <!-- <th>URL</th>
                        <th>URL</th> -->
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="js/main.js"></script>
    </body>
</html>