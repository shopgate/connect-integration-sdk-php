<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ServiceGenerator extends Command
{
    protected static $defaultName = 'pregen:service';

    protected function configure()
    {
        $this
            ->setDescription('Generate service by given swagger yml file.')
            ->setHelp(<<<HELP
To generate service PHP SDK, run the following
./tools/console generate:service https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/swagger-docs/omni/segmentation-crud.yaml
HELP
)
            ->addArgument('swagger-url', InputArgument::REQUIRED, 'Remote swagger yml file URL');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $swaggerContent = file_get_contents($input->getArgument('swagger-url'));
        if (!$swaggerContent) {
            throw new InvalidArgumentException('Can not download external swagger file');
        }

        $swagger = $value = Yaml::parse($swaggerContent);
        list($serviceName) = explode(' ', $swagger['info']['title']);

        $templateDir = __DIR__ . '/ServiceGenerator';

        foreach ($swagger['definitions'] as $definitionName => $definition) {
            $this->generateDto(
                compact(
                    'serviceName',
                    'templateDir',
                    'definitionName',
                    'definition',
                    'output'
                )
            );
        }

        $this->generateService(
            compact(
                'serviceName',
                'templateDir',
                'swagger',
                'output'
            )
        );

        $output->writeln('Done. Check and commit');
    }

    private function generateService($params)
    {
        extract($params, EXTR_OVERWRITE);

        error_reporting(0);
        ob_start();
        include __DIR__ . '/ServiceGenerator/service_template.php';
        $serviceContent = '<?php ' . PHP_EOL . ob_get_clean();

        /** @noinspection PhpUndefinedVariableInspection */
        file_put_contents(
            __DIR__ . '/../../src/Service/' . $serviceName . '.php',
            $serviceContent
        );
    }

    private function generateDto($params)
    {
        extract($params, EXTR_OVERWRITE);

        ob_start();
        include __DIR__ . '/ServiceGenerator/dto.php';
        $fileContent = ob_get_clean();

        if (!$fileContent) {
            return;
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $dir = __DIR__ . '/../../src/Dto/' . $this->pascalCase($serviceName);
        if (!is_dir($dir)) {
            if (!mkdir($dir)) {
                throw new \RuntimeException(sprintf('Can not make Dto dir "%s"', $dir));
            }
        }

        /** @noinspection PhpUndefinedVariableInspection */
        file_put_contents(
            __DIR__ . '/../../src/Dto/' . $this->pascalCase($serviceName) . '/' . $this->pascalCase($definitionName) . '.php',
            '<?php ' . PHP_EOL . $fileContent
        );
    }

    private function methodName($path, $pathParams)
    {
        if (!empty($pathParams['summary'])) {
            return $this->camelCase($pathParams['summary']);
        }
        $pathChunks = explode('/', $path);
        return end($pathChunks);
    }

    private function camelCase($string)
    {
        return lcfirst($this->pascalCase($string));
    }

    private function pascalCase($string)
    {
        return preg_replace(
            '/\W/',
            '',
            ucwords($string)
        );
    }

    private function snakeCase($string)
    {
        return preg_replace(
            '/\W/',
            '_',
            ucwords($string)
        );
    }
}
