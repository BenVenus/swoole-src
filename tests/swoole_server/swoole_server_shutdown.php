<?php
/**

 * Time: 下午4:34
 */
require_once __DIR__ . "/../include/swoole.inc";

$simple_tcp_server = __DIR__ . "/../include/api/swoole_server/opcode_server.php";
$port = get_one_free_port();

start_server($simple_tcp_server, TCP_SERVER_HOST, $port);

suicide(2000);
usleep(500 * 1000);

makeTcpClient(TCP_SERVER_HOST, $port, function(\swoole_client $cli) {
    $r = $cli->send(opcode_encode("shutdown", [2]));
    assert($r !== false);
}, function(\swoole_client $cli, $recv) {
    list($op, $data) = opcode_decode($recv);
    assert($data === true);
    swoole_event_exit();
    echo "SUCCESS";
});

?>
