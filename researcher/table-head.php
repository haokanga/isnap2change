<thead>
<tr>
    <?php for ($i = 0; $i < count($columnName); $i++) { ?>
        <th <?php if ($i == 0) {
            echo 'style="display:none"';
        } ?>><?php echo $columnName[$i]; ?></th>
    <?php } ?>
</tr>
</thead>