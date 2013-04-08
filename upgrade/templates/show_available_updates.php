
<table>

<?php
    foreach ($aTemplateFolders as $sFolder => $mixedAvailableResult):

        $sTemplateTdClass = (true === $mixedAvailableResult ? 'avail' : 'not-avail');
?>

<tr class="<?php echo $sTemplateTdClass; ?>">
    <td>
        <?php echo $sFolder; ?>
    </td>
    <td>
        <?php
            if (true === $mixedAvailableResult) {
                echo '<span>Available</span>';
            } else {
                echo $mixedAvailableResult;
            }
        ?>
    </td>
    <td>
        <?php if (true === $mixedAvailableResult): ?>

            <a href="<?php echo BX_DOL_URL_ROOT . 'upgrade/?folder=' . $sFolder ?>" onclick="return confirm('Are you sure?');">UPGRADE</a>

        <?php endif; ?>
    </td>
</tr>

<?php
    endforeach;
?>

</table>

