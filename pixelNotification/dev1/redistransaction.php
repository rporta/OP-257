<?php

require_once '/var/www/html/oprafwk/lib/credis/Client.php';
require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';


$obj = new Test;
echo "Iniciando bucle...\n";
for ($i = 1; $i <= 10; $i++) {
    $obj->bucle();
}
echo "Bucle finalizado...\n";


class Test {

    protected $config;
    protected $redis;

    public function __construct()
    {
        $this->config = configJson::getInstance();
        $this->config->setConfigFile(__DIR__.'/config/config.json');

        $this->redis = new Credis_Client(
            $this->config->get('Db.redis.pixel.host'),
            $this->config->get('Db.redis.pixel.port'),
            null,
            '',
            $this->config->get('Db.redis.pixel.database')
        );
    }

    public function bucle($num = 1)
    {
        while (true) {
            echo "Iniciando...\n";
            $this->redis->watch("setup_key_" . $num);
            $v = $this->redis->get("setup_key_". $num);
            // if ($v == 2) {
            //     echo "Es 2\n";
            //     echo "Proceso finalizado\n";
            //     break;
            // }
            if ($v == 1) {
                echo "Es 1\n";
                echo "Esperamos\n";
                sleep(1);
                echo "Continuamos\n";
                continue;
            }
            $this->redis->multi();
            $this->redis->setex("setup_key_".$num, 10, 1); // 10s timeout
            echo "Intentando setear 1\n";
            // sleep(rand(1,3));
            if (!$this->redis->exec()) {
                echo "No se pudo setear 1\n";
                continue; // someone else got the lock in the meantime, try again
            }
            echo "Eliminando\n";
            // we now have a temporary exclusive "lock"
            //... do the real work ...
            $this->redis->del("setup_key_".$num);
            break;
        }

    }
}
// $get = $redis->get('test123');
// $redis->watch('test123');
// $redis->multi();
// $redis->incr('test123', 'multi');
// sleep(rand(1,4));
// $result = $redis->exec();
// print_r($result) . "\n";