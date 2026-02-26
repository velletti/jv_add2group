<?php

declare(strict_types=1);

namespace JVelletti\JvAdd2group\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('jvellettiJvAdd2groupCTypeMigration')]
final class JVellettiJvAdd2groupCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "JVelletti JvAdd2group" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "JVelletti JvAdd2group" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
           'jvadd2group_add2group' => 'jvadd2group_add2group',
        ];
    }
}
