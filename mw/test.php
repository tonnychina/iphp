<?php
include_once(dirname(__FILE__) . '/../framework/Logger.php');
Logger::init();

include_once(dirname(__FILE__) . '/MasterWorker.php');

class MyMasterWorker extends MasterWorker
{
	function master(){
		for($i=0; $i<100; $i++){
			#Logger::debug("add job $i");
			$this->add_job($i);
			#$this->wait();
			#Logger::debug("");
		}
		Logger::debug("master added all $i jobs");
		
		// 当需要在确保所有任务处理完毕后再做其它操作时, 才需要调用 wait
		$this->wait();
		// ...
		Logger::debug("all job done");
	}

	function worker($job){
		usleep(mt_rand(1, 6) * 100 * 1000);
		// ...
		$pid = posix_getpid();
		Logger::debug("[$pid] process job: " . json_encode($job));
		return true;
	}
}

$mw = new MyMasterWorker();
$mw->set_num_workers(3);
$mw->run();



