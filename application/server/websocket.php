<?php


class WebSocket
{
	const HOST = '0.0.0.0';
	const PORT = 9501;
	private $server;

	public function __construct()
	{
		$this->server = new swoole_websocket_server(self::HOST, self::PORT);
		$this->server->set([
			'worker_num' => 2,          // 开启2个worker进程
			'max_request' => 4,         // 每个worker进程 max_request设置为4次
			'task_worker_num' => 4,     // 开启4个task进程
			'dispatch_mode' => 4,       // 数据包分发策略
			'daemonize' => false,       // 守护进程
		]);

		$this->server->on('Start', [$this, 'onStart']);
		$this->server->on('Open', [$this, 'onOpen']);
		$this->server->on('Message', [$this, 'onMessage']);
		$this->server->on('Close', [$this, 'onClose']);
		$this->server->on('Task', [$this, 'onTask']);
		$this->server->on('Finish', [$this, 'onFinish']);

		$this->server->start();
	}

	public function onStart($server)
	{
		echo "#### onStart ####".PHP_EOL;
		echo "SWOOLE ".SWOOLE_VERSION . " 服务已启动".PHP_EOL;
		echo "master_pid: {$server->master_pid}".PHP_EOL;
		echo "manager_pid: {$server->manager_pid}".PHP_EOL;
		echo "########".PHP_EOL.PHP_EOL;
	}

	public function onOpen($server, $request)
	{
		echo "connection open: {$request->fd}\n";
	}

	public function onTask($server, $task_id, $from_id, $data) {
		echo "#### onTask ####".PHP_EOL;
		echo "#{$server->worker_id} onTask: [PID={$server->worker_pid}]: task_id={$task_id}".PHP_EOL;
		$msg = '';
		switch ($data['type']) {
			case 'login':
				$msg = '我来了...';
				break;
			case 'speak':
				$msg = $data['msg'];
				break;
		}
		foreach ($server->connections as $fd) {
			$connectionInfo = $server->connection_info($fd);
			if ($connectionInfo['websocket_status'] == 3) {
				$server->push($fd, $msg); //长度最大不得超过2M
			}
		}
		$server->finish($data);
		echo "########".PHP_EOL.PHP_EOL;
	}

	public function onMessage($server, $frame)
	{
		echo "received from {$frame->fd}: {$frame->data}, opcode: {$frame->opcode}, fin: {$frame->finish}\n";

		foreach ($server->connections as $fd) {
			if ($server->isEstablished($fd)) {
				$server->push($fd, json_encode([
					"on"=>$frame->fd,
					"time" => date('Y/m/d H:i:s'),
					"msg"=>$frame->data
				]));
			}
		}
	}

	public function onFinish($server, $task_id, $data) {
		echo "#### onFinish ####".PHP_EOL;
		echo "Task {$task_id} 已完成".PHP_EOL;
		echo "########".PHP_EOL.PHP_EOL;
	}

	public function onClose($server, $fd)
	{
		echo "connection close: {$fd}\n";
	}

}

$server = new WebSocket();
