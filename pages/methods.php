<?php

function checkUserLoginDetails($data) {
    $emp_id = 0;
    $query = "Select emp_id from employees_list where username = '" . $data['username'] . "' AND password = '" . md5($data['password']) . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $emp_id = $row['emp_id'];
        }
    }
    return $emp_id;
}

function getUserProfileInfo($login_user) {
    $data = array();
    $query = "Select first_name, last_name, emp_id, designation, emp_id from employees_list where username = '" . $login_user . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data['full_name'] = $row['first_name'] . " " . $row['last_name'];
            $data['photo'] = getUserPhoto($row['emp_id']);
            $data['designation'] = $row['designation'];
            $data['emp_id'] = $row['emp_id'];
            $data['username'] = $login_user;
        }
    }
    return $data;
}

function getUserPhoto($empid) {
    $query = "Select photo from employee_profile where emp_id = '" . $empid . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['photo'];
        }
    }
}

function checkUserNameExist($name) {
    $query = "Select * from employees_list where username = '" . $name . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    return $result->num_rows;
}

function getUserCompleteProfile($emp_id) {
    $query = "SELECT * FROM employees_list as el INNER JOIN employee_profile as ep ON el.emp_id=ep.emp_id where el.emp_id = '" . $emp_id . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    }
}

function confirmUserRegister($data) {
    $date = date('Y-m-d', strtotime($data['join_date']));
    $password = md5($data['password']);
    $last_id = getLastEmployeeId();

    $query = "insert into employees_list(emp_id, username, first_name, last_name, designation, password, status, join_date) values('" . $last_id . "', '" . $data['username'] . "', '" . $data['first_name'] . "','" . $data['last_name'] . "', '" . $data['designation'] . "', '" . $password . "', 'working', '" . $date . "')";

    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result) {
        $query_update = "insert into employee_profile(emp_id, user_type) values('" . $last_id . "', 'employee')";
        $result_update = mysqli_query($GLOBALS['conn'], $query_update);
    }
    return $result_update;
}

function getLastEmployeeId() {
    $query = "Select id from employees_list order by id desc limit 1";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            return "qpro" . $row['id'];
        }
    }
}

function updateUserProfile($data) {
    $query1 = "Update employees_list set first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', designation = '" . $data['designation'] . "' where emp_id = '" . $data['emp_id'] . "'";
    $result1 = mysqli_query($GLOBALS['conn'], $query1);
    echo $result1;
    $query2 = "Update employee_profile set email = '" . $data['email'] . "', contact = '" . $data['contact'] . "', address = '" . $data['address'] . "' where emp_id = '" . $data['emp_id'] . "'";
    $result2 = mysqli_query($GLOBALS['conn'], $query2);

    if ($result1 && $result2) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function updateUploadedDocuments($files, $emp_id) {

    $query = "Update employee_profile set photo = '" . "images/profile/" . $files['photo']['name'][0] . "', resume = '" . "resume/" . $files['photo']['name'][1] . "', documents = '" . "documents/" . $files['photo']['name'][2] . "' where emp_id = '" . $emp_id . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    return $result;
}

function getExperienceFromJoinDate($date) {

    $today = date("Y-m-d");
    $diff = abs(strtotime($today) - strtotime($date));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    return $years . " Years " . $months . " Month " . $days . " Days";
}

function getEmployeeDetails() {
    $details = array();
    $i = 0;
    $query = "SELECT * FROM employees_list as el INNER JOIN employee_profile as ep ON el.emp_id=ep.emp_id";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $details[$i]['emp_id'] = $row['emp_id'];
            $details[$i]['username'] = $row['username'];
            $details[$i]['name'] = $row['first_name'] . " " . $row['last_name'];
            $details[$i]['email'] = $row['email'];
            $details[$i]['designation'] = $row['designation'];
            $details[$i]['contact'] = $row['contact'];
            $details[$i]['photo'] = $row['photo'];
            $i++;
        }
    }
    return $details;
}

function checkLeaveValidity($request_data) {
    $flag = 1;
    $public_leave = getPublicHolidayList();

    if ($request_data['day_count'] == 'multiple') {
        $date_arr = getAllRequestedDate($request_data['start_date'], $request_data['end_date']);
    } else {
        $date_arr = $request_data['start_date'];
    }

    for ($i = 0; $i < count($date_arr); $i++) {
        if (in_array($date_arr[$i], $public_leave)) {
            $flag = 0;
            return $flag;
        }
    }
    return $flag;
}

function checkPendingRequest($request_data) {

    $query = "SELECT * FROM leave_requests WHERE status ='pending' AND emp_id = '" . $request_data['emp_id'] . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        return 1;
    }
    return 0;
}

function submitLeaveRequest($request_data) {
    $date = date('Y-m-d');
    $start_date = date("Y-m-d", strtotime($request_data['start_date']));
    $end_date = date("Y-m-d", strtotime($request_data['end_date']));
    $query = "insert into leave_requests(emp_id, reason, leave_category, leave_from, leave_to, from_type, to_type, status, requested_date) values('" . $request_data['emp_id'] . "', '" . $request_data['reason'] . "', '" . $request_data['category'] . "','" . $start_date . "','" . $end_date . "','" . $request_data['from_type'] . "', '" . $request_data['to_type'] . "','pending', '" . $date . "')";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result) {
        return TRUE;
    }
    return FALSE;
}

function getPublicHolidayList() {
    $details = array();
    $i = 0;

    $query = "SELECT * FROM public_holidays where status = 'active'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $details[$i] = date("m/d/Y", strtotime($row['date']));
            $i++;
        }
    }
    return $details;
}

function getAllRequestedDate($fromdate, $todate) {
    $i = 0;
    $from_date = DateTime::createFromFormat('m/d/Y', $fromdate);
    $to_date = DateTime::createFromFormat('m/d/Y', $todate);
    $datePeriod = new DatePeriod($from_date, new DateInterval('P1D'), $to_date->modify('+1 day'));
    foreach ($datePeriod as $date) {
        $date_arr[$i] = $date->format('m/d/Y');
        $i++;
    }
    return $date_arr;
}

function getLeaveRequestDetails() {
    $details = array();
    $i = 0;
    $query = "SELECT * FROM leave_requests";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $details[$i]['emp_id'] = $row['emp_id'];
            $profile_info = getUsernamefromID($row['emp_id']);
            $details[$i]['full_name'] = $profile_info['full_name'];
            $details[$i]['username'] = $profile_info['username'];
            $details[$i]['reason'] = $row['reason'];
            $details[$i]['category'] = $row['leave_category'];
            $details[$i]['leave_date'] = $row['leave_from'] . " to " . $row['leave_to'];
            $details[$i]['status'] = $row['status'];
            $i++;
        }
    }
    return $details;
}

function getUsernamefromID($user_id) {
    $data = array();
    $query = "Select first_name, last_name, username from employees_list where emp_id = '" . $user_id . "'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data['full_name'] = $row['first_name'] . " " . $row['last_name'];
            $data['username'] = $row['username'];
        }
    }
    return $data;
}

function addNotification($data) {

    $date = date("Y-m-d");
    $query = "insert into notifications(notifications, status, added_date) values('" . $data['notification'] . "', 'Active', '" . $date . "')";
    $result = mysqli_query($GLOBALS['conn'], $query);
    return $result;
}

function getAllNotifications() {
    $details = array();
    $i = 0;
    $query = "SELECT * FROM notifications where status = 'Active'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    if ($result->num_rows) {
        while ($row = mysqli_fetch_assoc($result)) {
            $details[$i]['id'] = $row['id'];
            $details[$i]['notification'] = $row['notifications'];
            $details[$i]['status'] = $row['status'];
            $details[$i]['added_date'] = $row['added_date'];
            $i++;
        }
    }
    return $details;
}

function deleteNotification($id) {
    $date  = date("Y-m-d");
    $query = "Update notifications set status = 'Remove', removed_date = '".$date."' where id = $id";
    $result = mysqli_query($GLOBALS['conn'], $query);
    return $result;
}