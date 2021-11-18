<?php

declare(strict_types = 1);

namespace AvtoDev\Composer\Cleanup;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Composer\Installer\PackageEvent;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvents;
use Composer\Package\PackageInterface;
use Composer\Script\Event as ScriptEvent;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\DependencyResolver\Operation\InstallOperation;

final class Plugin implements PluginInterface, EventSubscriberInterface
{
    public const SELF_PACKAGE_NAME = 'avto-dev/composer-cleanup-plugin';

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        // Nothing to do here, as all features are provided through event listeners
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // Nothing to do here
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // Nothing to do here
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'handlePostPackageInstallEvent',
            PackageEvents::POST_PACKAGE_UPDATE  => 'handlePostPackageUpdateEvent',
        ];
    }

    /**
     * @param ScriptEvent $event
     *
     * @return void
     */
    public static function cleanupAllPackages(ScriptEvent $event): void
    {
        $io            = $event->getIO();
        $composer      = $event->getComposer();
        $fs            = new Filesystem;
        $global_rules  = Rules::getGlobalRules();
        $package_rules = Rules::getPackageRules();

        $installation_manager = $composer->getInstallationManager();

        $saved_size_bytes = 0;
        $start_time       = \microtime(true);

        // Loop over all installed packages
        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            $package_name = $package->getName();
            $install_path = $installation_manager->getInstallPath($package);

            $saved_size_bytes += self::makeClean($install_path, $global_rules, $fs, $io);

            // Try to extract defined targets for a package
            if (isset($package_rules[$package_name])) {
                $saved_size_bytes += self::makeClean($install_path, $package_rules[$package_name], $fs, $io);
            }
        }

        $io->write(\sprintf(
            '<info>%s:</info> Cleanup done in %01.3f seconds (<comment>%d Kb</comment> saved)',
            self::SELF_PACKAGE_NAME,
            \microtime(true) - $start_time,
            $saved_size_bytes / 1024
        ));
    }

    /**
     * @param PackageEvent $event
     *
     * @return void
     */
    public static function handlePostPackageInstallEvent(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        if ($operation instanceof InstallOperation) {
            static::cleanupPackage($operation->getPackage(), $event->getIO(), $event->getComposer());
        }
    }

    /**
     * @param PackageEvent $event
     *
     * @return void
     */
    public static function handlePostPackageUpdateEvent(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        if ($operation instanceof UpdateOperation) {
            static::cleanupPackage($operation->getTargetPackage(), $event->getIO(), $event->getComposer());
        }
    }

    /**
     * @param PackageInterface $package
     * @param IOInterface      $io
     * @param Composer         $composer
     *
     * @return void
     */
    protected static function cleanupPackage(PackageInterface $package, IOInterface $io, Composer $composer): void
    {
        $fs               = new Filesystem;
        $saved_size_bytes = 0;
        $package_rules    = Rules::getPackageRules();

        $install_path = $composer->getInstallationManager()->getInstallPath($package);

        // use global rules at first
        $saved_size_bytes += self::makeClean($install_path, Rules::getGlobalRules(), $fs, $io);

        // then check for individual package rule
        if (isset($package_rules[$package->getName()])) {
            $saved_size_bytes += self::makeClean($install_path, $package_rules[$package->getName()], $fs, $io);
        }

        if ($saved_size_bytes > 1024 * 32 || $io->isVerbose() || $io->isVeryVerbose()) {
            $io->write(\sprintf('    ↳ Cleanup done: <comment>%d Kb</comment> saved', $saved_size_bytes / 1024));
        }
    }

    /**
     * @param string        $package_path
     * @param array<string> $rules
     * @param Filesystem    $fs
     * @param IOInterface   $io
     *
     * @return int
     */
    private static function makeClean(string $package_path, array $rules, Filesystem $fs, IOInterface $io): int
    {
        $saved_size_bytes = 0;

        foreach ($rules as $rule) {
            $paths = \glob($package_path . DIRECTORY_SEPARATOR . \ltrim(\trim($rule), '\\/'), \GLOB_ERR);

            if (\is_array($paths)) {
                foreach ($paths as $path) {
                    try {
                        $path_size = $fs->size($path);

                        if ($fs->remove($path)) {
                            $saved_size_bytes += $path_size;
                        }
                    } catch (\Throwable $e) {
                        $io->writeError(\sprintf(
                            '<info>%s:</info> Error occurred: %s', self::SELF_PACKAGE_NAME, $e->getMessage()
                        ));
                    }
                }
            }
        }

        return $saved_size_bytes;
    }
}
