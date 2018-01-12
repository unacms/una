#!/bin/sh

# http://cs.sensiolabs.org

# exclude modules/boonex/sites/api/, run after this script: git checkout modules/boonex/sites/api/

PHP="/Applications/MAMP/bin/php/php5.4.10/bin/php"

# FIXERS=indentation,linefeed,trailing_spaces,php_closing_tag,short_tag,braces,phpdoc_params,eof_ending,extra_empty_lines # for v1
FIXERS='{"indentation_type":true,"full_opening_tag":true,"method_argument_space":true,"no_trailing_whitespace":true,"standardize_not_equals":true,"ternary_operator_spaces":true,"binary_operator_spaces":true,"concat_space":{"spacing":"one"}}' # for v2

CSFIXER="./scripts/php-cs-fixer-v2.phar"

find . -name "*.php" -maxdepth 1 -exec ${PHP} ${CSFIXER} fix {} --fixers=${FIXERS} \;
find ./modules/ -name "*.php" -exec ${PHP} ${CSFIXER} fix {} --fixers=${FIXERS} \;

${PHP} ${CSFIXER} fix ./studio/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./inc/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./install/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./periodic/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./template/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./samples/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./tests/units/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./tests/bootstrap.php --fixers=${FIXERS}
# ${PHP} ${CSFIXER} fix ./upgrade/files/7.1.0.B2-7.1.0/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./upgrade/classes/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./upgrade/templates/ --fixers=${FIXERS}
${PHP} ${CSFIXER} fix ./upgrade/index.php --fixers=${FIXERS}
