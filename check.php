<?php

    require_once('api.class.php');
    $url     = 'https://mail.dataarc.com/api/jsonrpcserver';
    $api_key = 'a662c9247d5751a5e00728d2d7f0f844a663fe4c829adb6036f4a6b4d7f02fe0';
    $list_id = 579741;
    // Create API wrapper object
    $api = new Api($url, $api_key, '3.3');
    // Enable request debugging
    $api->setDebug(true);

    // If required, set the proxy connection details
    // $api->setProxy('');
    // Get a list of folders
    $contacts[] = array(
                        'First Name' => 'Test',
                        'Last Name' =>  'Test',
                        'Email'      => 'test@example.com',
                        'Country'   =>  'VN',
                        'Terms & Conditions'    => 'On',
                        'Hotel' =>  'LICO'
                        );
    
    $listFolders = $api->invokeMethod('addContacts', $list_id, $contacts);

    echo PHP_EOL . 'Response:' . PHP_EOL;
    print_r($listFolders);
    /*
    $returned_fields = array('id', 'Email','First Name');
    $search_criteria = array(
                             array('First Name', 'equal', 'Test'),
                            
                             );
    $contacts = $api->invokeMethod('searchContacts', $list_id, $search_criteria, 0, 0, '', '', $returned_fields);
    var_dump($contacts);
   
*/

