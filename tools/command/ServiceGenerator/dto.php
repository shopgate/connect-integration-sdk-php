<?php
/**
 * @var string $definitionName
 * @var string $serviceName
 * @var array $definition
 * @var ServiceGenerator $this
 * @var Symfony\Component\Console\Output\OutputInterface $output
 */

if (!function_exists('generateDtoArray')) {
    function generateDtoArray($definition)
    {
        if (!empty($definition['items']['$ref'])) {
            $chunks = explode('/', $definition['items']['$ref']);
            $defName = end($chunks);

            return <<<ARR
        'type'  => 'array',
        'items' => ['\$ref' => {$defName}::class, 'skipValidation' => true],
ARR;
        }

        if (!empty($definition['items']['type'])) {
            $itemsAsObjects = generateDtoObject($definition['items']);
            return <<<ARR
        'type'  => 'array',
        'items' => [
$itemsAsObjects
        ],
ARR;
        }
    }
}

if (!function_exists('generateDtoEnum')) {
    function generateDtoEnum($definition)
    {
        $enums = "'" . implode("','", $definition['enum']) . "'";
        return <<<ARR
    'enum' => [$enums],
ARR;
    }
}

if (!function_exists('generateDtoObject')) {
    function generateDtoObject($definition)
    {
        if (empty($definition['properties'])) {
            return '';
        }
        $propLines = [];
        foreach ($definition['properties'] as $prop => $property) {
            if (!empty($property['$ref'])) {
                $chunks = explode('/', $property['$ref']);
                $defName = end($chunks);
                $propLines[] = str_repeat(' ', 8)
                    . sprintf(
                        "'%s' => ['\$ref' => %s::class, 'skipValidation' => true],",
                        $prop,
                        $defName
                    );
                continue;
            }

            if (empty($property['type'])) {
                $property['type'] = 'string';
            }
            if (is_array($property['type'])) {
                $property['type'] = reset($property['type']);
            }

            if ($property['type'] === 'array') {
                $propLines[] = str_repeat(' ', 8)
                . sprintf(
                    "'%s' => [\n%s\n],",
                    $prop,
                    generateDtoArray($property)
                );
            } else {
                $propLines[] = str_repeat(' ', 8)
                    . sprintf(
                        "'%s' => ['type' => '%s'],",
                        $prop,
                        $property['type']
                    );
            }
        }

        $lines = implode(PHP_EOL, $propLines);

        return <<<ARR
        'type'  => 'object',
        'properties' => [
        $lines
        ],
        'additionalProperties' => true,
ARR;
    }
}

if (empty($definition['type'])) {
    $output->writeln('Definition type is unknown: ' . $definitionName);
    return;
}
?>

/**
 * Copyright Shopgate GmbH.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Shopgate GmbH
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Dto\<?= $this->pascalCase($serviceName) ?>;

use Shopgate\ConnectSdk\Dto\Meta;
use Shopgate\ConnectSdk\Dto\Base;

class <?= $this->pascalCase($definitionName) ?> extends Base
{
<?php if ($definition['type'] === 'string' && !empty($definition['enum'])) : ?>

    <?php foreach ($definition['enum'] as $enum) : ?>
const STATUS_<?= strtoupper($enum) ?> = '<?= $enum ?>';
    <?php endforeach; ?>
<?php endif; ?>

    /**
     * @var array
     */
    protected $schema = [
<?php if ($definition['type'] === 'string' && !empty($definition['enum'])) : ?>
    <?= generateDtoEnum($definition) ?>
<?php endif; ?>
<?php if ($definition['type'] === 'array') : ?>
    <?= generateDtoArray($definition) ?>
<?php endif; ?>
<?php if ($definition['type'] === 'object') : ?>
    <?= generateDtoObject($definition) ?>
<?php endif; ?>
    ];
}

<?php
