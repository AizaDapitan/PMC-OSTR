<?php

include(__DIR__ . '/config.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: *");


$array_status = array();
$data_result = sqlsrv_query($conn, "select * from transactions where ref_req_no in (" . $ids . ") and details = 'OSTR' and status <> 'Pending'");

while ($result = sqlsrv_fetch_array($data_result)) {

    $array_status[] .= $result['ref_req_no'] . ':' . $result['status'];
    
}
return $array_status;
