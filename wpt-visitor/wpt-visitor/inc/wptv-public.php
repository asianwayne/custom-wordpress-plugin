<?php 
//
add_action('wp_head', 'record_visitor_ip');


function record_visitor_ip() {
  // Validate and sanitize the user agent and IP address variables
  $user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);
  $page_route = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);

  $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
  $page_route = $protocol . "://$_SERVER[HTTP_HOST]$page_route";
  $page_route = urldecode($page_route);
  $visitor_ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);

  if (!$user_agent || !$visitor_ip || !$page_route) {
    // Invalid input, do nothing
    return;
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'visitor_ips'; // Replace "visitor_ips" with the name of the new table

  // Check if the visitor IP already exists in the table and if it was created less than an hour ago
  // 下面是不记录一小时内访问过的ip， 勿删  勿删勿删勿删勿删
  // $query = $wpdb->prepare("SELECT id FROM $table_name WHERE ip = %s AND time > DATE_SUB(NOW(), INTERVAL 1 HOUR)", $visitor_ip);
  // $result = $wpdb->get_var($query);

  // if (!$result) {
  //   // Insert the visitor info into the table
  //   $data = array(
  //     'ip' => $visitor_ip,
  //     'browser' => $user_agent,
  //     'route'  => $page_route,
  //     'time' => current_time('mysql')
  //   );

  //   $wpdb->insert($table_name, $data);
  // }

  //下面是不带ip是否存在验证，是ip就直接插入到数据库。 数据库很容易爆
  
  $data = array(
      'ip' => $visitor_ip,
      'browser' => $user_agent,
      'route'  => $page_route,
      'time' => current_time('mysql')
    );

    $wpdb->insert($table_name, $data);
}