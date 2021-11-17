<?php

declare(strict_types = 1);

namespace AvtoDev\Composer\Cleanup;

class Rules
{
    /**
     * Get global packages cleanup rules.
     *
     * Values can contains asterisk (`*` - zero or more characters) and question mark (`?` - exactly one character).
     *
     * @see <https://www.php.net/manual/en/function.glob.php#refsect1-function.glob-parameters>
     *
     * @return array<string>
     */
    public static function getGlobalRules(): array
    {
        return [
            '*.md', '*.MD', '*.rst', '*.RST', '*.markdown',
            // Markdown/reStructuredText files like `README.md`, `changelog.MD`..
            'AUTHORS', 'LICENSE', 'COPYING', 'AUTHORS', // Text files without extensions
            'CHANGES.txt', 'CHANGES', 'CHANGELOG.txt', 'LICENSE.txt', 'TODO.txt', 'README.txt', // Text files
            '.github', '.gitlab', // .git* specific directories
            '.gitignore', '.gitattributes', // git-specific files
            'phpunit.xml*', 'phpstan.neon*', 'phpbench.*', 'psalm.*', '.psalm', // Test configurations
            '.travis.yml', '.travis', '.scrutinizer.yml', '.circleci', 'appveyor.yml', // CI
            '.codecov.yml', '.coveralls.yml', '.styleci.yml', '.dependabot', // CI
            '.php_cs', '.php_cs.*', 'phpcs.*', '.*lint', // Code-style definitions
            '.gush.yml', 'bors.toml', '.pullapprove.yml', // 3rd party integrations
            '.editorconfig', '.idea', '.vscode', // Configuration for editors
            'phive.xml', 'build.xml', // Build configurations
            'composer.lock', // Composer lock file
            'Makefile', // Scripts, Makefile
            'Dockerfile', 'docker-compose.yml', 'docker-compose.yaml', '.dockerignore', // Docker

            '.git', '.svn',
        ];
    }

    /**
     * Get packages cleanup rules as array, where key is package name, and value is an array of directories and/or
     * file names, which must be deleted.
     *
     * Values can contains asterisk (`*` - zero or more characters) and question mark (`?` - exactly one character).
     *
     * @see <https://www.php.net/manual/en/function.glob.php#refsect1-function.glob-parameters>
     *
     * @return array<string, array<string>>
     */
    public static function getPackageRules(): array
    {
        return [
            'dnoegel/php-xdg-base-dir'         => ['tests'],
            'lcobucci/jwt'                     => ['test'],
            'monolog/monolog'                  => ['tests'],
            'morrislaptop/laravel-queue-clear' => ['tests'],
            'myclabs/deep-copy'                => ['doc'],
            'nikic/php-parser'                 => ['test', 'test_old', 'doc'],
            'phpstan/phpdoc-parser'            => ['doc'],
            'rap2hpoutre/laravel-log-viewer'   => ['tests'],
            'schuppo/password-strength'        => ['tests'],
            'spatie/laravel-permission'        => ['art', 'docs'],
            'symfony/css-selector'             => ['Tests'],
            'symfony/debug'                    => ['Tests'],
            'symfony/event-dispatcher'         => ['Tests'],
            'symfony/filesystem'               => ['Tests'],
            'symfony/finder'                   => ['Tests'],
            'symfony/http-foundation'          => ['Tests'],
            'symfony/http-kernel'              => ['Tests'],
            'symfony/options-resolver'         => ['Tests'],
            'symfony/routing'                  => ['Tests'],
            'symfony/stopwatch'                => ['Tests'],

            'artesaos/seotools'                     => ['tests'],
            'cakephp/chronos'                       => ['docs'],
            'deployer/deployer'                     => ['docs'],
            'deployer/recipes'                      => ['docs'],
            'google/apiclient'                      => ['docs'],
            'hackzilla/password-generator'          => ['Tests'],
            'phenx/php-font-lib'                    => ['tests'],
            'predis/predis'                         => ['examples'],
            'rmccue/requests'                       => ['tests', 'docs', 'examples'],
            'stil/gd-text'                          => ['examples', 'tests'],
            'theiconic/php-ga-measurement-protocol' => ['tests', 'docs'],
            'zircote/swagger-php'                   => ['tests', 'examples', 'docs'],

            'chumper/zipper'               => ['tests'],
            'cogpowered/finediff'          => ['tests'],
            'elasticsearch/elasticsearch'  => ['tests', 'travis', 'docs'],
            'meyfa/php-svg'                => ['tests'],
            'ralouphie/getallheaders'      => ['tests'],
            'react/promise'                => ['tests'],
            'sabberworm/php-css-parser'    => ['tests'],
            'unisharp/laravel-filemanager' => \array_merge(self::getLaravelFileManagerRules(), ['tests', 'docs']),
            'yoomoney/yookassa-sdk-php'    => ['tests', '*.md'],

            'binarytorch/larecipe'                  => ['package*', '*.js', 'yarn.lock'],
            'clue/stream-filter'                    => ['tests', 'examples'],
            'dragonmantank/cron-expression'         => ['tests'],
            'erusev/parsedown-extra'                => ['test'],
            'friendsofphp/php-cs-fixer'             => ['*.sh', 'doc'], // Note: `tests` must be not included
            'fakerphp/faker'                        => \array_merge(self::getFakerPhpRules(), ['test']),
            'hamcrest/hamcrest-php'                 => ['tests'],
            'jakub-onderka/php-console-color'       => ['tests'],
            'jakub-onderka/php-console-highlighter' => ['tests', 'examples'],
            'johnkary/phpunit-speedtrap'            => ['tests'],
            'justinrainbow/json-schema'             => ['demo'],
            'kevinrob/guzzle-cache-middleware'      => ['tests'],
            'mockery/mockery'                       => ['tests', 'docker', 'docs'],
            'mtdowling/jmespath.php'                => ['tests'],
            'nesbot/carbon'                         => self::getNesbotCarbonRules(),
            'paragonie/random_compat'               => ['other', '*.sh'],
            'paragonie/sodium_compat'               => ['*.sh', 'plasm-*.*', 'dist'],
            'phar-io/manifest'                      => ['tests', 'examples'],
            'phar-io/version'                       => ['tests'],
            'phpunit/php-code-coverage'             => ['tests'],
            'phpunit/php-file-iterator'             => ['tests'],
            'phpunit/php-timer'                     => ['tests'],
            'phpunit/php-token-stream'              => ['tests'],
            'phpunit/phpunit'                       => ['tests'],
            'psy/psysh'                             => ['.phan', 'test', 'vendor-bin'],
            'queue-interop/amqp-interop'            => ['tests'],
            'queue-interop/queue-interop'           => ['tests'],
            'sebastian/code-unit-reverse-lookup'    => ['tests'],
            'sebastian/comparator'                  => ['tests'],
            'sebastian/diff'                        => ['tests'],
            'sebastian/environment'                 => ['tests'],
            'sebastian/exporter'                    => ['tests'],
            'sebastian/object-enumerator'           => ['tests'],
            'sebastian/object-reflector'            => ['tests'],
            'sebastian/recursion-context'           => ['tests'],
            'sebastian/resource-operations'         => ['tests', 'build'],
            'sentry/sentry-laravel'                 => ['test', 'scripts', '.craft.yml'],
            'spiral/goridge'                        => ['examples', '*.go', 'go.mod', 'go.sum'],
            'spiral/roadrunner'                     => [
                'cmd', 'osutil', 'service', 'util', 'systemd', '*.mod', '*.sum', '*.go', '*.sh', 'tests',
            ],
            'swiftmailer/swiftmailer'               => ['tests', 'doc'],
            'symfony/psr-http-message-bridge'       => ['Tests'],
            'symfony/service-contracts'             => ['Test'],
            'symfony/translation'                   => ['Tests'],
            'symfony/translation-contracts'         => ['Test'],
            'symfony/var-dumper'                    => ['Tests', 'Test'],
            'theseer/tokenizer'                     => ['tests'],

            'sebastian/type'            => ['tests'],
            'sebastian/global-state'    => ['tests'],
            'sebastian/code-unit'       => ['tests'],
            'phpunit/php-invoker'       => ['tests'],
            'facade/ignition-contracts' => ['Tests', 'docs'],
            'doctrine/annotations'      => ['docs'],
            'doctrine/inflector'        => ['docs'],
            'doctrine/instantiator'     => ['docs'],

            'voku/portable-ascii'            => ['docs'],
            'anhskohbo/no-captcha'           => ['tests'],
            'beyondcode/laravel-dump-server' => ['docs'],
            'dompdf/dompdf'                  => ['LICENSE.LGPL', 'VERSION'],
            'kalnoy/nestedset'               => ['tests'],
            'phenx/php-svg-lib'              => ['tests', 'COPYING.GPL'],
            'wapmorgan/morphos'              => ['tests', '*.md'],
            'proj4php/proj4php'              => ['test'],
            'aws/aws-sdk-php'                => ['.changes', '.github'],

            'achingbrain/php5-akismet'          => ['src/site', 'src/test'],
            'tpyo/amazon-s3-php-class'          => ['example*.php'],
            'snipe/banbuilder'                  => ['tests'],
            'chargebee/chargebee-php'           => ['test'],
            'bshaffer/oauth2-server-php'        => ['test'],
            'paypal/paypal-checkout-sdk'        => ['samples', 'tests'],
            'recurly/recurly-client'            => ['Tests', 'scripts'],
        ];
    }

    /**
     * Package fzaninotto/faker moved to fakerphp/faker.
     *
     * @return array<string>
     */
    protected static function getFakerPhpRules(): array
    {
        return \array_map(static function (string $locale): string {
            return "src/Faker/Provider/{$locale}";
        }, [
            'el_GR', 'en_SG', 'fa_IR', 'ja_JP', 'mn_MN', 'pl_PL', 'vi_VN', 'zh_CN', 'sk_SK',
            'ar_JO', 'en_AU', 'en_UG', 'fi_FI', 'hu_HU', 'ka_GE', 'ms_MY', 'pt_BR', 'sr_RS',
            'ar_SA', 'cs_CZ', 'en_CA', 'hy_AM', 'kk_KZ', 'nb_NO', 'pt_PT', 'sv_SE', 'zh_TW',
            'at_AT', 'da_DK', 'en_ZA', 'fr_BE', 'id_ID', 'ko_KR', 'ne_NP', 'ro_MD', 'tr_TR',
            'en_HK', 'es_AR', 'fr_CA', 'nl_BE', 'ro_RO', 'th_TH', 'fr_CH', 'lt_LT', 'nl_NL',
            'de_AT', 'en_IN', 'es_ES', 'es_PE', 'fr_FR', 'is_IS', 'lv_LV', 'de_CH', 'en_NG',
            'bg_BG', 'de_DE', 'en_NZ', 'es_VE', 'he_IL', 'it_CH', 'me_ME', 'sl_SI', 'bn_BD',
            'el_CY', 'en_PH', 'et_EE', 'hr_HR', 'it_IT', 'uk_UA', 'sr_Cyrl_RS', 'sr_Latn_RS',
        ]);
    }

    /**
     * @return array<string>
     */
    protected static function getNesbotCarbonRules(): array
    {
        return \array_map(static function (string $locale): string {
            return "src/Carbon/Lang/{$locale}.php";
        }, [
            'a?*_*', 'a?', 'a??',
            'b?*_*', 'b?', 'b??',
            'c?*_*', 'c?', 'c??',
            'd?*_*', 'd?', 'd??',
            'e[bel]_*', 'en_[01ABCDEFHIJKLMN]*', 'en_G[DGHIMUY]*', 'e[belostw]?', 'e[stw]_*', 'en_[PRSTUVWZ]?',
            'e[elnost]',
            'f?*_*', 'f?', 'f??',
            'g?*_*', 'g?', 'g??',
            'h?*_*', 'h?', 'h??',
            'i?*_*', 'i?', 'i??',
            'j?*_*', 'j?', 'j??',
            'k?*_*', 'k?', 'k??',
            'l?*_*', 'l?', 'l??',
            'm?*_*', 'm?', 'm??',
            'n?*_*', 'n?', 'n??',
            'o?*_*', 'o?', 'o??',
            'p?*_*', 'p?', 'p??',
            'q?*_*', 'q?', 'q??',
            'r[ow]?_*', 'r[amnojw]*',
            's?*_*', 's?', 's??',
            't?*_*', 't?', 't??',
            'u[gznrz]*',
            'v?*_*', 'v?', 'v??',
            'w?*_*', 'w?', 'w??',
            'x?*_*', 'x?', 'x??',
            'y?*_*', 'y?', 'y??',
            'z?*_*', 'z?', 'z??',
        ]);
    }

    /**
     * @return string[]
     */
    protected static function getLaravelFileManagerRules(): array
    {
        return \array_map(static function (string $locale): string {
            return "src/lang/Provider/{$locale}";
        }, [
            'ar', 'az', 'bg', 'de', 'el', 'eu', 'fa', 'fr', 'he', 'hu', 'id', 'it', 'ka', 'nl', 'pl', 'pt', 'pt-BR',
            'ro', 'rs', 'sv', 'tr', 'uk', 'vi', 'zh-CN', 'zh-TW',
        ]);
    }
}
