<?php
namespace App\Application;

use Symfony\Component\HttpKernel\KernelInterface;

class ConfigApplication
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return mixed
     */
    public function getLibConfig()
    {
        $baseDir = $this->kernel->getProjectDir();
        $libConfigFile = $baseDir . '/lib/config.php';
        return require $libConfigFile;
    }

    /**
     * @param array $taskConfigs
     * @return string
     */
    public function writeConfig(array $taskConfigs): string
    {
        $libConfigs = $this->getLibConfig();
        $generateConfigFile = $libConfigs['dataDir'] . '/generateConfigs.php';
        $configContents = "<?php \$configs['tasks'] = " . var_export($taskConfigs, true) . ';';
        file_put_contents($generateConfigFile, $configContents);
        return $configContents;
    }
}